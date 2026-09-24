<?php

namespace App\Services\Salud360;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Relleno para insertar en las tablas viejas de pediatría.
 *
 * Casi todas sus columnas son NOT NULL y sin valor por defecto, así que un alta tiene que llenarlas
 * todas y no solo las que mandó la app. Se sacan de la base y no de una lista escrita a mano porque
 * estas tablas tienen columnas que la app no conoce —detalles que solo carga la web, restos de
 * formularios viejos— y una columna nueva del lado de pediatría no tiene por qué romper el alta.
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class ColumnasLegacy
{
    /** @var array cache por tabla, para no consultar el catálogo en cada alta */
    private static $cache = [];

    /**
     * Valor neutro para cada columna obligatoria de la tabla.
     *
     * @param string $tabla
     * @param array  $opciones columna => tipo de opción ('opcion' numera 1/2 con el 0 vacío; el resto
     *                         1/0 con el 2 vacío). Una columna de opciones nunca puede nacer en la
     *                         respuesta "no": nace "sin cargar".
     * @param array  $omitir   columnas que pone el que llama (id, claves, activo, fechas de alta)
     */
    public static function vacias($tabla, array $opciones = [], array $omitir = [])
    {
        $omitir = array_merge(['id', 'consulta_id', 'paciente_id', 'activo', 'created_at', 'updated_at'], $omitir);
        $out = [];
        foreach (self::columnas($tabla) as $c) {
            if (in_array($c['nombre'], $omitir, true) || $c['nulo'] || $c['por_defecto'] !== null) {
                continue;
            }
            if (isset($opciones[$c['nombre']])) {
                $out[$c['nombre']] = $opciones[$c['nombre']] === 'opcion' ? 0 : SeccionesService::SIN_CARGAR;
            } elseif (in_array($c['tipo'], ['date', 'datetime', 'timestamp'], true)) {
                $out[$c['nombre']] = Carbon::now()->toDateString();
            } elseif (in_array($c['tipo'], ['int', 'bigint', 'smallint', 'tinyint', 'decimal', 'float', 'double'], true)) {
                $out[$c['nombre']] = 0;
            } else {
                $out[$c['nombre']] = '';
            }
        }
        return $out;
    }

    private static function columnas($tabla)
    {
        if (isset(self::$cache[$tabla])) {
            return self::$cache[$tabla];
        }
        $filas = DB::select(
            'select column_name, data_type, is_nullable, column_default
               from information_schema.columns
              where table_schema = database() and table_name = ?',
            [$tabla]
        );
        $out = [];
        foreach ($filas as $f) {
            $out[] = [
                'nombre' => self::dato($f, 'column_name'),
                'tipo' => strtolower(self::dato($f, 'data_type')),
                'nulo' => strtoupper(self::dato($f, 'is_nullable')) === 'YES',
                'por_defecto' => self::dato($f, 'column_default'),
            ];
        }
        self::$cache[$tabla] = $out;
        return $out;
    }

    /**
     * Campo de una fila de `information_schema`. Según la versión de MySQL los nombres vienen en
     * minúscula o en mayúscula, y el valor puede ser nulo (`isset` no alcanza para distinguir
     * "no existe" de "es nulo").
     */
    private static function dato($fila, $nombre)
    {
        if (property_exists($fila, $nombre)) {
            return $fila->$nombre;
        }
        $mayuscula = strtoupper($nombre);
        return property_exists($fila, $mayuscula) ? $fila->$mayuscula : null;
    }
}
