<?php

namespace App\Http\Middleware;

use App\Services\Salud360\TobbAuthService;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Autenticación de la API de Salud 360 en pediatría.
 *
 * No hay login ni tokens propios: llega el token de turnosonlinebb y [TobbAuthService] lo valida
 * contra su `auth/perfil`, resolviendo el médico local por `users.medico_id_tobb`.
 *
 * Cada motivo de rechazo tiene su propio código para que "no me anda" siempre tenga causa visible.
 */
class Salud360Api
{
    /** @var TobbAuthService */
    private $tobb;

    public function __construct(TobbAuthService $tobb)
    {
        $this->tobb = $tobb;
    }

    public function handle($request, Closure $next)
    {
        $token = $this->tobb->tokenDelPedido($request);
        if (!$token) {
            return $this->rechazo('No autenticado.', 401, 'sin_token');
        }
        $user = $this->tobb->usuarioPorToken($token);
        if ($user !== null) {
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            $request->attributes->set('salud360_perfil', $this->tobb->perfil());
            return $next($request);
        }

        $extra = $this->tobb->extra();
        switch ($this->tobb->motivo()) {
            case TobbAuthService::RECHAZO_HC_NO_HABILITADA:
                return $this->rechazo('El administrador todavía no te habilitó la historia clínica de pediatría.', 403, 'hc_no_habilitada');
            case TobbAuthService::RECHAZO_MEDICO_NO_VINCULADO:
                return $this->rechazo('Tu usuario de pediatría no está vinculado con turnosonlinebb. Avisale al administrador.', 403, 'medico_no_vinculado', $extra);
            case TobbAuthService::RECHAZO_SIN_PERMISO:
                return $this->rechazo('Por ahora solo los médicos pueden usar la historia clínica desde la app.', 403, 'sin_permiso');
            case TobbAuthService::RECHAZO_TOBB_CAIDO:
                return $this->rechazo('No se pudo validar la sesión con turnosonlinebb. Intentá de nuevo en un momento.', 503, 'tobb_caido');
            default:
                return $this->rechazo('Sesión vencida. Volvé a iniciar sesión.', 401, 'token');
        }
    }

    private function rechazo($mensaje, $status, $codigo, array $extra = [])
    {
        return response()->json(array_merge(['ok' => false, 'mensaje' => $mensaje, 'codigo' => $codigo], $extra), $status);
    }
}
