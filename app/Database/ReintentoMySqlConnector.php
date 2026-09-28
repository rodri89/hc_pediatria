<?php

namespace App\Database;

use Illuminate\Database\Connectors\MySqlConnector;
use PDOException;

/**
 * Conexión a MySQL que reintenta cuando el hosting la rechaza de a ratos.
 *
 * En el hosting compartido, la pantalla de la consulta dispara muchos pedidos AJAX a la vez y cada
 * uno abre su propia conexión. Cuando se pasa del límite del plan, MySQL rechaza algunas con
 * "[2002] Operation not permitted" o "Too many connections", y ese pedido falla entero aunque un
 * instante después la base atienda sin problema.
 *
 * El reintento es seguro porque el error ocurre al abrir la conexión: todavía no se ejecutó ninguna
 * consulta, así que no hay nada que se pueda duplicar.
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class ReintentoMySqlConnector extends MySqlConnector
{
    /** Intentos en total, contando el primero. */
    const INTENTOS = 4;

    /** Espera antes del primer reintento, en milisegundos. Crece con cada intento. */
    const ESPERA_MS = 150;

    public function createConnection($dsn, array $config, array $options)
    {
        $intento = 1;
        while (true) {
            try {
                return parent::createConnection($dsn, $config, $options);
            } catch (PDOException $e) {
                if ($intento >= self::INTENTOS || !$this->esRechazoPasajero($e)) {
                    throw $e;
                }
                usleep(self::ESPERA_MS * $intento * 1000);
                $intento++;
            }
        }
    }

    /** Errores de conexión que se van solos al rato. Un usuario o contraseña mal no se reintenta. */
    private function esRechazoPasajero(PDOException $e)
    {
        $mensaje = $e->getMessage();
        foreach (['[2002]', 'Operation not permitted', 'Too many connections', 'max_user_connections', '[1040]', '[1203]'] as $senal) {
            if (stripos($mensaje, $senal) !== false) {
                return true;
            }
        }
        return false;
    }
}
