<?php

namespace App\Http\Controllers\Api\Salud360;

use App\Services\Salud360\RegistrosService;
use App\Services\Salud360\SeccionesService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Consultas (evoluciones) de pediatría desde la app.
 *
 * REGLA QUE NO SE TOCA: este controlador NO escribe ni lee `medico_infos`. Esa tabla guarda qué
 * paciente y qué consulta está mirando el médico en la web; si la API la pisa, al pediatra se le
 * mueve la pantalla mientras está atendiendo. La web la usa porque navega por estado; la API recibe
 * siempre los identificadores de forma explícita. No copiar el patrón de `MedicoController`.
 */
class ConsultaController extends Salud360Controller
{
    /** @var SeccionesService */
    private $secciones;

    /** @var RegistrosService */
    private $registros;

    public function __construct(SeccionesService $secciones, RegistrosService $registros)
    {
        $this->secciones = $secciones;
        $this->registros = $registros;
    }

    /** GET consultas?paciente_id=&limite= */
    public function index(Request $request)
    {
        $medico = $this->medico($request);
        $pacienteId = (int) $request->input('paciente_id');
        if ($pacienteId <= 0) {
            return $this->error('Falta el paciente.', 422, 'datos');
        }
        if (!$this->atiende($medico->id, $pacienteId)) {
            return $this->error('Ese paciente no está en tu listado.', 403, 'sin_permiso');
        }
        $limite = min(max((int) $request->input('limite', 50), 1), 200);

        $filas = DB::table('consultas')
            ->where('paciente_id', $pacienteId)
            ->where('activo', '>', self::CONSULTA_BORRADA)
            ->orderBy('created_at', 'desc')
            ->limit($limite)
            ->get();

        $nombres = $this->nombresDeMedicos($filas);
        $consultas = [];
        foreach ($filas as $c) {
            $item = $this->formatearConsulta($c, isset($nombres[(int) $c->medico_id]) ? $nombres[(int) $c->medico_id] : null);
            $item['propia'] = (int) $c->medico_id === (int) $medico->id;
            $consultas[] = $item;
        }
        return $this->ok(['consultas' => $consultas]);
    }

    /** POST consultas/abrir  { paciente_id, tipo, fecha } */
    public function abrir(Request $request)
    {
        $medico = $this->medico($request);
        $pacienteId = (int) $request->input('paciente_id');
        if ($pacienteId <= 0) {
            return $this->error('Falta el paciente.', 422, 'datos');
        }
        if (!$this->atiende($medico->id, $pacienteId)) {
            return $this->error('Ese paciente no está en tu listado.', 403, 'sin_permiso');
        }
        $paciente = DB::table('pacientes')->where('id', $pacienteId)->where('activo', 1)->first();
        if ($paciente === null) {
            return $this->error('Paciente no encontrado.', 404, 'no_encontrado');
        }
        $tipo = (string) $request->input('tipo', 'control');
        $numero = $this->tipoConsultaNumero($tipo);
        $fecha = $this->fechaPedida($request->input('fecha'));

        // Si el médico ya tiene una consulta abierta de ese tipo para ese paciente, se reutiliza:
        // evita duplicados cuando la app reintenta después de quedarse sin señal.
        $abierta = DB::table('consultas')
            ->where('paciente_id', $pacienteId)
            ->where('medico_id', $medico->id)
            ->where('tipo_consulta', $numero)
            ->where('activo', self::CONSULTA_ABIERTA)
            ->orderBy('id', 'desc')
            ->first();
        if ($abierta !== null) {
            return $this->ok([
                'consulta' => $this->formatearConsulta($abierta, $medico->name),
                'reutilizada' => true,
            ]);
        }

        $ahora = Carbon::now();
        $id = DB::table('consultas')->insertGetId([
            'paciente_id' => $pacienteId,
            'medico_id' => (int) $medico->id,
            'tipo_consulta' => $numero,
            'es_foto' => $tipo === 'foto' ? 1 : 0,
            'edad_paciente' => $this->edadEnMeses($paciente->fecha_nacimiento, $fecha),
            'edad_mostrar' => (string) $request->input('edad_mostrar', ''),
            'activo' => self::CONSULTA_ABIERTA,
            // En pediatría la fecha clínica de la consulta es su fecha de creación.
            'created_at' => $fecha,
            'updated_at' => $ahora,
        ]);
        $consulta = DB::table('consultas')->where('id', $id)->first();
        return $this->ok([
            'consulta' => $this->formatearConsulta($consulta, $medico->name),
            'reutilizada' => false,
        ], 201);
    }

