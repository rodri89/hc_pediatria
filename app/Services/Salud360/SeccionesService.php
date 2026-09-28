<?php

namespace App\Services\Salud360;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Traduce el contenido de una consulta entre el modelo genérico de la app y las tablas de pediatría.
 *
 * La app guarda todo como `seccion -> campo -> valor`; pediatría tiene una tabla por sección. Para las
 * secciones de texto todas comparten la misma forma (`consulta_id, paciente_id, descripcion, activo`),
 * así que alcanza con un mapa y una única regla de escritura, la misma que usa la web en
 * `MedicoController::guardarMotivoConsulta`: buscar por consulta y paciente con `activo IN (1,2)`,
 * actualizar si existe e insertar si no, dejando `activo = 1`.
 */
class SeccionesService
{
    /**
     * Las columnas de opciones de pediatría son de tres estados: 1 y 0 son las dos respuestas y 2 es
     * "el médico no contestó", que es con lo que nacen. La web solo pregunta por el 1.
     */
    const SIN_CARGAR = 2;

    /** Sección de la app con el desarrollo madurativo, que tiene un campo por hito y no columnas fijas. */
    const SECCION_DESARROLLO = 'desarrollo';

    /** Prefijo del campo de un hito en la app: `dm_<desarrollo_madurativos.id>`. */
    const PREFIJO_HITO = 'dm_';

    /** @var int|null|false id del hito "Observacion"; false mientras no se buscó. */
    private static $idObservacion = false;

    /** Id de sección en la app => tabla en pediatría. Las de texto guardan un único campo `texto`. */
    const TABLAS_TEXTO = [
        'motivo_consulta' => 'motivo_consultas',
        'observaciones' => 'observaciones',
        'nota' => 'notas',
        'conductas' => 'conductas',
        'datos_subjetivos' => 'datos_subjetivos',
        'datos_objetivos' => 'datos_objetivos',
        'escolaridad' => 'escolaridads',
        'pantallas' => 'pantallas',
        'habitos' => 'habitos',
        'menarca' => 'menarcas',
        'somnia' => 'somnias',
        'diuresis_catarsis' => 'catarses',
        'actividades' => 'actividades_extra_escolares',
        'vacunas' => 'vacunas_dos',
        'lactancia_previa' => 'lactancia_embarazo_previos',
    ];

