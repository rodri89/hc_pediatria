<?php

namespace App\Http\Controllers\Api\Salud360;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Base de los controladores de la API que consume la app Salud 360.
 *
 * El usuario autenticado lo deja el middleware `salud360` a partir del token de turnosonlinebb.
 * En pediatría **no existe tabla de médicos**: el médico es una fila de `users` con `usuario_tipo = 2`.
 *
 * IMPORTANTE: ningún controlador de esta API debe tocar `medico_infos`. Esa tabla guarda qué paciente
 * y qué consulta está mirando el médico en la web; si la API la pisa, al pediatra se le mueve la
 * pantalla mientras trabaja. La API recibe siempre los identificadores de forma explícita.
 */
abstract class Salud360Controller extends Controller
{
    const TIPO_ADMIN = 1;
    const TIPO_MEDICO = 2;
    const TIPO_SECRETARIA = 3;

    /** Estados de `consultas.activo` en pediatría. */
    const CONSULTA_BORRADA = 0;
    const CONSULTA_CERRADA = 1;
    const CONSULTA_ABIERTA = 2;

    // ------------------------------------------------------------------
    // Respuestas
    // ------------------------------------------------------------------

    protected function ok(array $data = [], $status = 200)
    {
        return response()->json(array_merge(['ok' => true], $data), $status);
    }

    protected function error($mensaje, $status = 422, $codigo = null, array $extra = [])
    {
        $body = ['ok' => false, 'mensaje' => $mensaje];
        if ($codigo !== null) {
            $body['codigo'] = $codigo;
        }
        return response()->json(array_merge($body, $extra), $status);
    }

    // ------------------------------------------------------------------
    // Usuario
    // ------------------------------------------------------------------

    /** Médico autenticado (fila de `users`). */
    protected function medico(Request $request)
    {
        return $request->user();
    }

    protected function rol($user)
    {
        switch ((int) $user->usuario_tipo) {
            case self::TIPO_ADMIN:
                return 'admin';
            case self::TIPO_MEDICO:
                return 'medico';
            case self::TIPO_SECRETARIA:
                return 'secretaria';
            default:
                return 'desconocido';
        }
    }

    // ------------------------------------------------------------------
    // Formato
    // ------------------------------------------------------------------

    protected function formatearPaciente($p)
    {
        return [
            'id' => (int) $p->id,
            'paciente_id_tobb' => isset($p->paciente_id_tobb) ? (int) $p->paciente_id_tobb : 0,
            'nombre' => $p->nombre,
            'apellido' => $p->apellido,
            'dni' => (string) $p->dni,
            'sexo' => $p->sexo,
            'fecha_nacimiento' => $this->fechaCorta($p->fecha_nacimiento),
            'telefono' => $p->telefono,
            'mail' => $p->mail,
            'domicilio' => $p->domicilio,
            'localidad' => $p->localidad,
            'obra_social' => $p->obra_social,
            'numero_afiliado' => $p->numero_afiliado,
            'obra_social_plan' => $p->obra_social_plan,
            'nombre_padre' => $p->nombre_padre,
            'nombre_madre' => $p->nombre_madre,
            'cantidad_hermanos' => (int) $p->cantidad_hermanos,
            'activo' => (int) $p->activo,
        ];
    }

    protected function formatearConsulta($c, $medicoNombre = null)
    {
        return [
            'id' => (int) $c->id,
            'paciente_id' => (int) $c->paciente_id,
            'medico_id' => (int) $c->medico_id,
            'medico_nombre' => $medicoNombre,
            'tipo' => $this->tipoConsultaTexto((int) $c->tipo_consulta),
            'tipo_numero' => (int) $c->tipo_consulta,
            // En pediatría la fecha clínica de la consulta es su fecha de creación: no hay columna propia.
            'fecha' => $this->fechaCorta($c->created_at),
            'estado' => $this->estadoConsultaTexto((int) $c->activo),
            'edad_mostrar' => $c->edad_mostrar,
            'edad_meses' => (int) $c->edad_paciente,
            'updated_at' => (string) $c->updated_at,
        ];
    }

    /** Códigos de tipo de consulta de la app ↔ números de pediatría. */
    protected function tiposConsulta()
    {
        return [
            'control' => 1,
            'enfermedad' => 2,
            'foto' => 3,
            'telemedicina' => 4,
            'prenatal' => 5,
            'lactancia' => 6,
        ];
    }

    protected function tipoConsultaNumero($codigo)
    {
        $tipos = $this->tiposConsulta();
        return isset($tipos[$codigo]) ? $tipos[$codigo] : 1;
    }

    protected function tipoConsultaTexto($numero)
    {
        $texto = array_flip($this->tiposConsulta());
        return isset($texto[$numero]) ? $texto[$numero] : 'control';
    }

    protected function estadoConsultaTexto($activo)
    {
        switch ((int) $activo) {
            case self::CONSULTA_ABIERTA:
                return 'ABIERTA';
            case self::CONSULTA_CERRADA:
                return 'CERRADA';
            default:
                return 'ANULADA';
        }
    }

    /** `AAAA-MM-DD` a partir de una fecha o fecha y hora; null si no hay valor útil. */
    protected function fechaCorta($valor)
    {
        $texto = (string) $valor;
        if ($texto === '' || substr($texto, 0, 4) === '1000' || substr($texto, 0, 10) === '0000-00-00') {
            return null;
        }
        return substr($texto, 0, 10);
    }

    // ------------------------------------------------------------------
    // Permisos sobre una consulta
    // ------------------------------------------------------------------

    /** Consulta que el médico puede leer: de un paciente de su cartera. */
    protected function consultaVisible($medico, $id)
    {
        $consulta = DB::table('consultas')->where('id', (int) $id)->first();
        if ($consulta === null || (int) $consulta->activo === self::CONSULTA_BORRADA) {
            return $this->error('Consulta no encontrada.', 404, 'no_encontrado');
        }
        if (!$this->atiende($medico->id, $consulta->paciente_id)) {
            return $this->error('Esa consulta no es de un paciente de tu listado.', 403, 'sin_permiso');
        }
        return $consulta;
    }

    /**
     * Consulta que el médico puede escribir: además, propia.
     * Devuelve la fila, o una respuesta de error ya armada. Quien llama debe comprobar
     * `instanceof JsonResponse`: un `is_object()` no alcanza, porque la respuesta también es un objeto.
     */
    protected function consultaPropia($medico, $id)
    {
        $consulta = $this->consultaVisible($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        if ((int) $consulta->medico_id !== (int) $medico->id) {
            return $this->error('Esa consulta la cargó otro médico.', 403, 'consulta_ajena');
        }
        return $consulta;
    }

    /** El paciente está en la cartera del médico. */
    protected function atiende($medicoUserId, $pacienteId)
    {
        return DB::table('medico_pacientes')
            ->where('medico_user_id', $medicoUserId)
            ->where('paciente_id', $pacienteId)
            ->where('activo', 1)
            ->exists();
    }

    protected function marcarActualizada($consultaId)
    {
        DB::table('consultas')->where('id', $consultaId)->update(['updated_at' => Carbon::now()]);
    }
}
