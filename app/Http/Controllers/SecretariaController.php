<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MedicoSecretaria;
use App\User;

class SecretariaController extends Controller
{

	public function __construct()
    {    	
        $this->middleware('auth');
    }

    function nuevoPaciente() {
    	$opcion = 1;
    	$user = \Auth::user();	
    	$medicos = $this->getMedicos($user->id);
    	if($medicos->count() == 1){
    		return view('secretaria.nuevo_paciente')
        		->with('opcion', $opcion)
        		->with('medico_id', $medicos[0]->medico_id);		
    	} else {
    		return view('secretaria.seleccionar_medico')
        		->with('opcion', $opcion)
        		->with('medicos', $medicos);
        	}
    }

    function actualizarPaciente() {
    	$opcion = 2;
    	$user = \Auth::user();	
    	$medicos = $this->getMedicos($user->id);
    	return view('secretaria.seleccionar_medico')
        		->with('opcion', $opcion)
        		->with('medicos', $medicos);
    }

    function actualizarPacienteBuscar(Request $request){
    	$paciente = $this->getPacienteDNI($request->paciente_dni);
    	$medico_id = $request->medico_id;
    	$user_id = $request->user_id;
    	$medicos = $this->getMedicos($user_id);
        return view('secretaria.actualizar_paciente')
        		->with('medico_id', $medico_id)
        		->with('medicos', $medicos)        		
        		->with('paciente',$paciente);                    
    }

     function buscarPaciente() {
    	$opcion = 3;
    	$user = \Auth::user();	
    	$medicos = $this->getMedicos($user->id);
    	return view('secretaria.seleccionar_medico')
        		->with('opcion', $opcion)
        		->with('medicos', $medicos);
    }


    // me devulve todos los medicos que estan vinculados a la secretaria
    function getMedicos($secretaria_id) {
    	$medicos = DB::table('users')     
    				->join('medico_secretarias','medico_secretarias.medico_user_id','=','users.id')                                 
	                ->select('medico_secretarias.medico_user_id as medico_id','users.name','users.foto')
	                ->where('users.usuario_tipo',2) // 2 son los medicos
	                ->where('medico_secretarias.secretaria_user_id', $secretaria_id)    
	                ->where('users.activo', 1)    
	                ->distinct()
	                ->get();	
	    return $medicos;
    }

    function continuarNavegacion(Request $request){
    	$opcion = $request->opcion;
    	$medico_id = $request->medico_id;
    	if($opcion == 1){
    		return view('secretaria.nuevo_paciente')
        		->with('opcion', $opcion)
        		->with('medico_id', $medico_id);
        }
        if($opcion == 2){
    		return view('secretaria.actualizar_paciente')
        		->with('opcion', $opcion)
        		->with('paciente', null)
        		->with('medico_id', $medico_id);
        }
        if($opcion == 3){
    		return view('secretaria.buscar_paciente')
        		->with('opcion', $opcion)
        		->with('medico_id', $medico_id);
        }
    }

    function getPacienteDNI($dni){
    	$paciente = DB::table('pacientes')                                                                  
                ->where('pacientes.dni', $dni)   
				->where('pacientes.activo', 1)                                                   	   		                   
                ->first();	
        return $paciente;
    }
}