    /**
     * Secciones con tabla y columnas propias (fase 2). Cada una declara:
     *
     *   tabla   la de pediatría;
     *   por     'consulta' si la fila es de esa consulta, 'paciente' si es una sola por paciente y
     *           se arrastra entre consultas (antecedentes);
     *   campos  campo de la app => [tipo, columna, columna del detalle].
     *
     * Tipos:
     *   texto   se guarda tal cual;
     *   numero  columna entera; el vacío es 0, que es con lo que nace, y se lee de vuelta vacío;
     *   detalle la app manda "<tilde>|<detalle>" pero pediatría solo tiene la columna de texto: se
     *           guarda el detalle y la tilde se descarta;
     *   check   la app manda "1|detalle" o "0|detalle" (casilla con detalle); pediatría guarda el
     *           1/0 en una columna y el detalle en otra;
     *   sino    la app manda "SI|detalle" o "NO|detalle"; pediatría guarda 1/0 y el detalle;
     *   opcion  igual que `sino` pero con la otra numeración: **la consulta prenatal guarda 1 y 2, y
     *           el 0 es "sin cargar"**, al revés que el resto de pediatría. No se unificó a propósito:
     *           son las columnas que ya lee la web.
     *
     * La tercera posición (columna del detalle) va solo si el campo de la app la tiene: si no, el
     * detalle que haya cargado el pediatra en la web no se toca.
     *
     * Agregar una sección es agregar una entrada acá: el resto ya está escrito.
     */
    const FORMULARIOS = [
        'alimentacion' => [
            'tabla' => 'alimentacions',
            'por' => 'consulta',
            'campos' => [
                'pecho' => ['check', 'pecho', 'pecho_detalle'],
                'leche_maternizada' => ['check', 'leche_maternizada', 'leche_maternizada_detalle'],
                'leche_vaca' => ['check', 'leche_vaca', 'leche_vaca_detalle'],
                'dieta_tipo' => ['texto', 'dieta_tipo'],
                'dieta_comidas' => ['texto', 'dieta_comidas'],
                'hierro' => ['check', 'hierro', 'hierro_dosis'],
                'vitamina' => ['check', 'vitamina', 'vitamina_dosis'],
            ],
            // `alimentacions` tiene además `catarsis` y `somnia`, de una versión vieja del formulario:
            // hoy están ocultas y la web las lee de `catarses` y `somnias`, que son secciones de texto
            // aparte. No se tocan.
        ],
        'neonatales' => [
            'tabla' => 'antecedentes_neonatales',
            'por' => 'paciente',
            'campos' => [
                'nota' => ['texto', 'nota'],
            ],
        ],
        'perinatales' => [
            'tabla' => 'antecedentes_perinatales',
            'por' => 'paciente',
            'campos' => [
                'embarazo' => ['texto', 'embarazo'],
                'controles' => ['numero', 'embarazo_controles'],
                'patologias' => ['sino', 'patologias', 'patologias_detalle'],
                // En la app la casilla se llama SBHB y en pediatría la columna SBHA: es el mismo dato.
                'hisop_sbhb' => ['sino', 'hisop_sbha'],
                'hisop_sbhb_detalle' => ['texto', 'hisop_sbha_detalle'],
                'serologia1' => ['sino', 'serologia1'],
                'serologia1_detalle' => ['texto', 'serologia1_detalle'],
                'serologia3' => ['sino', 'serologia3'],
                'serologia3_detalle' => ['texto', 'serologia3_detalle'],
                'parto' => ['texto', 'parto'],
                'parto_detalle' => ['texto', 'parto_detalle'],
                'eg' => ['texto', 'eg'],
                'peso' => ['texto', 'peso'],
                'talla' => ['texto', 'talla'],
                'pc' => ['texto', 'pc'],
                'apgar' => ['texto', 'apgar'],
                'caida_cordon' => ['numero', 'caida_cordon'],
                'meconio' => ['numero', 'meconio'],
                'gyf' => ['texto', 'gyf'],
                'fei' => ['sino', 'fei'],
                'fei_detalle' => ['texto', 'fei_anormal_detalle'],
                // `vdrl_detalle` y `chagas_detalle` existen en pediatría pero la app no tiene dónde
                // escribirlos: no se mapean, así el detalle que cargó el pediatra en la web se queda.
                'vdrl' => ['sino', 'vdrl'],
                'chagas' => ['sino', 'chagas'],
                'oea' => ['sino', 'oea'],
            ],
        ],
        // Las tres de la consulta prenatal. Ojo con la numeración: acá es 1/2 y el 0 es "sin cargar".
        'prenatal_familia' => [
            'tabla' => 'familias',
            'por' => 'consulta',
            'campos' => [
                'mama' => ['texto', 'mama'],
                'mama_edad' => ['texto', 'mama_edad'],
                'mama_ocupacion' => ['texto', 'mama_ocupacion'],
                'papa' => ['texto', 'papa'],
                'papa_edad' => ['texto', 'papa_edad'],
                'papa_ocupacion' => ['texto', 'papa_ocupacion'],
                'bebe' => ['texto', 'bebe'],
                'eg' => ['texto', 'eg'],
                'fpp' => ['texto', 'fpp'],
                'hermanos' => ['opcion', 'hermanos'],
            ],
        ],
        'prenatal_embarazo' => [
            'tabla' => 'embarazo_actuals',
            'por' => 'consulta',
            'campos' => [
                'obstetra' => ['texto', 'obstetra'],
                'eg' => ['texto', 'eg'],
                'controles' => ['texto', 'n_controles'],
                'serologia1' => ['opcion', 'serologia_1'],
                'serologia1_detalle' => ['texto', 'serologia_1_detalle'],
                'serologia2' => ['opcion', 'serologia_2'],
                'serologia2_detalle' => ['texto', 'serologia_2_detalle'],
                'hisop_sbhb' => ['opcion', 'hisop_sbhb'],
                'hisop_detalle' => ['texto', 'hisop_sbhb_detalle'],
                'ptog' => ['opcion', 'ptog'],
                'ptog_detalle' => ['texto', 'ptog_detalle'],
                'vacunas' => ['texto', 'vacunas'],
                'parto' => ['opcion', 'parto'],
                'cesarea_detalle' => ['texto', 'cesarea_detalle'],
                'ecografia' => ['texto', 'ecografia'],
                'observaciones' => ['texto', 'observaciones'],
            ],
        ],
        // Antecedentes. En la app no son secciones sino casillas de la tabla `antecedente`; viajan como
        // si fueran una sección, con un campo por clave y el valor "<tilde>|<detalle>".
        'antecedentes_personales' => [
            'tabla' => 'antecedentes_personales',
            'por' => 'consulta',
            'campos' => [
                'enfermedad_actual' => ['detalle', 'enfermedad_actual'],
                'alergias' => ['check', 'alergias', 'alergia_detalle'],
                'qx' => ['check', 'qx', 'qx_detalle'],
                'traumatismos' => ['check', 'traumatismos', 'traumatismos_detalle'],
                'transfusiones' => ['check', 'transfusiones', 'transfusiones_detalle'],
                'otro' => ['check', 'otro', 'otro_detalle'],
            ],
            // En pediatría las internaciones son una lista aparte, así que la app no manda esta casilla.
            'opciones_sin_mapear' => ['internaciones'],
        ],
        'antecedentes_familiares' => [
            'tabla' => 'antecedentes_familiares',
            'por' => 'consulta',
            'campos' => [
                'hta' => ['check', 'hta', 'hta_detalle'],
                'dbt' => ['check', 'dbt', 'dbt_detalle'],
                'asma' => ['check', 'asma', 'asma_detalle'],
                'alergia' => ['check', 'alergia', 'alergia_detalle'],
                'enf_cv' => ['check', 'enf_cv', 'enf_cv_detalle'],
                'muerte_subita' => ['check', 'muerte_subita', 'muerte_subita_detalle'],
                'enf_celiaca' => ['check', 'enf_celiaca', 'enf_celiaca_detalle'],
                'enf_tiroideas' => ['check', 'enf_tiroideas', 'enf_tiroideas_detalle'],
                'enf_neurologicas' => ['check', 'enf_neurologicas', 'enf_neurologicas_detalle'],
                'convulsion_febril' => ['check', 'convulsion_febril', 'convulsion_febril_detalle'],
                'enf_psiquiatrica' => ['check', 'enf_psiquiatrica', 'enf_psiquiatrica_detalle'],
                'enf_oh' => ['check', 'enf_oh', 'enf_oh_detalle'],
                'tabaquismo' => ['check', 'tabaquismo', 'tabaquismo_detalle'],
                'otro' => ['check', 'otro', 'otro_detalle'],
            ],
        ],
        'prenatal_obstetricos' => [
            'tabla' => 'antecedentes_obstetricos',
            'por' => 'consulta',
            'campos' => [
                'g' => ['numero', 'g'],
                'p' => ['numero', 'p'],
                'a' => ['numero', 'a'],
                'detalle' => ['texto', 'descripcion'],
            ],
        ],
    ];

