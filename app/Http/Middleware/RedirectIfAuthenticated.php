<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\MedicoLicencia;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
           $usuario_actual=\Auth::user();
            $us_tipo = $usuario_actual->usuario_tipo;
            
            switch($us_tipo)
                {
                   case '1': //Administrador
                        return redirect()->route('admin_home');
                        break;
                    case '2': //medico
                        $licencia = $this->validarLicencia($usuario_actual->id);
                        if( $licencia == 1){
                            return redirect()->route('medico_home');
                        } else {
                            if($licencia == 0){
                                return redirect()->route('licencia_expirada');                                
                            } else {
                            return redirect()->route('aviso_licencia_expirada');
                        }
                        }
                        break;
                    case '3': //secretaria
                        return redirect()->route('secretaria_home');
                        //return '/turnos_admin_secretaria/seleccionar_consultorio';
                        break;
                }
        }
        return $next($request);
    }

function validarLicencia($user_id){
        $medico = DB::table('users')
                    ->join('medico_licencias', 'medico_licencias.medico_user_id', 'users.id')
                    ->select('users.id as uid', 'users.name', 'users.activo as uactivo', 'medico_licencias.id as mlid', 'medico_licencias.fecha_aviso_expiracion', 'medico_licencias.fecha_expiracion_licencia','medico_licencias.importe', 'medico_licencias.activo as mlactivo')
                    ->where('users.id', $user_id)
                    ->first();
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");
        if($medico->fecha_aviso_expiracion<=$fecha && $medico->fecha_expiracion_licencia>=$fecha){
            return 2;
        } else {  
            if($medico->fecha_expiracion_licencia<$fecha || $medico->mlactivo == 0){
                $medicoLicencia = MedicoLicencia::find($medico->mlid);
                $medicoLicencia->activo = 0;
                $medicoLicencia->save();
                return 0;
            } else {            
                return 1;
            }
        }
    }
}
