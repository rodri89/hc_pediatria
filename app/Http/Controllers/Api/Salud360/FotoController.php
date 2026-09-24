<?php

namespace App\Http\Controllers\Api\Salud360;

use App\Services\Salud360\FotosService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Las fotos y los archivos adjuntos de la historia clínica, desde la app.
 *
 * Un archivo por pedido, y en multiparte: el médico saca la foto en el consultorio, donde la señal
 * es mala, y si se corta a la mitad se reintenta esa sola y no las diez de la consulta.
 *
 * El archivo se sirve por acá y no por su URL pública. La web de pediatría deja las fotos en
 * `public/img/…`, que cualquiera con el enlace puede abrir; la app ya manda el token en cada pedido,
 * así que para ella no cuesta nada pedirlas por un camino que comprueba de quién es el paciente.
 * La URL pública igual se devuelve, porque es la que usa la web.
 *
 * REGLA QUE NO SE TOCA: este controlador NO escribe ni lee `medico_infos`. Ver `Salud360Controller`.
 */
class FotoController extends Salud360Controller
{
    /** Tamaño máximo de un adjunto. Más que esto es una foto sin achicar o un PDF escaneado entero. */
    const MAX_BYTES = 12582912;

    /** @var FotosService */
    private $fotos;

    public function __construct(FotosService $fotos)
    {
        $this->fotos = $fotos;
    }

    /** GET consultas/{id}/fotos */
    public function index(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaVisible($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        return $this->ok(['fotos' => $this->fotos->leer($consulta->id, $consulta->paciente_id)]);
    }

    /**
     * POST consultas/{id}/fotos  (multipart)
     *   tipo       sección de la app: consulta | neonatales | examen_complementario | internacion | familigrama
     *   ref        id que tiene el archivo en el dispositivo
     *   padre_id   fila de la lista de la que cuelga, cuando el tipo lo pide
     *   archivo    el archivo
     */
    public function store(Request $request, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, $id);
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }

        $tipo = (string) $request->input('tipo');
        $ref = (string) $request->input('ref');
        if ($tipo === '' || $ref === '') {
            return $this->error('Faltan el tipo y la referencia del archivo.', 422, 'datos');
        }
        if (!$request->hasFile('archivo')) {
            return $this->error('No llegó ningún archivo.', 422, 'datos');
        }

        $archivo = $request->file('archivo');
        if (!$archivo->isValid()) {
            // Casi siempre es `upload_max_filesize` o `post_max_size` del PHP del hosting.
            return $this->error('El archivo llegó incompleto.', 422, 'archivo_invalido');
        }
        if ($archivo->getSize() > self::MAX_BYTES) {
            return $this->error('El archivo supera los 12 MB.', 422, 'archivo_grande');
        }

        $r = $this->fotos->guardar(
            $consulta->id,
            $consulta->paciente_id,
            $medico,
            $tipo,
            $archivo,
            (int) $request->input('padre_id')
        );
        if (isset($r['error'])) {
            return $this->errorDeGuardado($r['error'], $tipo);
        }

        $this->marcarActualizada($consulta->id);
        return $this->ok([
            'ids' => [$ref => $r['id']],
            'id' => $r['id'],
            'url' => $r['url'],
            'updated_at' => (string) Carbon::now(),
        ]);
    }

    /**
     * GET fotos/{tipo}/{id}/archivo?consulta_id= — el archivo en sí.
     *
     * Pide la consulta para saber de qué paciente es y comprobar que está en la cartera del médico:
     * alcanza con poder leerla, no hace falta que la consulta sea propia.
     */
    public function archivo(Request $request, $tipo, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaVisible($medico, (int) $request->input('consulta_id'));
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        $fila = $this->fotos->fila($consulta->paciente_id, $tipo, $id);
        if ($fila === null) {
            return $this->error('Archivo no encontrado.', 404, 'no_encontrado');
        }
        $ruta = $this->fotos->rutaEnDisco($fila['archivo']);
        if (!is_file($ruta)) {
            // La fila está pero el archivo no: pasa con las fotos viejas de la web, subidas a otro
            // hosting. Se avisa con un código propio para que la app no lo tome como error de red.
            return $this->error('El archivo no está en el servidor.', 404, 'archivo_ausente', [
                'url' => $fila['url'],
            ]);
        }
        return response()->file($ruta);
    }

    /** DELETE fotos/{tipo}/{id}?consulta_id= — baja lógica, el archivo queda en el disco. */
    public function destroy(Request $request, $tipo, $id)
    {
        $medico = $this->medico($request);
        $consulta = $this->consultaPropia($medico, (int) $request->input('consulta_id'));
        if ($consulta instanceof JsonResponse) {
            return $consulta;
        }
        if (!$this->fotos->borrar($consulta->paciente_id, $tipo, $id)) {
            return $this->error('Archivo no encontrado.', 404, 'no_encontrado');
        }
        $this->marcarActualizada($consulta->id);
        return $this->ok(['id' => (string) $id, 'updated_at' => (string) Carbon::now()]);
    }

    private function errorDeGuardado($codigo, $tipo)
    {
        switch ($codigo) {
            case 'tipo_desconocido':
                return $this->error('La sección "' . $tipo . '" no acepta adjuntos.', 422, 'tipo_desconocido');
            case 'tipo_de_archivo':
                return $this->error('Solo se aceptan imágenes y PDF.', 422, 'tipo_de_archivo');
            case 'padre_no_encontrado':
                return $this->error('No se encontró la fila a la que se adjunta.', 422, 'padre_no_encontrado');
            default:
                return $this->error('No se pudo guardar el archivo.', 500, 'no_se_pudo_guardar');
        }
    }
}