    /**
     * Las dos opciones de cada campo, tal como las manda la app: el texto que eligió el médico ("+",
     * "Normal", "N"). La primera es la que pediatría guarda como 1.
     *
     * La clave puede ser `<seccion>.<campo>` o solo `<campo>`; se busca primero la larga. Hace falta
     * porque `parto` es una cosa en los antecedentes perinatales y otra en la consulta prenatal.
     * Un campo que no esté acá usa SI/NO, que es lo que manda la app para las casillas comunes.
     */
    const OPCIONES = [
        'hisop_sbhb' => ['+', '-'],
        'serologia1' => ['+', '-'],
        'serologia2' => ['+', '-'],
        'serologia3' => ['+', '-'],
        'vdrl' => ['+', '-'],
        'chagas' => ['+', '-'],
        'fei' => ['Normal', 'Anormal'],
        'oea' => ['Presentes', 'Ausentes'],
        'prenatal_embarazo.ptog' => ['N', 'P'],
        'prenatal_embarazo.parto' => ['Parto', 'Cesárea'],
    ];

    /** Campo del examen físico en la app => columna en pediatría. */
    const CAMPOS_EXAMEN = [
        'peso' => 'peso',
        'peso_percentil' => 'peso_percentil',
        'talla' => 'talla',
        'talla_percentil' => 'talla_percentil',
        'imc' => 'imc',
        'imc_percentil' => 'imc_percentil',
        'perimetro_cefalico' => 'pc',
        'perimetro_cefalico_percentil' => 'pc_percentil',
        'tension_arterial' => 'ta',
        'ipd' => 'ipd',
        'nota' => 'nota',
    ];

