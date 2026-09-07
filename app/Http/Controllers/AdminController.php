<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\MedicoLicencia;
use App\MedicoSecretaria;

class AdminController extends Controller
{
    
	public function __construct()
    {    	
        $this->middleware('auth');
    }

    public function vincularSecretariaMedico(Request $request)
    {
    	
		$medico_aux = explode('-',$request->get('medico'));
		$secretaria_aux = explode('-',$request->get('secretaria'));

		$sec_medico = new medicoSecretaria;
		$sec_medico->medico_user_id = $medico_aux[0];
		$sec_medico->secretaria_user_id = $secretaria_aux[0];    
		$sec_medico->activo = 1;    
    	$sec_medico->save();


    	$secretarias = DB::table('users')->where('usuario_tipo', 3)->get();
		$medicos = DB::table('users')->where('usuario_tipo', 2)->get();

    	return View('admin.admin_secretaria')
    	->with('secretarias',$secretarias)
    	->with('medicos',$medicos);   
    }

    function adminMedicos(){
        $medicos = DB::table('users')
                    ->join('medico_licencias', 'medico_licencias.medico_user_id', 'users.id')
                    ->select('users.id as uid', 'users.name', 'users.activo as uactivo', 'medico_licencias.id as mlid', 'medico_licencias.fecha_aviso_expiracion', 'medico_licencias.fecha_expiracion_licencia','medico_licencias.importe', 'medico_licencias.activo as mlactivo')
                    ->where('users.usuario_tipo', 2)
                    ->get();
        
        return view('admin.admin_medicos')
                    ->with('medicos',$medicos);   
    }

    function verMedicosAdmin(){
        return View('admin.admin_secretaria')
        ->with('secretarias',$secretarias)
        ->with('medicos',$medicos);      
    }

    function updateExpLicencia(Request $request){
        $id = $request->id;
        $nuevaFecha = $request->nuevaFecha;
        $medicoLicencia = MedicoLicencia::find($id);
        $medicoLicencia->fecha_expiracion_licencia = $nuevaFecha;
        $medicoLicencia->save();

        return response()->json(array('response'=>1));        
    }

    function updateAvisoLicencia(Request $request){
        $id = $request->id;
        $nuevaFecha = $request->nuevaFecha;
        $medicoLicencia = MedicoLicencia::find($id);
        $medicoLicencia->fecha_aviso_expiracion = $nuevaFecha;
        $medicoLicencia->save();
        
        return response()->json(array('response'=>1));        
    }

    function updateImporte(Request $request){
        $id = $request->id;
        $importe = $request->importe;
        $medicoLicencia = MedicoLicencia::find($id);
        $medicoLicencia->importe = $importe;
        $medicoLicencia->save();
        
        return response()->json(array('response'=>1));        
    }

    function updateActivo(Request $request){
        $id = $request->id;
        $activo = $request->activo;
        $medicoLicencia = MedicoLicencia::find($id);
        $medicoLicencia->activo = $activo;
        $medicoLicencia->save();
        
        return response()->json(array('response'=>1));        
    }
    
}
