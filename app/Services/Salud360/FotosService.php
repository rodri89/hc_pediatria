<?php

namespace App\Services\Salud360;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Las fotos y los archivos adjuntos de la historia clínica.
 *
 * En la app son filas de la tabla `archivo`: el médico saca una foto con el teléfono y queda
 * guardada en el dispositivo, con señal o sin ella. Acá cada sección que acepta adjuntos tiene su
 * propia tabla de fotos, todas con la misma forma (`consulta_id`, `paciente_id`, la clave foránea a
 * la ficha de la que cuelgan, `numero`, `foto` y `activo`).
 *
 * Tres cosas para entenderlas:
 *
 * - **La columna `foto` no guarda una ruta completa**, sino lo que va después de `img/`. La web la
 *   lee como `img/<lo que dice la columna>`, así que el archivo vive en `public/img/…`. Lo mismo
 *   hace esta clase, y por eso lo que sube la app se ve en la web sin ningún cambio del otro lado.
 *   La web reparte sus fotos en una carpeta por médico y otra por sección; la app las deja todas en
 *   [CARPETA], porque el hosting no le deja crear directorios.
 * - **El nombre del archivo se sortea**, nunca se usa el que traía. Dos madres que mandan
 *   `ecografia.jpg` no se pisan, y el nombre original no dice nada que la historia clínica necesite.
 * - **Borrar es `activo = 0`**, y el archivo queda en el disco. Es lo que hace hoy la web, que no
 *   tiene borrado: una foto de una historia clínica no se tira porque alguien tocó el botón
 *   equivocado en el teléfono.
 *
 * A diferencia de la web, acá el redimensionado **respeta la proporción** y nunca agranda: la web
 * llama a `resize(1980, 1920)` sin conservar el lado, y deforma todo lo que no sea de esa medida
 * exacta. Y a diferencia de la web, se aceptan PDF, que se guardan tal cual: la app los deja
 * adjuntar y la web todavía no los muestra.
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class FotosService
{
    /**
     * Carpeta única de los adjuntos que sube la app, dentro de `public/img/`. Se crea una sola vez y
     * a mano: en el hosting compartido el PHP no tiene permiso para crear directorios.
     */
    const CARPETA = 'salud360';

    /** Lado máximo de una imagen guardada. Las más chicas no se tocan. */
    const ANCHO_MAX = 1980;
    const ALTO_MAX = 1920;

    /** Lo que se acepta subir. El resto se rechaza con `tipo_de_archivo`. */
    const MIMES = [
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf',
    ];

    /** @var SeccionesService para asegurar la ficha de la que cuelgan las fotos de antecedentes */
    private $secciones;

    public function __construct(SeccionesService $secciones)
    {
        $this->secciones = $secciones;
    }

    /**
     * Sección de la app => dónde se guardan sus adjuntos.
     *
     *   tabla          la de pediatría;
     *   carpeta        prefijo del nombre del archivo, con el que la web nombra esa sección;
     *   lectura        'consulta' si la galería es la de esta consulta, 'paciente' si es la del
     *                  paciente entera (así las modela la app);
     *   con_consulta   si la tabla lleva `consulta_id` (el familigrama no);
     *   con_numero     si lleva `numero`, la posición que mueven "anterior" y "siguiente";
     *   cuelga_de      sección de cuyo formulario depende por clave foránea;
     *   cuelga_de_registro  lista de cuya fila depende: la app manda el id de esa fila en `padre_id`.
     */
    const TIPOS = [
        'consulta' => [
            'tabla' => 'consulta_fotos',
            'carpeta' => 'consulta_foto',
            'lectura' => 'consulta',
            'con_consulta' => true,
            'con_numero' => true,
        ],
        'neonatales' => [
            'tabla' => 'antecedentes_neonatales_fotos',
            'carpeta' => 'antecedentes_neonatales',
            'lectura' => 'paciente',
            'con_consulta' => true,
            'con_numero' => true,
            'cuelga_de' => ['seccion' => 'neonatales', 'columna' => 'antecedentes_neonatales_id'],
        ],
        'examen_complementario' => [
            'tabla' => 'examenes_complementarios_fotos',
            'carpeta' => 'examenes_complementarios',
            'lectura' => 'consulta',
            'con_consulta' => true,
            'con_numero' => true,
            'cuelga_de_registro' => ['tabla' => 'examenes_complementarios', 'columna' => 'examen_complementario_id'],
        ],
        'internacion' => [
            'tabla' => 'internaciones_fotos',
            'carpeta' => 'internaciones',
            'lectura' => 'paciente',
            'con_consulta' => true,
            'con_numero' => true,
            'cuelga_de_registro' => ['tabla' => 'internaciones', 'columna' => 'internaciones_id'],
        ],
        'familigrama' => [
            'tabla' => 'familigramas',
            'carpeta' => 'familigrama',
            'lectura' => 'paciente',
            'con_consulta' => false,
            'con_numero' => false,
        ],
    ];

    /** Todos los adjuntos que la app puede mostrar en esta consulta. */
    public function leer($consultaId, $pacienteId)
    {
        $out = [];
        foreach (self::TIPOS as $tipo => $def) {
            $q = DB::table($def['tabla'])->where('paciente_id', $pacienteId)->whereIn('activo', [1, 2]);
            if ($def['lectura'] === 'consulta' && $def['con_consulta']) {
                $q->where('consulta_id', $consultaId);
            }
            $q->orderBy($def['con_numero'] ? 'numero' : 'id');
            foreach ($q->get() as $fila) {
                $out[] = $this->formatear($tipo, $def, $fila);
            }
        }
        return $out;
    }

    /**
     * Guarda un adjunto que manda la app y devuelve el id con el que quedó, para que lo anote y no
     * lo vuelva a subir. Un archivo por pedido: si se corta la señal a la mitad se reintenta ese y
     * no los diez de la consulta.
     *
     * @param  UploadedFile $archivo
     * @param  int          $padreId  fila de la lista de la que cuelga, si el tipo lo pide
     * @return array  ['id' => ..., 'url' => ...] o ['error' => codigo]
     */
    public function guardar($consultaId, $pacienteId, $tipo, UploadedFile $archivo, $padreId = 0)
    {
        if (!isset(self::TIPOS[$tipo])) {
            return ['error' => 'tipo_desconocido'];
        }
        $def = self::TIPOS[$tipo];

        $mime = strtolower((string) $archivo->getMimeType());
        if (!isset(self::MIMES[$mime])) {
            return ['error' => 'tipo_de_archivo'];
        }

        $padre = $this->resolverPadre($def, $consultaId, $pacienteId, $padreId);
        if ($padre === false) {
            return ['error' => 'padre_no_encontrado'];
        }

        $relativa = $this->escribirEnDisco($def, $archivo, $mime);
        if ($relativa === null) {
            return ['error' => 'carpeta_no_escribible'];
        }

        $ahora = Carbon::now();
        $valores = ColumnasLegacy::vacias($def['tabla'], [], $this->columnasPropias($def));
        $valores['paciente_id'] = (int) $pacienteId;
        $valores['foto'] = $relativa;
        $valores['activo'] = 1;
        $valores['created_at'] = $ahora;
        $valores['updated_at'] = $ahora;
        if ($def['con_consulta']) {
            $valores['consulta_id'] = (int) $consultaId;
        }
        if ($def['con_numero']) {
            $valores['numero'] = $this->proximoNumero($def, $consultaId, $pacienteId, $padre);
        }
        if ($padre !== null) {
            $valores[$this->columnaPadre($def)] = (int) $padre;
        }

        $id = DB::table($def['tabla'])->insertGetId($valores);
        return ['id' => (string) $id, 'url' => $this->url($relativa)];
    }

    /**
     * Baja lógica, como el resto de la historia clínica. El archivo no se borra del disco: el
     * médico da de baja una foto de la galería, no un documento del servidor.
     */
    public function borrar($pacienteId, $tipo, $id)
    {
        if (!isset(self::TIPOS[$tipo])) {
            return false;
        }
        $def = self::TIPOS[$tipo];
        $afectadas = DB::table($def['tabla'])
            ->where('id', (int) $id)
            ->where('paciente_id', (int) $pacienteId)
            ->update(['activo' => 0, 'updated_at' => Carbon::now()]);
        return $afectadas > 0;
    }

    /** La fila, ya acotada al paciente: es la comprobación de propiedad a nivel dato. */
    public function fila($pacienteId, $tipo, $id)
    {
        if (!isset(self::TIPOS[$tipo])) {
            return null;
        }
        $def = self::TIPOS[$tipo];
        $fila = DB::table($def['tabla'])
            ->where('id', (int) $id)
            ->where('paciente_id', (int) $pacienteId)
            ->whereIn('activo', [1, 2])
            ->first();
        return $fila === null ? null : $this->formatear($tipo, $def, $fila);
    }

    /** Dónde quedó el archivo en el disco, para servirlo por la API en vez de por URL pública. */
    public function rutaEnDisco($relativa)
    {
        return $this->baseDeImagenes() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativa);
    }

    /**
     * Carpeta `img` que sirve el servidor web, en el disco.
     *
     * No alcanza con `public_path()`. En un hosting donde el contenido del `public` de Laravel se
     * copia dentro del `public_html` del dominio, `public_path()` apunta a una carpeta que el
     * servidor no publica, o que directamente no existe. La web no se entera porque guarda con rutas
     * **relativas** (`Image::save('img/…')`), que caen en el directorio del script, o sea el que se
     * publica. Acá se resuelve igual, y se puede forzar con `SALUD360_IMG_PATH` si hiciera falta.
     */
    public function baseDeImagenes()
    {
        $forzada = trim((string) config('salud360.img_path'));
        if ($forzada !== '') {
            return rtrim($forzada, '/\\');
        }
        // El directorio del `index.php` que atendió el pedido: es el que el servidor publica.
        $script = (string) filter_input(INPUT_SERVER, 'SCRIPT_FILENAME');
        if ($script !== '' && is_file($script)) {
            $candidato = dirname($script) . DIRECTORY_SEPARATOR . 'img';
            if (is_dir($candidato)) {
                return $candidato;
            }
        }
        return public_path('img');
    }

    /** La URL absoluta con la que lo sirve la web de pediatría. */
    public function url($relativa)
    {
        return asset('img/' . $relativa);
    }

    // ---- interno ----

    private function formatear($tipo, array $def, $fila)
    {
        $relativa = (string) $fila->foto;
        $item = [
            'id' => (string) $fila->id,
            'tipo' => $tipo,
            'archivo' => $relativa,
            'url' => $this->url($relativa),
            'nombre' => basename($relativa),
        ];
        if ($def['con_numero']) {
            $item['numero'] = (int) $fila->numero;
        }
        if ($def['con_consulta']) {
            $item['consulta_id'] = (string) $fila->consulta_id;
        }
        if (isset($def['cuelga_de_registro'])) {
            $item['padre_id'] = (string) $fila->{$def['cuelga_de_registro']['columna']};
        }
        return $item;
    }

    /**
     * El id de la fila de la que cuelga la foto: null si el tipo no cuelga de nada, false si tenía
     * que colgar de algo que no aparece.
     */
    private function resolverPadre(array $def, $consultaId, $pacienteId, $padreId)
    {
        if (isset($def['cuelga_de'])) {
            $id = $this->secciones->asegurarFila($def['cuelga_de']['seccion'], $consultaId, $pacienteId);
            return $id === null ? false : $id;
        }
        if (isset($def['cuelga_de_registro'])) {
            if ((int) $padreId <= 0) {
                return false;
            }
            $existe = DB::table($def['cuelga_de_registro']['tabla'])
                ->where('id', (int) $padreId)
                ->where('paciente_id', (int) $pacienteId)
                ->exists();
            return $existe ? (int) $padreId : false;
        }
        return null;
    }

    private function columnaPadre(array $def)
    {
        if (isset($def['cuelga_de'])) {
            return $def['cuelga_de']['columna'];
        }
        return $def['cuelga_de_registro']['columna'];
    }

    /** Las columnas que llena esta clase, y que por eso no tiene que rellenar `ColumnasLegacy`. */
    private function columnasPropias(array $def)
    {
        $propias = ['foto'];
        if ($def['con_numero']) {
            $propias[] = 'numero';
        }
        if (isset($def['cuelga_de']) || isset($def['cuelga_de_registro'])) {
            $propias[] = $this->columnaPadre($def);
        }
        return $propias;
    }

    /** Última carpeta que se intentó escribir, para poder nombrarla en el error. */
    private $ultimoDestino = '';

    public function ultimoDestino()
    {
        return $this->ultimoDestino;
    }

    /**
     * Deja el archivo en la carpeta [CARPETA] dentro del `img` que publica el servidor, y devuelve lo
     * que va en la columna. Ver [baseDeImagenes] sobre por qué no alcanza con `public_path()`.
     *
     * **Una sola carpeta para todos, sin subcarpetas.** La web usa una por médico y otra por sección,
     * y las crea a mano: su código no crea ninguna, asume que están. En este hosting el PHP no tiene
     * permiso para crear directorios, así que una carpeta única, creada una vez, es lo que funciona.
     * La sección va adelante del nombre para que el contenido siga siendo legible, y el nombre se
     * sortea, así dos madres que mandan `ecografia.jpg` no se pisan.
     *
     * Las imágenes se achican si son más grandes que el máximo; el resto se guarda tal cual. No se
     * usa `store()` de Laravel, que es lo que hace la web: escribe además una copia en
     * `storage/app` que nadie lee nunca.
     */
    private function escribirEnDisco(array $def, UploadedFile $archivo, $mime)
    {
        $destino = $this->baseDeImagenes() . DIRECTORY_SEPARATOR . self::CARPETA;
        $this->ultimoDestino = $destino;
        if (!is_dir($destino)) {
            // Se intenta igual, por si el hosting lo permite. La arroba evita que el aviso de PHP
            // se cuele en la respuesta; si no se pudo, lo dice el código de error.
            @mkdir($destino, 0755, true);
        }
        if (!is_dir($destino) || !is_writable($destino)) {
            return null;
        }

        $nombre = $def['carpeta'] . '-' . Str::random(32) . '.' . self::MIMES[$mime];
        $completa = $destino . DIRECTORY_SEPARATOR . $nombre;

        if ($mime === 'application/pdf') {
            $archivo->move($destino, $nombre);
        } else {
            $imagen = Image::make($archivo->getRealPath());
            $imagen->resize(self::ANCHO_MAX, self::ALTO_MAX, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $imagen->save($completa);
        }
        if (!is_file($completa)) {
            return null;
        }
        return self::CARPETA . '/' . $nombre;
    }

    /**
     * Posición de la foto: la que mueven "anterior" y "siguiente" en la web, que busca por
     * `numero` entre 1 y la cantidad de fotos del grupo. El grupo es el mismo que cuenta la web:
     * las fotos de esa consulta, las de ese examen o internación, o las del paciente
     * (antecedentes neonatales), y como ella, cantidad + 1. Numerar por paciente deja huecos, y la web
     * no encuentra la foto.
     */
    private function proximoNumero(array $def, $consultaId, $pacienteId, $padre)
    {
        $q = DB::table($def['tabla'])->where('paciente_id', (int) $pacienteId)->whereIn('activo', [1, 2]);
        if (isset($def['cuelga_de_registro'])) {
            $q->where($def['cuelga_de_registro']['columna'], (int) $padre);
        } elseif ($def['lectura'] === 'consulta' && $def['con_consulta']) {
            $q->where('consulta_id', (int) $consultaId);
        }
        return $q->count() + 1;
    }
}