    // ------------------------------------------------------------------
    // Secciones de texto
    // ------------------------------------------------------------------

    /** Todo lo que la app entiende de esta consulta: secciones de texto y formularios. */
    public function leer($consultaId, $pacienteId)
    {
        $out = $this->leerTextos($consultaId, $pacienteId) + $this->leerFormularios($consultaId, $pacienteId);
        $desarrollo = $this->leerDesarrollo($consultaId, $pacienteId);
        if (count($desarrollo) > 0) {
            $out[self::SECCION_DESARROLLO] = $desarrollo;
        }
        return $out;
    }

    /**
     * Guarda las secciones que manda la app, cada una en la tabla que le corresponde. Una sección
     * ausente no se toca. Devuelve los ids guardados y los desconocidos, para que la app no dé por
     * guardado algo que el servidor ignoró.
     */
    public function guardar($consultaId, $pacienteId, array $secciones)
    {
        $textos = [];
        $formularios = [];
        $guardadas = [];
        $desconocidas = [];
        foreach ($secciones as $seccion => $campos) {
            if (!is_array($campos)) {
                continue;
            }
            if (isset(self::TABLAS_TEXTO[$seccion])) {
                $textos[$seccion] = $campos;
            } elseif (isset(self::FORMULARIOS[$seccion])) {
                $formularios[$seccion] = $campos;
            } elseif ($seccion === self::SECCION_DESARROLLO) {
                $this->guardarDesarrollo($consultaId, $pacienteId, $campos);
                $guardadas[] = $seccion;
            } else {
                $desconocidas[] = $seccion;
            }
        }
        $r = $this->guardarTextos($consultaId, $pacienteId, $textos);
        $guardadas = array_merge(
            $guardadas,
            $r['guardadas'],
            $this->guardarFormularios($consultaId, $pacienteId, $formularios)
        );
        return ['guardadas' => $guardadas, 'desconocidas' => $desconocidas];
    }

    /** Todas las secciones de texto con contenido, en el formato de la app. */
    public function leerTextos($consultaId, $pacienteId)
    {
        $out = [];
        foreach (self::TABLAS_TEXTO as $seccion => $tabla) {
            $fila = $this->filaTexto($tabla, $consultaId, $pacienteId);
            if ($fila !== null && trim((string) $fila->descripcion) !== '') {
                $out[$seccion] = ['texto' => (string) $fila->descripcion];
            }
        }
        return $out;
    }