    /** GET consultas/{id} */
    public function show(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaVisible($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        return $this->ok([
            'consulta' => $this->formatearConsulta($consulta, $this->nombreMedico($consulta->medico_id)),
            'secciones' => $this->secciones->leer($consulta->id, $consulta->paciente_id),
            'examen_fisico' => $this->secciones->leerExamen($consulta->id, $consulta->paciente_id),
            'registros' => $this->registros->leer($consulta->id, $consulta->paciente_id),
        ]);
    }

    /** PUT consultas/{id}/registros  { registros: [ {ref, tipo, [id], [fecha], campos, [borrado]} ] } */
    public function guardarRegistros(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        $registros = $request->input('registros');
        if (!is_array($registros)) {
            return $this->error('Falta el cuerpo con los registros.', 422, 'datos');
        }
        $r = $this->registros->guardar($consulta->id, $consulta->paciente_id, $registros);
        $this->marcarActualizada($consulta->id);
        return $this->ok([
            'ids' => $r['ids'],
            'desconocidos' => $r['desconocidos'],
            'updated_at' => (string) Carbon::now(),
        ]);
    }

    /** PUT consultas/{id}/secciones  { secciones: { "<id>": {"texto": "..."} } } */
    public function guardarSecciones(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        $secciones = $request->input('secciones');
        if (!is_array($secciones)) {
            return $this->error('Falta el cuerpo con las secciones.', 422, 'datos');
        }
        $r = $this->secciones->guardar($consulta->id, $consulta->paciente_id, $secciones);
        $this->marcarActualizada($consulta->id);
        return $this->ok([
            'guardadas' => $r['guardadas'],
            'desconocidas' => $r['desconocidas'],
            'updated_at' => (string) Carbon::now(),
        ]);
    }

    /** PUT consultas/{id}/examen */
    public function guardarExamen(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        $datos = $request->input('examen_fisico');
        if (!is_array($datos)) {
            $datos = $request->except(['_token']);
        }
        $this->secciones->guardarExamen($consulta->id, $consulta->paciente_id, $datos);
        $this->marcarActualizada($consulta->id);
        return $this->ok([
            'examen_fisico' => $this->secciones->leerExamen($consulta->id, $consulta->paciente_id),
            'updated_at' => (string) Carbon::now(),
        ]);
    }

    /** POST consultas/{id}/cerrar  { fecha, edad_mostrar } */
    public function cerrar(Request $request, $id)
    {
        $r = $this->cambiarEstado($request, $id, self::CONSULTA_CERRADA);
        // Al cerrar, lo que se cargó en esta consulta pasa a la historia del paciente. Sin esto la web
        // no vuelve a mostrar los exámenes complementarios ni las interconsultas (ver RegistrosService).
        if ($r->getStatusCode() === 200) {
            $this->registros->confirmarDeConsulta((int) $id);
        }
        return $r;
    }

    /** POST consultas/{id}/reabrir */
    public function reabrir(Request $request, $id)
    {
        return $this->cambiarEstado($request, $id, self::CONSULTA_ABIERTA);
    }

    /** DELETE consultas/{id} (baja lógica, como en la web) */
    public function destroy(Request $request, $id)
    {
        return $this->cambiarEstado($request, $id, self::CONSULTA_BORRADA);
    }

    private function cambiarEstado(Request $request, $id, $activo)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        $cambios = ['activo' => $activo, 'updated_at' => Carbon::now()];

        $edad = $request->input('edad_mostrar');
        if ($edad !== null && $edad !== '') {
            $cambios['edad_mostrar'] = (string) $edad;
        }
        $fecha = $request->input('fecha');
        if ($fecha !== null && $fecha !== '') {
            // Igual que `establecerActivo` de la web: la fecha de la consulta es su fecha de creación.
            $cambios['created_at'] = $this->fechaPedida($fecha);
            $paciente = DB::table('pacientes')->where('id', $consulta->paciente_id)->first();
            if ($paciente !== null) {
                $cambios['edad_paciente'] = $this->edadEnMeses($paciente->fecha_nacimiento, $cambios['created_at']);
            }
        }
        DB::table('consultas')->where('id', $consulta->id)->update($cambios);
        $actual = DB::table('consultas')->where('id', $consulta->id)->first();
        return $this->ok(['consulta' => $this->formatearConsulta($actual, $medico->name)]);
    }

    // ------------------------------------------------------------------
    // Apoyo
    // ------------------------------------------------------------------

    /** Consulta que el médico puede leer: de un paciente de su cartera. */
    private function consultaVisible($medico, $id)
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
    private function consultaPropia($medico, $id)
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

    private function atiende($medicoUserId, $pacienteId)
    {
        return DB::table('medico_pacientes')
            ->where('medico_user_id', $medicoUserId)
            ->where('paciente_id', $pacienteId)
            ->where('activo', 1)
            ->exists();
    }

    private function marcarActualizada($consultaId)
    {
        DB::table('consultas')->where('id', $consultaId)->update(['updated_at' => Carbon::now()]);
    }

    private function nombreMedico($medicoId)
    {
        $u = DB::table('users')->where('id', (int) $medicoId)->first();
        return $u === null ? null : $u->name;
    }

    private function nombresDeMedicos($filas)
    {
        $ids = [];
        foreach ($filas as $f) {
            $ids[] = (int) $f->medico_id;
        }
        $ids = array_values(array_unique($ids));
        if (count($ids) === 0) {
            return [];
        }
        $out = [];
        foreach (DB::table('users')->whereIn('id', $ids)->get() as $u) {
            $out[(int) $u->id] = $u->name;
        }
        return $out;
    }

    /** Fecha del pedido como fecha y hora; si no viene o es inválida, ahora. */
    private function fechaPedida($valor)
    {
        $texto = trim((string) $valor);
        if ($texto === '') {
            return Carbon::now();
        }
        $solo = str_replace('/', '-', substr($texto, 0, 10));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $solo)) {
            return Carbon::now();
        }
        try {
            // Se conserva la hora actual para no dejar todas las consultas del día a medianoche.
            $ahora = Carbon::now();
            return Carbon::parse($solo)->setTime($ahora->hour, $ahora->minute, $ahora->second);
        } catch (\Throwable $e) {
            return Carbon::now();
        }
    }

    /** Edad en meses del paciente a la fecha de la consulta. La web la deja en 0; acá se calcula. */
    private function edadEnMeses($fechaNacimiento, $fechaConsulta)
    {
        $nac = trim((string) $fechaNacimiento);
        if ($nac === '' || substr($nac, 0, 4) === '1000' || substr($nac, 0, 10) === '0000-00-00') {
            return 0;
        }
        try {
            $desde = Carbon::parse(substr($nac, 0, 10));
            $hasta = $fechaConsulta instanceof Carbon ? $fechaConsulta : Carbon::parse((string) $fechaConsulta);
            $meses = $desde->diffInMonths($hasta);
            return $meses < 0 ? 0 : $meses;
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
