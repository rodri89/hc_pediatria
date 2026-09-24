<?php

namespace App\Services\Salud360;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Resuelve qué paciente de pediatría corresponde a un paciente de la app.
 *
 * Las bases de turnos y de pediatría son independientes y sus ids no coinciden. El vínculo firme es
 * `pacientes.paciente_id_tobb`; cuando todavía no existe se busca por documento, con desempate.
 *
 * Dos reglas que no se negocian:
 *   1. **Nunca adivinar.** Si por documento quedan varios candidatos y no se puede desempatar, se
 *      devuelven todos para que decida el médico. Vincular al hermano equivocado cruzaría dos
 *      historias clínicas, que es el peor daño posible de este proyecto.
 *   2. **Nunca pisar datos con vacíos.** La ficha de pediatría tiene padre, madre, sexo y hermanos
 *      que turnos no conoce; la app manda vacío para esos. Solo se completan campos vacíos.
 */
class PacienteVinculoService
{
    /** Documento del paciente ficticio con el que turnosonlinebb bloquea horarios. */
    const DNI_BLOQUEO = 99999;

    /** Motivos de resolución, para que la app sepa de dónde salió el paciente. */
    const POR_TOBB_ID = 'tobb_id';
    const POR_DNI = 'dni';
    const POR_ELEGIDO = 'elegido';
    const NUEVO = 'nuevo';

    /** @var string|null */
    private $error = null;

    /** @var array */
    private $candidatos = [];

    /** @var bool */
    private $creado = false;

    /** @var string|null */
    private $vinculadoPor = null;

    public function error()
    {
        return $this->error;
    }

    public function candidatos()
    {
        return $this->candidatos;
    }

    public function creado()
    {
        return $this->creado;
    }

    public function vinculadoPor()
    {
        return $this->vinculadoPor;
    }

    /**
     * Devuelve la fila de `pacientes` que corresponde a los datos que manda la app, creándola si hace
     * falta, y deja el vínculo con el médico. Null si no se pudo resolver; entonces [error] dice por qué.
     *
     * @param object $medico  fila de `users` del médico autenticado
     * @param array  $datos   ficha del paciente tal como la tiene la app
     */
    public function resolver($medico, array $datos)
    {
        $this->error = null;
        $this->candidatos = [];
        $this->creado = false;
        $this->vinculadoPor = null;

        $this->asegurarColumna();

        $dni = $this->normalizarDni(isset($datos['dni']) ? $datos['dni'] : null);
        if ($dni === null) {
            $this->error = 'dni_invalido';
            return null;
        }
        $idTobb = isset($datos['paciente_id_tobb']) ? (int) $datos['paciente_id_tobb'] : 0;
        $elegido = isset($datos['pediatria_paciente_id']) ? (int) $datos['pediatria_paciente_id'] : 0;

        return DB::transaction(function () use ($medico, $datos, $dni, $idTobb, $elegido) {
            // 1) Ya vinculado por el id de turnos: el camino normal a partir de la segunda vez.
            if ($idTobb > 0) {
                $fila = DB::table('pacientes')->where('paciente_id_tobb', $idTobb)->where('activo', 1)->lockForUpdate()->first();
                if ($fila !== null) {
                    $this->completarVacios($fila, $datos);
                    $this->asegurarVinculoMedico($medico->id, $fila->id);
                    $this->vinculadoPor = self::POR_TOBB_ID;
                    return DB::table('pacientes')->where('id', $fila->id)->first();
                }
            }

            // 2) El médico ya resolvió una ambigüedad y nos dice con cuál quedarnos.
            if ($elegido > 0) {
                $fila = DB::table('pacientes')->where('id', $elegido)->where('activo', 1)->lockForUpdate()->first();
                if ($fila === null) {
                    $this->error = 'paciente_inexistente';
                    return null;
                }
                $yaTiene = (int) $fila->paciente_id_tobb;
                if ($idTobb > 0 && $yaTiene > 0 && $yaTiene !== $idTobb) {
                    $this->error = 'paciente_ya_vinculado';
                    return null;
                }
                $this->vincular($fila->id, $idTobb, $datos);
                $this->asegurarVinculoMedico($medico->id, $fila->id);
                $this->vinculadoPor = self::POR_ELEGIDO;
                return DB::table('pacientes')->where('id', $fila->id)->first();
            }

            // 3) Por documento.
            $candidatos = DB::table('pacientes')->where('dni', $dni)->where('activo', 1)->get();
            if ($candidatos->count() === 0) {
                $id = $this->crear($dni, $datos);
                $this->asegurarVinculoMedico($medico->id, $id);
                $this->creado = true;
                $this->vinculadoPor = self::NUEVO;
                return DB::table('pacientes')->where('id', $id)->first();
            }

            $unico = $candidatos->count() === 1 ? $candidatos->first() : $this->desempatar($candidatos, $datos);
            if ($unico === null) {
                $this->error = 'paciente_ambiguo';
                $this->candidatos = $this->describirCandidatos($candidatos);
                return null;
            }
            $yaTiene = (int) $unico->paciente_id_tobb;
            if ($idTobb > 0 && $yaTiene > 0 && $yaTiene !== $idTobb) {
                // Ese paciente de pediatría ya está tomado por otro paciente de turnos: que decida una persona.
                $this->error = 'paciente_ya_vinculado';
                $this->candidatos = $this->describirCandidatos($candidatos);
                return null;
            }
            $this->vincular($unico->id, $idTobb, $datos);
            $this->asegurarVinculoMedico($medico->id, $unico->id);
            $this->vinculadoPor = self::POR_DNI;
            return DB::table('pacientes')->where('id', $unico->id)->first();
        });
    }