    /**
     * Guarda las secciones recibidas. Una sección ausente no se toca, para que la app pueda mandar
     * solo lo que cambió. Devuelve los ids guardados y los desconocidos, para que la app no dé por
     * guardado algo que el servidor ignoró.
     */
    public function guardarTextos($consultaId, $pacienteId, array $secciones)
    {
        $guardadas = [];
        $desconocidas = [];
        foreach ($secciones as $seccion => $campos) {
            if (!isset(self::TABLAS_TEXTO[$seccion])) {
                $desconocidas[] = $seccion;
                continue;
            }
            if (!is_array($campos) || !array_key_exists('texto', $campos)) {
                continue;
            }
            $this->guardarTexto(self::TABLAS_TEXTO[$seccion], $consultaId, $pacienteId, (string) $campos['texto']);
            $guardadas[] = $seccion;
        }
        return ['guardadas' => $guardadas, 'desconocidas' => $desconocidas];
    }

    private function guardarTexto($tabla, $consultaId, $pacienteId, $texto)
    {
        $ahora = Carbon::now();
        $fila = $this->filaTexto($tabla, $consultaId, $pacienteId);
        if ($fila !== null) {
            DB::table($tabla)->where('id', $fila->id)->update([
                'descripcion' => $texto,
                'activo' => 1,
                'updated_at' => $ahora,
            ]);
            return;
        }
        DB::table($tabla)->insert([
            'consulta_id' => (int) $consultaId,
            'paciente_id' => (int) $pacienteId,
            'descripcion' => $texto,
            'activo' => 1,
            'created_at' => $ahora,
            'updated_at' => $ahora,
        ]);
    }

    private function filaTexto($tabla, $consultaId, $pacienteId)
    {
        return DB::table($tabla)
            ->where('consulta_id', $consultaId)
            ->where('paciente_id', $pacienteId)
            ->whereIn('activo', [1, 2])
            ->first();
    }

    // ------------------------------------------------------------------
    // Secciones con tabla propia (fase 2)
    // ------------------------------------------------------------------

    /** Las secciones de [FORMULARIOS] que tengan algo cargado, en el formato de la app. */
    public function leerFormularios($consultaId, $pacienteId)
    {
        $out = [];
        foreach (self::FORMULARIOS as $seccion => $def) {
            $fila = $this->filaFormulario($def, $consultaId, $pacienteId);
            if ($fila === null) {
                continue;
            }
            $campos = [];
            foreach ($def['campos'] as $campo => $mapa) {
                $valor = $this->valorDeColumna($seccion, $campo, $mapa, $fila);
                if ($valor !== '') {
                    $campos[$campo] = $valor;
                }
            }
            if (count($campos) > 0) {
                $out[$seccion] = $campos;
            }
        }
        return $out;
    }

    /**
     * Guarda las secciones con tabla propia. Devuelve los ids guardados.
     *
     * Solo se escriben los campos que vinieron: un campo ausente conserva lo que la web tenía, que es
     * lo mismo que hace el guardado de textos y lo que evita pisar con vacíos lo que cargó el pediatra.
     */
    public function guardarFormularios($consultaId, $pacienteId, array $secciones)
    {
        $guardadas = [];
        foreach ($secciones as $seccion => $campos) {
            if (!isset(self::FORMULARIOS[$seccion]) || !is_array($campos)) {
                continue;
            }
            $def = self::FORMULARIOS[$seccion];
            $valores = [];
            foreach ($def['campos'] as $campo => $mapa) {
                if (array_key_exists($campo, $campos)) {
                    $valores = array_merge($valores, $this->columnasDeValor($seccion, $campo, $mapa, (string) $campos[$campo]));
                }
            }
            if (count($valores) === 0) {
                continue;
            }
            $this->escribirFormulario($def, $consultaId, $pacienteId, $valores);
            $guardadas[] = $seccion;
        }
        return $guardadas;
    }

