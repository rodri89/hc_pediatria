<?php

namespace App\Http\Controllers\Api\Salud360;

use Illuminate\Http\Request;

/**
 * Sesión de la API de pediatría.
 *
 * No hay `login` ni `logout`: la app ya inició sesión en turnosonlinebb y trae ese token.
 * Acá solo se confirma que el token sirve y se devuelve con qué usuario local quedó resuelto,
 * que es lo que necesita la app para saber que puede empezar a guardar.
 */
class AuthController extends Salud360Controller
{
    /** GET auth/perfil */
    public function perfil(Request $request)
    {
        $user = $this->medico($request);
        $perfilTobb = $request->attributes->get('salud360_perfil');
        $medicoTobb = is_array($perfilTobb) && isset($perfilTobb['medico']) ? $perfilTobb['medico'] : null;

        return $this->ok([
            'perfil' => [
                'usuario' => [
                    'id' => (int) $user->id,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'tipo' => (int) $user->usuario_tipo,
                ],
                'rol' => $this->rol($user),
                'hc' => config('salud360.hc_codigo'),
                'medico_id_tobb' => (int) $user->medico_id_tobb,
                // Lo que informa turnosonlinebb, para que la app pueda comparar sin pedirlo de nuevo.
                'tobb' => $medicoTobb === null ? null : [
                    'medico_id' => isset($medicoTobb['id']) ? (int) $medicoTobb['id'] : 0,
                    'historias_clinicas' => isset($medicoTobb['historias_clinicas']) ? $medicoTobb['historias_clinicas'] : [],
                ],
            ],
        ]);
    }
}