    /**
     * Entre varios pacientes con el mismo documento, el que coincide en fecha de nacimiento y apellido.
     * Null si no queda exactamente uno: en ese caso decide el médico.
     */
    private function desempatar($candidatos, array $datos)
    {
        $fecha = $this->normalizarFecha(isset($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null);
        $apellido = $this->normalizarTexto(isset($datos['apellido']) ? $datos['apellido'] : '');
        if ($fecha === null || $apellido === '') {
            return null;
        }
        $coinciden = [];
        foreach ($candidatos as $c) {
            $mismaFecha = substr((string) $c->fecha_nacimiento, 0, 10) === $fecha;
            $mismoApellido = $this->normalizarTexto($c->apellido) === $apellido;
            if ($mismaFecha && $mismoApellido) {
                $coinciden[] = $c;
            }
        }
        return count($coinciden) === 1 ? $coinciden[0] : null;
    }

    private function describirCandidatos($candidatos)
    {
        $out = [];
        foreach ($candidatos as $c) {
            $out[] = [
                'id' => (int) $c->id,
                'paciente_id_tobb' => (int) $c->paciente_id_tobb,
                'nombre' => $c->nombre,
                'apellido' => $c->apellido,
                'dni' => (string) $c->dni,
                'fecha_nacimiento' => substr((string) $c->fecha_nacimiento, 0, 10),
                'cantidad_consultas' => (int) DB::table('consultas')->where('paciente_id', $c->id)->where('activo', '>', 0)->count(),
            ];
        }
        return $out;
    }

    private function vincular($pacienteId, $idTobb, array $datos)
    {
        $fila = DB::table('pacientes')->where('id', $pacienteId)->first();
        if ($idTobb > 0 && (int) $fila->paciente_id_tobb !== $idTobb) {
            DB::table('pacientes')->where('id', $pacienteId)->update([
                'paciente_id_tobb' => $idTobb,
                'updated_at' => Carbon::now(),
            ]);
            $fila = DB::table('pacientes')->where('id', $pacienteId)->first();
        }
        $this->completarVacios($fila, $datos);
    }

    /** Completa SOLO los campos vacíos de pediatría con lo que manda la app. Nunca sobrescribe. */
    private function completarVacios($fila, array $datos)
    {
        $mapa = [
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'telefono' => 'telefono',
            'mail' => 'mail',
            'domicilio' => 'domicilio',
            'localidad' => 'localidad',
            'obra_social' => 'obra_social',
            'numero_afiliado' => 'numero_afiliado',
            'obra_social_plan' => 'obra_social_plan',
            'sexo' => 'sexo',
            'nombre_padre' => 'nombre_padre',
            'nombre_madre' => 'nombre_madre',
        ];
        $cambios = [];
        foreach ($mapa as $columna => $clave) {
            $actual = trim((string) $fila->$columna);
            $nuevo = trim((string) (isset($datos[$clave]) ? $datos[$clave] : ''));
            if ($actual === '' && $nuevo !== '') {
                $cambios[$columna] = $nuevo;
            }
        }
        $fechaActual = substr((string) $fila->fecha_nacimiento, 0, 10);
        $fechaNueva = $this->normalizarFecha(isset($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null);
        if ($fechaNueva !== null && ($fechaActual === '' || $fechaActual === '0000-00-00' || substr($fechaActual, 0, 4) === '1000')) {
            $cambios['fecha_nacimiento'] = $fechaNueva;
        }
        if (count($cambios) > 0) {
            $cambios['updated_at'] = Carbon::now();
            DB::table('pacientes')->where('id', $fila->id)->update($cambios);
        }
    }

    /** Alta con las 18 columnas: todas son NOT NULL y sin valor por defecto. */
    private function crear($dni, array $datos)
    {
        $ahora = Carbon::now();
        $texto = function ($clave) use ($datos) {
            return trim((string) (isset($datos[$clave]) ? $datos[$clave] : ''));
        };
        $fecha = $this->normalizarFecha(isset($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null);
        return DB::table('pacientes')->insertGetId([
            'paciente_id_tobb' => isset($datos['paciente_id_tobb']) ? (int) $datos['paciente_id_tobb'] : 0,
            'nombre' => $texto('nombre'),
            'apellido' => $texto('apellido'),
            'dni' => $dni,
            'telefono' => $texto('telefono'),
            'domicilio' => $texto('domicilio'),
            'mail' => $texto('mail'),
            'fecha_nacimiento' => $fecha === null ? '1000-01-01' : $fecha,
            'obra_social' => $texto('obra_social'),
            'numero_afiliado' => $texto('numero_afiliado'),
            'obra_social_plan' => $texto('obra_social_plan'),
            'obra_social_foto' => '',
            'sexo' => $texto('sexo'),
            'nombre_padre' => $texto('nombre_padre'),
            'nombre_madre' => $texto('nombre_madre'),
            'cantidad_hermanos' => isset($datos['cantidad_hermanos']) ? (int) $datos['cantidad_hermanos'] : 0,
            'localidad' => $texto('localidad'),
            'activo' => 1,
            'created_at' => $ahora,
            'updated_at' => $ahora,
        ]);
    }

    /** El paciente tiene que figurar en la cartera del médico para que lo vea en su listado. */
    private function asegurarVinculoMedico($medicoUserId, $pacienteId)
    {
        $ahora = Carbon::now();
        $fila = DB::table('medico_pacientes')
            ->where('medico_user_id', $medicoUserId)
            ->where('paciente_id', $pacienteId)
            ->first();
        if ($fila === null) {
            DB::table('medico_pacientes')->insert([
                'medico_user_id' => (int) $medicoUserId,
                'paciente_id' => (int) $pacienteId,
                'activo' => 1,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
            return;
        }
        if ((int) $fila->activo !== 1) {
            DB::table('medico_pacientes')->where('id', $fila->id)->update(['activo' => 1, 'updated_at' => $ahora]);
        }
    }

    /** El documento es `int(11)` en pediatría: hay que validarlo antes de tocar la base. */
    private function normalizarDni($valor)
    {
        $texto = preg_replace('/\D/', '', (string) $valor);
        if ($texto === '') {
            return null;
        }
        $numero = (int) $texto;
        if ($numero < 1000 || $numero > 2147483647 || $numero === self::DNI_BLOQUEO) {
            return null;
        }
        return $numero;
    }

    private function normalizarFecha($valor)
    {
        $texto = trim((string) $valor);
        if ($texto === '') {
            return null;
        }
        $texto = str_replace('/', '-', substr($texto, 0, 10));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $texto) || substr($texto, 0, 4) === '1000') {
            return null;
        }
        return $texto;
    }

    /** Mayúsculas, sin tildes ni espacios de más, para comparar apellidos. */
    private function normalizarTexto($valor)
    {
        $texto = mb_strtoupper(trim((string) $valor), 'UTF-8');
        $texto = strtr($texto, ['Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N']);
        return preg_replace('/\s+/', ' ', $texto);
    }

    /** Crea la columna del vínculo si no existe (el hosting no corre migraciones). */
    public function asegurarColumna()
    {
        if (Schema::hasColumn('pacientes', 'paciente_id_tobb')) {
            return;
        }
        Schema::table('pacientes', function (Blueprint $t) {
            $t->unsignedInteger('paciente_id_tobb')->default(0)->after('id')->index();
        });
    }
}