    /**
     * La fila que la web lee para esta sección.
     *
     * Las de paciente (antecedentes) van por paciente y nada más, igual que
     * `existeAntecedentePerinatal` y que las pantallas que las muestran: es una sola ficha que se
     * arrastra entre consultas. Se escribe justo esa fila —no una nueva por consulta— para que el
     * pediatra vea en la web lo que cargó el médico en la app.
     */
    /**
     * Id de la fila de una sección, creándola vacía si no existe. Lo usan las internaciones, que en
     * pediatría cuelgan por clave foránea de la ficha de antecedentes personales de la consulta.
     */
    public function asegurarFila($seccion, $consultaId, $pacienteId)
    {
        if (!isset(self::FORMULARIOS[$seccion])) {
            return null;
        }
        $def = self::FORMULARIOS[$seccion];
        $fila = $this->filaFormulario($def, $consultaId, $pacienteId);
        if ($fila !== null) {
            return $fila->id;
        }
        $this->escribirFormulario($def, $consultaId, $pacienteId, []);
        $fila = $this->filaFormulario($def, $consultaId, $pacienteId);
        return $fila === null ? null : $fila->id;
    }

    private function filaFormulario(array $def, $consultaId, $pacienteId)
    {
        $q = DB::table($def['tabla'])->where('paciente_id', $pacienteId);
        if ($def['por'] === 'consulta') {
            $q->where('consulta_id', $consultaId);
        }
        return $q->whereIn('activo', [1, 2])->orderBy('id')->first();
    }

    private function escribirFormulario(array $def, $consultaId, $pacienteId, array $valores)
    {
        $ahora = Carbon::now();
        $fila = $this->filaFormulario($def, $consultaId, $pacienteId);
        $valores['activo'] = 1;
        $valores['updated_at'] = $ahora;

        if ($fila !== null) {
            DB::table($def['tabla'])->where('id', $fila->id)->update($valores);
            return;
        }
        // Estas tablas son viejas: casi todas las columnas son NOT NULL y sin valor por defecto, así
        // que en un alta hay que llenarlas todas, no solo las que mandó la app.
        $valores = $valores + $this->columnasVacias($def);
        $valores['consulta_id'] = (int) $consultaId;
        $valores['paciente_id'] = (int) $pacienteId;
        $valores['created_at'] = $ahora;
        DB::table($def['tabla'])->insert($valores);
    }

    /**
     * Valor de un campo de la app leído de la fila de pediatría, o '' si el médico no lo cargó.
     *
     * Las columnas de opciones son de tres estados: 1 y 0 son las dos opciones y **2 es "sin cargar"**,
     * que es con lo que nacen. Devolver 0 en vez de vacío sería inventar una respuesta ("no", "-",
     * "anormal") que el médico nunca dio.
     */
    private function valorDeColumna($seccion, $campo, array $mapa, $fila)
    {
        $tipo = $mapa[0];
        $columna = $mapa[1];
        $detalle = isset($mapa[2]) ? (string) $fila->{$mapa[2]} : '';

        if ($tipo === 'texto') {
            return (string) $fila->$columna;
        }
        if ($tipo === 'numero') {
            $n = (int) $fila->$columna;
            return $n === 0 ? '' : (string) $n;
        }
        if ($tipo === 'detalle') {
            $texto = (string) $fila->$columna;
            return $texto === '' ? '' : '1|' . $texto;
        }
        $bandera = (int) $fila->$columna;
        $primera = 1;
        $segunda = $tipo === 'opcion' ? 2 : 0;
        if ($bandera !== $primera && $bandera !== $segunda) {
            return '';
        }
        $opciones = $this->opciones($seccion, $campo, $tipo);
        $elegida = $bandera === $primera ? $opciones[0] : $opciones[1];
        return isset($mapa[2]) ? $elegida . '|' . $detalle : $elegida;
    }

