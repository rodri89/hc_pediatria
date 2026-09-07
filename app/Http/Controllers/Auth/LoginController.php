<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;
use App\MedicoLicencia;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

     public function redirectPath()
    {        
        if (Auth::check()) { 
            
            $usuario_actual=\Auth::user();
            $us_tipo = $usuario_actual->usuario_tipo;
            
            switch($us_tipo)
                {
                case '1': //Administrador
                    return '/admin_home';
                    break;
                case '2': //medico
                    $licencia = $this->validarLicencia($usuario_actual->id);
                    if( $licencia == 1){
                        return '/medico_home';
                    } else {
                        if($licencia == 0){
                            return '/licencia_expirada';
                        } else {
                            return '/aviso_licencia_expirada';
                        }
                    }
                    break;
                case '3': //secretaria
                    return '/secretaria_home';                
                    break;  
                }   
        }
        else 
        {
            return redirect('/login');
        }    
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
