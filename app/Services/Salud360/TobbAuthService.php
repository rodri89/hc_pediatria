<?php

namespace App\Services\Salud360;

use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Identidad de la API de Salud 360 en pediatría.
 *
 * turnosonlinebb es el único proveedor de identidad: esta API **no tiene login ni emite tokens**.
 * Recibe el token que la app obtuvo en turnosonlinebb, lo valida contra su `GET auth/perfil` y
 * ubica al médico local por `users.medico_id_tobb = perfil.medico.id`.
 *
 * Son dos interruptores independientes y por eso cada fallo tiene su propio código:
 *   1. el administrador habilita "pediatria" al médico en turnosonlinebb (`salud360_medico_hc`);
 *   2. el usuario de pediatría tiene cargado el número de médico de turnos (`users.medico_id_tobb`).
 *
 * Compatible con PHP 7.1 / Laravel 5.8.
 */
class TobbAuthService
{
    const TABLA_CACHE = 'salud360_token_cache';
    const TABLA_PENDIENTES = 'salud360_vinculos_pendientes';

    /** Motivos de rechazo; el middleware los traduce a HTTP. */
    const RECHAZO_TOKEN = 'token';
    const RECHAZO_SIN_PERMISO = 'sin_permiso';
    const RECHAZO_HC_NO_HABILITADA = 'hc_no_habilitada';
    const RECHAZO_MEDICO_NO_VINCULADO = 'medico_no_vinculado';
    const RECHAZO_TOBB_CAIDO = 'tobb_caido';

    /** Motivo del último rechazo, para que el middleware arme la respuesta. */
    private $motivo = null;

    /** Datos extra del último rechazo (por ejemplo el número de médico que falta vincular). */
    private $extra = [];

    /** Perfil de turnosonlinebb de la última validación exitosa. */
    private $perfil = null;

    public function motivo()
    {
        return $this->motivo;
    }

    public function extra()
    {
        return $this->extra;
    }

    public function perfil()
    {
        return $this->perfil;
    }

    /** Token del pedido: `Authorization: Bearer <token>` o `X-Salud360-Token` si el hosting descarta el primero. */
    public function tokenDelPedido($request)
    {
        $cabecera = (string) $request->header('Authorization', '');
        if (stripos($cabecera, 'Bearer ') === 0) {
            $token = trim(substr($cabecera, 7));
            if ($token !== '') {
                return $token;
            }
        }
        $propio = trim((string) $request->header('X-Salud360-Token', ''));
        return $propio !== '' ? $propio : null;
    }

    /**
     * Usuario local de pediatría que corresponde al token de turnosonlinebb, o null.
     * Cuando devuelve null, [motivo] dice por qué.
     */
    public function usuarioPorToken($plano)
    {
        $this->motivo = null;
        $this->extra = [];
        $this->perfil = null;

        if (!is_string($plano) || $plano === '') {
            $this->motivo = self::RECHAZO_TOKEN;
            return null;
        }
        $this->asegurarTablas();
        $hash = hash('sha256', $plano);
        $fila = DB::table(self::TABLA_CACHE)->where('token_hash', $hash)->first();

        // 1) Sesión validada hace poco: se reutiliza sin molestar a turnosonlinebb.
        if ($fila !== null && Carbon::parse($fila->expira_en)->isFuture()) {
            $this->perfil = json_decode($fila->perfil_json, true);
            return $this->usuarioLocal((int) $fila->medico_id_tobb);
        }

        // 2) Se le pregunta a turnosonlinebb.
        $perfil = $this->perfilRemoto($plano);
        if ($perfil === null) {
            return $this->sinRespuestaDeTobb($fila, $hash);
        }
        return $this->aceptar($perfil, $hash);
    }

    /** Valida el perfil que devolvió turnosonlinebb y deja la sesión en caché. */
    private function aceptar(array $perfil, $hash)
    {
        $this->perfil = $perfil;
        $rol = isset($perfil['rol']) ? $perfil['rol'] : '';
        // Fase 1: solo médicos. Las secretarias entran en una fase posterior.
        if ($rol !== 'medico' || !isset($perfil['medico']) || !is_array($perfil['medico'])) {
            $this->motivo = self::RECHAZO_SIN_PERMISO;
            return null;
        }
        $medico = $perfil['medico'];
        $habilitadas = isset($medico['historias_clinicas']) && is_array($medico['historias_clinicas'])
            ? $medico['historias_clinicas'] : [];
        if (!in_array(config('salud360.hc_codigo'), $habilitadas, true)) {
            $this->motivo = self::RECHAZO_HC_NO_HABILITADA;
            return null;
        }
        $medicoIdTobb = (int) $medico['id'];
        $user = $this->usuarioLocal($medicoIdTobb);
        if ($user === null) {
            $this->registrarPendiente($medicoIdTobb, $medico, $perfil);
            $this->motivo = self::RECHAZO_MEDICO_NO_VINCULADO;
            $this->extra = ['medico_id_tobb' => $medicoIdTobb];
            return null;
        }
        $this->guardarEnCache($hash, $user->id, $medicoIdTobb, $perfil);
        return $user;
    }

    /**
     * turnosonlinebb no contestó. Si hay una sesión validada antes y no pasó la ventana de gracia,
     * se acepta igual: una caída de turnos no puede dejar al pediatra sin historia clínica.
     */
    private function sinRespuestaDeTobb($fila, $hash)
    {
        $gracia = (int) config('salud360.gracia_horas');
        if ($fila !== null && $gracia > 0 && Carbon::parse($fila->expira_en)->addHours($gracia)->isFuture()) {
            Log::warning('salud360: turnosonlinebb no responde, se acepta la sesión en caché del médico ' . $fila->medico_id_tobb);
            $this->perfil = json_decode($fila->perfil_json, true);
            $user = $this->usuarioLocal((int) $fila->medico_id_tobb);
            if ($user !== null) {
                return $user;
            }
        }
        $this->motivo = $this->motivo ?: self::RECHAZO_TOBB_CAIDO;
        return null;
    }

