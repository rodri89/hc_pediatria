<?php

namespace App\Services\Salud360;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Las listas de la historia clínica: exámenes complementarios, interconsultas, screenings e
 * internaciones. Son varias filas por sección, a diferencia de las secciones y los formularios.
 *
 * En la app son filas de `registro_clinico`, con id propio y campos libres. Acá cada una tiene su
 * tabla, con columnas fijas y dos números que la web usa para navegar entre ellas:
 *
 * - **`numero`** es la posición dentro del paciente, la que mueven los botones "anterior" y
 *   "siguiente". Se asigna al crear y no se cambia más.
 * - **`numero_consulta`** es la posición dentro de la consulta, y solo lo llevan las dos tablas que
 *   distinguen "de esta consulta" de "de consultas anteriores".
 *
 * Esa distinción es el otro dato a no perder de vista: exámenes complementarios e interconsultas
 * **nacen con `activo = 2`** ("cargado en la consulta que está abierta") y pasan a `1` recién cuando
 * se cierra la consulta, igual que hace `establecerActivo` de la web. Screenings e internaciones no
 * tienen esa vuelta: nacen en 1.
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class RegistrosService
{
    /** Fecha con la que la web marca "sin fecha" en estas tablas. */
    const SIN_FECHA = '1900-01-01';

    /** @var SeccionesService para asegurar la ficha de la que cuelgan las internaciones */
    private $secciones;

    public function __construct(SeccionesService $secciones)
    {
        $this->secciones = $secciones;
    }

    /**
     * Tipo de registro en la app => cómo se guarda en pediatría.
     *
     *   tabla         la de pediatría;
     *   lectura       'consulta' si la lista es la de esta consulta, 'paciente' si es la del paciente
     *                 entera (así las modela la app);
     *   con_consulta  si la tabla lleva `consulta_id` (screenings no);
     *   por_confirmar si nace en `activo = 2` y se confirma al cerrar la consulta;
     *   fecha         columna de fecha, o null si la tabla no tiene;
     *   campos        campo de la app => columna;
     *   numero_consulta  si la tabla lleva esa segunda numeración;
     *   respuesta     campo cuya carga anota en `consulta_respuesta` la consulta donde se respondió;
     *   cuelga_de     sección de cuya fila depende por clave foránea (las internaciones).
     */
    const TIPOS = [
        'examen_complementario' => [
            'tabla' => 'examenes_complementarios',
            'lectura' => 'consulta',
            'con_consulta' => true,
            'por_confirmar' => true,
            'fecha' => 'fechaSolicitud',
            'numero_consulta' => true,
            'campos' => ['solicito' => 'solicito', 'respuesta' => 'respuesta'],
            'respuesta' => 'respuesta',
        ],
        'interconsulta' => [
            'tabla' => 'interconsultas',
            'lectura' => 'consulta',
            'con_consulta' => true,
            'por_confirmar' => true,
            'fecha' => 'fechaSolicitud',
            'numero_consulta' => true,
            'campos' => ['especialista' => 'especialista', 'solicito' => 'solicito', 'respuesta' => 'respuesta'],
            'respuesta' => 'respuesta',
        ],
        'screening' => [
            'tabla' => 'screenings',
            'lectura' => 'paciente',
            'con_consulta' => false,
            'por_confirmar' => false,
            'fecha' => 'fechaSolicitud',
            'campos' => ['evaluacion' => 'evaluacion', 'respuesta' => 'respuesta'],
        ],
        'internacion' => [
            'tabla' => 'internaciones',
            'lectura' => 'paciente',
            'con_consulta' => true,
            'por_confirmar' => false,
            'fecha' => null,
            'campos' => [
                'motivo' => 'motivo', 'lugar' => 'lugar', 'duracion' => 'duracion',
                'indicacion_alta' => 'indicacion_alta',
            ],
            // En pediatría la internación cuelga por clave foránea de la ficha de antecedentes
            // personales de la consulta, que es desde donde se la carga en la web.
            'cuelga_de' => ['seccion' => 'antecedentes_personales', 'columna' => 'antecedentes_personales_id'],
        ],
    ];

    /** Todas las listas que la app puede mostrar en esta consulta. */
    public function leer($consultaId, $pacienteId)
    {
        $out = [];
        foreach (self::TIPOS as $tipo => $def) {
            $q = DB::table($def['tabla'])->where('paciente_id', $pacienteId)->whereIn('activo', [1, 2]);
            if ($def['lectura'] === 'consulta') {
                $q->where('consulta_id', $consultaId);
            }
            foreach ($q->orderBy('numero')->get() as $fila) {
                $campos = [];
                foreach ($def['campos'] as $campo => $columna) {
                    $campos[$campo] = (string) $fila->$columna;
                }
                $item = ['id' => (string) $fila->id, 'tipo' => $tipo, 'campos' => $campos];
                if ($def['fecha'] !== null) {
                    $fecha = (string) $fila->{$def['fecha']};
                    $item['fecha'] = ($fecha === '' || $fecha === self::SIN_FECHA) ? null : substr($fecha, 0, 10);
                }
                $out[] = $item;
            }
        }
        return $out;
    }

    /**
     * Guarda las filas que manda la app. Cada una trae su `ref` (el id que tiene en el dispositivo) y,
     * si ya se había enviado, su `id` de pediatría. Devuelve `ref => id` para que la app anote el
     * vínculo y la próxima vez modifique en lugar de duplicar.
     *
     * Una fila con `borrado` se da de baja lógica, como en la web.
     */
    public function guardar($consultaId, $pacienteId, array $registros)
    {
        $ids = [];
        $desconocidos = [];
        foreach ($registros as $r) {
            if (!is_array($r) || !isset($r['tipo']) || !isset($r['ref'])) {
                continue;
            }
            if (!isset(self::TIPOS[$r['tipo']])) {
                $desconocidos[] = (string) $r['tipo'];
                continue;
            }
            $id = $this->guardarUno(self::TIPOS[$r['tipo']], $consultaId, $pacienteId, $r);
            if ($id !== null) {
                $ids[(string) $r['ref']] = (string) $id;
            }
        }
        return ['ids' => $ids, 'desconocidos' => array_values(array_unique($desconocidos))];
    }

    private function guardarUno(array $def, $consultaId, $pacienteId, array $r)
    {
        $ahora = Carbon::now();
        $remotoId = isset($r['id']) ? (int) $r['id'] : 0;
        $fila = $remotoId > 0
            ? DB::table($def['tabla'])->where('id', $remotoId)->where('paciente_id', $pacienteId)->first()
            : null;

        if (!empty($r['borrado'])) {
            if ($fila !== null) {
                DB::table($def['tabla'])->where('id', $fila->id)->update(['activo' => 0, 'updated_at' => $ahora]);
            }
            return $fila === null ? null : $fila->id;
        }

        $campos = isset($r['campos']) && is_array($r['campos']) ? $r['campos'] : [];
        $valores = ['updated_at' => $ahora];
        foreach ($def['campos'] as $campo => $columna) {
            if (array_key_exists($campo, $campos)) {
                $valores[$columna] = (string) $campos[$campo];
            }
        }
        if ($def['fecha'] !== null && array_key_exists('fecha', $r)) {
            $valores[$def['fecha']] = $r['fecha'] === null || $r['fecha'] === '' ? self::SIN_FECHA : $r['fecha'];
        }
        // La web anota en qué consulta se cargó la respuesta, y deja 0 mientras no hay ninguna.
        if (isset($def['respuesta']) && array_key_exists($def['respuesta'], $campos)) {
            $valores['consulta_respuesta'] = trim((string) $campos[$def['respuesta']]) === '' ? 0 : (int) $consultaId;
        }

        if ($fila !== null) {
            DB::table($def['tabla'])->where('id', $fila->id)->update($valores);
            return $fila->id;
        }
        return $this->insertar($def, $consultaId, $pacienteId, $valores, $ahora);
    }

    private function insertar(array $def, $consultaId, $pacienteId, array $valores, $ahora)
    {
        $omitir = array_merge(['numero'], $def['fecha'] === null ? [] : [$def['fecha']]);
        if (isset($def['cuelga_de'])) {
            $omitir[] = $def['cuelga_de']['columna'];
        }
        if (isset($def['respuesta'])) {
            $omitir[] = 'consulta_respuesta';
        }
        $valores = $valores + ColumnasLegacy::vacias($def['tabla'], [], $omitir);

        if (isset($def['cuelga_de'])) {
            $padre = $this->secciones->asegurarFila($def['cuelga_de']['seccion'], $consultaId, $pacienteId);
            if ($padre === null) {
                return null;
            }
            $valores[$def['cuelga_de']['columna']] = (int) $padre;
        }
        $valores['paciente_id'] = (int) $pacienteId;
        $valores['numero'] = $this->proximoNumero($def['tabla'], $pacienteId);
        $valores['activo'] = $def['por_confirmar'] ? SeccionesService::SIN_CARGAR : 1;
        $valores['created_at'] = $ahora;
        if ($def['con_consulta']) {
            $valores['consulta_id'] = (int) $consultaId;
        }
        if (!empty($def['numero_consulta'])) {
            $valores['numero_consulta'] = $this->proximoNumeroDeConsulta($def['tabla'], $consultaId);
        }
        if ($def['fecha'] !== null && !array_key_exists($def['fecha'], $valores)) {
            $valores[$def['fecha']] = self::SIN_FECHA;
        }
        if (isset($def['respuesta']) && !array_key_exists('consulta_respuesta', $valores)) {
            $valores['consulta_respuesta'] = 0;
        }
        return DB::table($def['tabla'])->insertGetId($valores);
    }

    /** Posición dentro del paciente: la que mueven "anterior" y "siguiente" en la web. */
    private function proximoNumero($tabla, $pacienteId)
    {
        $max = DB::table($tabla)->where('paciente_id', $pacienteId)->whereIn('activo', [1, 2])->max('numero');
        return ((int) $max) + 1;
    }

    private function proximoNumeroDeConsulta($tabla, $consultaId)
    {
        $max = DB::table($tabla)->where('consulta_id', $consultaId)->whereIn('activo', [1, 2])->max('numero_consulta');
        return ((int) $max) + 1;
    }

    /**
     * Al cerrar la consulta, lo que se cargó en ella deja de ser "de la consulta abierta" y pasa a la
     * historia del paciente. Es lo que hacen `setActivoExamenesComplementarios` y
     * `setActivoInterconsulta` de la web, y sin esto la web no los vuelve a mostrar nunca.
     */
    public function confirmarDeConsulta($consultaId)
    {
        foreach (self::TIPOS as $def) {
            if (!$def['por_confirmar']) {
                continue;
            }
            DB::table($def['tabla'])
                ->where('consulta_id', $consultaId)
                ->where('activo', SeccionesService::SIN_CARGAR)
                ->update(['activo' => 1, 'updated_at' => Carbon::now()]);
        }
    }
}