    /** Lo inverso: el valor que manda la app repartido en las columnas de pediatría. */
    private function columnasDeValor($seccion, $campo, array $mapa, $valor)
    {
        $tipo = $mapa[0];
        if ($tipo === 'texto') {
            return [$mapa[1] => $valor];
        }
        if ($tipo === 'numero') {
            return [$mapa[1] => (int) $valor];
        }
        if ($tipo === 'detalle') {
            $partes = explode('|', $valor, 2);
            return [$mapa[1] => isset($partes[1]) ? $partes[1] : ''];
        }
        $partes = explode('|', $valor, 2);
        $elegida = $partes[0];
        $opciones = $this->opciones($seccion, $campo, $tipo);

        if ($elegida === (string) $opciones[0]) {
            $bandera = 1;
        } elseif ($elegida === (string) $opciones[1]) {
            $bandera = $tipo === 'opcion' ? 2 : 0;
        } else {
            $bandera = $tipo === 'opcion' ? 0 : self::SIN_CARGAR;
        }
        $out = [$mapa[1] => $bandera];
        // El detalle solo se guarda cuando hay algo que guardar: así una casilla sin detalle no le
        // borra al pediatra el que había escrito en la web.
        if (isset($mapa[2]) && isset($partes[1]) && $partes[1] !== '') {
            $out[$mapa[2]] = $partes[1];
        }
        return $out;
    }

    /** Las dos opciones de un campo, buscando primero la clave larga `<seccion>.<campo>`. */
    private function opciones($seccion, $campo, $tipo)
    {
        if (isset(self::OPCIONES[$seccion . '.' . $campo])) {
            return self::OPCIONES[$seccion . '.' . $campo];
        }
        if (isset(self::OPCIONES[$campo])) {
            return self::OPCIONES[$campo];
        }
        return $tipo === 'check' ? ['1', '0'] : ['SI', 'NO'];
    }

    /** Relleno para el alta: todas las columnas obligatorias de la tabla, no solo las mapeadas. */
    private function columnasVacias(array $def)
    {
        $opciones = [];
        foreach ($def['campos'] as $campo => $mapa) {
            if ($mapa[0] === 'check' || $mapa[0] === 'sino' || $mapa[0] === 'opcion') {
                $opciones[$mapa[1]] = $mapa[0];
            }
        }
        // Columnas de opciones que esta sección no mapea y que igual no pueden nacer en 0 ("no").
        if (isset($def['opciones_sin_mapear'])) {
            foreach ($def['opciones_sin_mapear'] as $columna) {
                $opciones[$columna] = 'sino';
            }
        }
        return ColumnasLegacy::vacias($def['tabla'], $opciones);
    }

    // ------------------------------------------------------------------
    // Desarrollo madurativo
    // ------------------------------------------------------------------

    /**
     * El desarrollo madurativo no es un formulario de columnas fijas: es una fila por hito en
     * `desarrollo_madurativo_pacientes`. La app lo guarda en la sección `desarrollo` con un campo
     * `dm_<id>` por hito, donde `<id>` es el mismo `desarrollo_madurativos.id` que usa la web, más un
     * campo `observacion`.
     *
     * La observación viaja como un hito más, el único de tipo "Observacion", con `checked = 2` y el
     * texto en su columna. Así lo hace `guardarDesarrolloMadurativoObservacion` de la web.
     */
    public function leerDesarrollo($consultaId, $pacienteId)
    {
        $filas = DB::table('desarrollo_madurativo_pacientes')
            ->where('consulta_id', $consultaId)
            ->where('paciente_id', $pacienteId)
            ->where('activo', 1)
            ->get();
        $idObservacion = $this->idHitoObservacion();
        $out = [];
        foreach ($filas as $f) {
            $hito = (int) $f->desarrollo_madurativo_id;
            if ($idObservacion !== null && $hito === $idObservacion) {
                if (trim((string) $f->observacion) !== '') {
                    $out['observacion'] = (string) $f->observacion;
                }
                continue;
            }
            $checked = (int) $f->checked;
            if ($checked === 0 || $checked === 1) {
                $out[self::PREFIJO_HITO . $hito] = (string) $checked;
            }
        }
        return $out;
    }