    /** Perfil en turnosonlinebb, o null si rechazó el token o no se pudo consultar. */
    private function perfilRemoto($plano)
    {
        $url = rtrim((string) config('salud360.tobb_url'), '/') . '/api/salud360/auth/perfil';
        try {
            $cliente = new Client(['timeout' => (int) config('salud360.tobb_timeout')]);
            $r = $cliente->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $plano,
                    'X-Salud360-Token' => $plano,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);
            $status = $r->getStatusCode();
            $cuerpo = json_decode((string) $r->getBody(), true);
            if ($status === 401 || $status === 403) {
                // turnosonlinebb rechazó el token: no es una caída, es una sesión inválida.
                $this->motivo = self::RECHAZO_TOKEN;
                return null;
            }
            if ($status < 200 || $status >= 300 || !is_array($cuerpo) || empty($cuerpo['ok']) || !isset($cuerpo['perfil'])) {
                Log::warning('salud360: respuesta inesperada de turnosonlinebb al validar el token (HTTP ' . $status . ')');
                return null;
            }
            return $cuerpo['perfil'];
        } catch (\Throwable $e) {
            Log::warning('salud360: no se pudo consultar el perfil en turnosonlinebb: ' . $e->getMessage());
            return null;
        }
    }

    /** Médico local por el número de médico de turnosonlinebb. */
    private function usuarioLocal($medicoIdTobb)
    {
        if ($medicoIdTobb <= 0) {
            return null;
        }
        return User::where('medico_id_tobb', $medicoIdTobb)
            ->where('usuario_tipo', 2)
            ->where('activo', 1)
            ->first();
    }

    private function guardarEnCache($hash, $userId, $medicoIdTobb, array $perfil)
    {
        $ahora = Carbon::now();
        $fila = [
            'user_id' => (int) $userId,
            'medico_id_tobb' => (int) $medicoIdTobb,
            'perfil_json' => json_encode($perfil),
            'expira_en' => $ahora->copy()->addMinutes((int) config('salud360.cache_minutos')),
            'ultimo_uso_en' => $ahora,
            'updated_at' => $ahora,
        ];
        $existe = DB::table(self::TABLA_CACHE)->where('token_hash', $hash)->exists();
        if ($existe) {
            DB::table(self::TABLA_CACHE)->where('token_hash', $hash)->update($fila);
            return;
        }
        $fila['token_hash'] = $hash;
        $fila['created_at'] = $ahora;
        DB::table(self::TABLA_CACHE)->insert($fila);
    }

    /**
     * Deja constancia de un médico habilitado en turnosonlinebb que todavía no tiene usuario acá.
     * Convierte un rechazo mudo en una lista de trabajo para el administrador.
     */
    private function registrarPendiente($medicoIdTobb, array $medico, array $perfil)
    {
        $ahora = Carbon::now();
        $nombre = trim((isset($medico['apellido']) ? $medico['apellido'] : '') . ', ' . (isset($medico['nombre']) ? $medico['nombre'] : ''), ', ');
        $email = isset($medico['mail']) ? (string) $medico['mail'] : (isset($perfil['usuario']['email']) ? (string) $perfil['usuario']['email'] : '');
        $fila = DB::table(self::TABLA_PENDIENTES)->where('medico_id_tobb', $medicoIdTobb)->first();
        if ($fila === null) {
            DB::table(self::TABLA_PENDIENTES)->insert([
                'medico_id_tobb' => (int) $medicoIdTobb,
                'nombre' => $nombre,
                'email' => $email,
                'intentos' => 1,
                'ultimo_intento' => $ahora,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ]);
            return;
        }
        DB::table(self::TABLA_PENDIENTES)->where('id', $fila->id)->update([
            'nombre' => $nombre ?: $fila->nombre,
            'email' => $email ?: $fila->email,
            'intentos' => (int) $fila->intentos + 1,
            'ultimo_intento' => $ahora,
            'updated_at' => $ahora,
        ]);
    }

    /** Crea las tablas de apoyo si no existen (el hosting no corre migraciones). */
    public function asegurarTablas()
    {
        if (!Schema::hasTable(self::TABLA_CACHE)) {
            Schema::create(self::TABLA_CACHE, function (Blueprint $t) {
                $t->bigIncrements('id');
                $t->string('token_hash', 64)->unique();
                $t->unsignedInteger('user_id')->index();
                $t->unsignedInteger('medico_id_tobb')->index();
                $t->text('perfil_json');
                $t->timestamp('expira_en')->nullable();
                $t->timestamp('ultimo_uso_en')->nullable();
                $t->timestamps();
            });
        }
        if (!Schema::hasTable(self::TABLA_PENDIENTES)) {
            Schema::create(self::TABLA_PENDIENTES, function (Blueprint $t) {
                $t->bigIncrements('id');
                $t->unsignedInteger('medico_id_tobb')->unique();
                $t->string('nombre', 191)->default('');
                $t->string('email', 191)->default('');
                $t->unsignedInteger('intentos')->default(0);
                $t->timestamp('ultimo_intento')->nullable();
                $t->timestamps();
            });
        }
    }

    /** Borra las sesiones vencidas más allá de la ventana de gracia. */
    public function limpiarCache()
    {
        $this->asegurarTablas();
        $limite = Carbon::now()->subHours(max(1, (int) config('salud360.gracia_horas')));
        DB::table(self::TABLA_CACHE)->where('expira_en', '<', $limite)->delete();
    }
}