    public function guardarDesarrollo($consultaId, $pacienteId, array $campos)
    {
        $idObservacion = $this->idHitoObservacion();
        foreach ($campos as $campo => $valor) {
            if ($campo === 'observacion') {
                if ($idObservacion !== null) {
                    $this->escribirHito($consultaId, $pacienteId, $idObservacion, self::SIN_CARGAR, (string) $valor);
                }
                continue;
            }
            if (strpos($campo, self::PREFIJO_HITO) !== 0) {
                continue;
            }
            $hito = (int) substr($campo, strlen(self::PREFIJO_HITO));
            if ($hito <= 0) {
                continue;
            }
            $this->escribirHito($consultaId, $pacienteId, $hito, ((string) $valor === '1') ? 1 : 0, null);
        }
    }

    private function escribirHito($consultaId, $pacienteId, $hitoId, $checked, $observacion)
    {
        $ahora = Carbon::now();
        $fila = DB::table('desarrollo_madurativo_pacientes')
            ->where('consulta_id', $consultaId)
            ->where('paciente_id', $pacienteId)
            ->where('desarrollo_madurativo_id', $hitoId)
            ->where('activo', 1)
            ->first();

        $valores = ['checked' => $checked, 'updated_at' => $ahora];
        if ($observacion !== null) {
            $valores['observacion'] = $observacion;
        }
        if ($fila !== null) {
            DB::table('desarrollo_madurativo_pacientes')->where('id', $fila->id)->update($valores);
            return;
        }
        DB::table('desarrollo_madurativo_pacientes')->insert($valores + [
            'consulta_id' => (int) $consultaId,
            'paciente_id' => (int) $pacienteId,
            'desarrollo_madurativo_id' => (int) $hitoId,
            'observacion' => $observacion === null ? '' : $observacion,
            'activo' => 1,
            'created_at' => $ahora,
        ]);
    }

    /** Id del hito especial donde la web guarda la observación, o null si el catálogo no lo tiene. */
    private function idHitoObservacion()
    {
        if (self::$idObservacion === false) {
            $fila = DB::table('desarrollo_madurativos')->where('tipo', 'Observacion')->where('activo', 1)->first();
            self::$idObservacion = $fila === null ? null : (int) $fila->id;
        }
        return self::$idObservacion;
    }

    // ------------------------------------------------------------------
    // Examen físico
    // ------------------------------------------------------------------

    /** Examen físico de la consulta en el formato de la app, o null si no hay. */
    public function leerExamen($consultaId, $pacienteId)
    {
        $fila = DB::table('examen_fisicos')
            ->where('consulta_id', $consultaId)
            ->where('paciente_id', $pacienteId)
            ->whereIn('activo', [1, 2])
            ->first();
        if ($fila === null) {
            return null;
        }
        $out = [];
        foreach (self::CAMPOS_EXAMEN as $campo => $columna) {
            $out[$campo] = (string) $fila->$columna;
        }
        return $out;
    }

    /**
     * Guarda el examen físico. Solo viajan los campos que pediatría muestra; frecuencia cardíaca,
     * temperatura, saturación y circunferencia abdominal no existen acá y se ignoran sin pérdida.
     */
    public function guardarExamen($consultaId, $pacienteId, array $datos)
    {
        $ahora = Carbon::now();
        $fila = DB::table('examen_fisicos')
            ->where('consulta_id', $consultaId)
            ->where('paciente_id', $pacienteId)
            ->whereIn('activo', [1, 2])
            ->first();

        $valores = [];
        foreach (self::CAMPOS_EXAMEN as $campo => $columna) {
            if (array_key_exists($campo, $datos)) {
                $valores[$columna] = (string) $datos[$campo];
            } elseif ($fila === null) {
                // Todas las columnas son NOT NULL y sin valor por defecto: en un alta hay que llenarlas.
                $valores[$columna] = '';
            }
        }
        if (count($valores) === 0) {
            return;
        }
        $valores['activo'] = 1;
        $valores['updated_at'] = $ahora;

        if ($fila !== null) {
            DB::table('examen_fisicos')->where('id', $fila->id)->update($valores);
            return;
        }
        $valores['consulta_id'] = (int) $consultaId;
        $valores['paciente_id'] = (int) $pacienteId;
        $valores['created_at'] = $ahora;
        DB::table('examen_fisicos')->insert($valores);
    }
}
