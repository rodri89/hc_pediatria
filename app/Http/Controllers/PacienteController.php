<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Paciente;
use App\User;
use App\MedicoPaciente;
use datetime;
use App\MedicoInfo;

class PacienteController extends Controller
{

    public function __construct()
    {       
        $this->middleware('auth');
    }

    public function altaPacienteMedicoSecretaria(Request $request) {   
        $user = User::find($request->get('user_id'));        
        $pacienteExiste = $this->getPacienteDNI($request->dni);
        if($pacienteExiste == null){
            if((strcmp($request->nombre, '') != 0) && (strcmp($request->apellido, '') != 0)){
                $paciente = $this->altaPaciente($request);
                $response = 1;
            } else {
                $response = 0;
                $paciente = null;
                return response()->json(array('paciente'=>$paciente, 'response'=>$response));
            }
        } else {
            $paciente = $pacienteExiste;
        }
        $medico_id = $request->medico_id;

        if($user->usuario_tipo == 2) { // es medico
            if($this->validarPacienteMedicoExiste($paciente->id, $user->id) == 0) {
            	$medicoPaciente = new medicoPaciente;
            	$medicoPaciente->medico_user_id = $user->id;
            	$medicoPaciente->paciente_id = $paciente->id;
            	$medicoPaciente->activo = 1;
            	$medicoPaciente->save();
            }
        } else {
            if($user->usuario_tipo == 3) { // es secre
                $medicoPaciente = new medicoPaciente;
                $medicoPaciente->medico_user_id = $medico_id;
                $medicoPaciente->paciente_id = $paciente->id;
                $medicoPaciente->activo = 1;
                $medicoPaciente->save();
        }   
        }        
        return response()->json(array('paciente'=>$paciente, 'response'=>$response));
    }

    function validarPacienteMedicoExiste($paciente, $medico){
        $medicoPaciente = DB::table('medico_pacientes')                                                
                            ->where('medico_pacientes.paciente_id',$paciente)
                            ->where('medico_pacientes.medico_user_id', $medico)    
                            ->where('medico_pacientes.activo', 1)    
                            ->first();
        if($medicoPaciente != null)
            return 1;
        else
            return 0;
    }

    function getNewIdPaciente(){
        $paciente_aux = Paciente::max('id');
        $paciente_aux = $paciente_aux + 1;
        return $paciente_aux;
    }

    function altaPaciente(Request $request) {
    	$paciente = new paciente;
        $paciente->nombre = strtoupper($request->get('nombre'));
        $paciente->apellido = strtoupper($request->get('apellido'));
        
        if($request->get('dni') != null)
            $paciente->dni = $request->get('dni');            
        else
            $paciente->dni = 1;

        if($request->get('fecha_nacimiento')== null)        
            $paciente->fecha_nacimiento = '1000-01-01 00:00:00';
        else
            $paciente->fecha_nacimiento = $request->get('fecha_nacimiento');

        if($request->get('telefono')== null)        
            $paciente->telefono = '';
        else
            $paciente->telefono = $request->get('telefono'); 
        
        if($request->get('mail')== null)        
            $paciente->mail = '';
        else
            $paciente->mail = strtoupper($request->get('mail'));

        if($request->get('obra_social')== null)        
            $paciente->obra_social = '';
        else
            $paciente->obra_social = $request->get('obra_social');

        if($request->get('numero_afiliado')== null)
            $paciente->numero_afiliado = '';
        else    
            $paciente->numero_afiliado = strtoupper($request->get('numero_afiliado'));
        
        if($request->get('plan')== null)
            $paciente->obra_social_plan = '';
        else
            $paciente->obra_social_plan = strtoupper($request->get('plan'));

        if($request->get('domicilio')== null)        
            $paciente->domicilio = '';
        else
            $paciente->domicilio = strtoupper($request->get('domicilio')); 

        if($request->get('localidad')== null)        
            $paciente->localidad = '';
        else
            $paciente->localidad = strtoupper($request->get('localidad'));

        if($request->get('nombre_padre')== null)        
            $paciente->nombre_padre = '';
        else
            $paciente->nombre_padre = strtoupper($request->get('nombre_padre'));

        if($request->get('nombre_madre')== null)        
            $paciente->nombre_madre = '';
        else
            $paciente->nombre_madre = strtoupper($request->get('nombre_madre'));

        $paciente->sexo = $request->sexo; 
        $paciente->cantidad_hermanos = $request->hermanos; 
        $paciente->obra_social_foto = '';
        $paciente->activo = 1;  
    
        $paciente->save();

        return $paciente;
    }

    function consultarPaciente(Request $request) {
    	$dni_paciente = $request->dni_paciente;   
        $paciente_id = $request->paciente_id;

        $user = User::find($request->get('user_id'));
        if($paciente_id == null){
            $paciente = $this->getPacienteDNI($dni_paciente);
        } else {
            $paciente = paciente::find($paciente_id);
        }
        
        if($paciente != null) {                     
        	if($user->usuario_tipo == 2) {// si es medico
        		$medicoPaciente = DB::table('medico_pacientes')                                                
			                ->where('medico_pacientes.paciente_id',$paciente->id)
			               // ->where('medico_pacientes.medico_user_id', $user->id)    
			                ->where('medico_pacientes.activo', 1)    
			                ->first();
			    if($medicoPaciente != null) {
			    	return response()->json(array('paciente'=>$paciente));
			    }
			} else {
				if($user->usuario_tipo == 3) {// si es secretaria
		    		// verifico que la secretaria esta vinculada con el medico
		    		$medicoPaciente = DB::table('medico_pacientes')
						    			->join('medico_secretarias','medico_secretarias.medico_user_id','=','medico_pacientes.medico_user_id')
						                ->where('medico_pacientes.paciente_id',$paciente->id)
						                ->where('medico_secretarias.secretaria_user_id', $user->id)    
						                ->where('medico_pacientes.activo', 1)  
						                ->where('medico_secretarias.activo', 1)     
						                ->first();
					if($medicoPaciente != null) {
		    			return response()->json(array('paciente'=>$paciente));
		   			 }
				}	
			}	                                    
        }            

        return response()->json(array('paciente'=>null));
    }

    function getPacienteDNI($dni){
    	$paciente = DB::table('pacientes')                                                                  
                ->where('pacientes.dni', $dni)   
				->where('pacientes.activo', 1)                                                   	   		                   
                ->first();	
        return $paciente;
    }

    function actualizarPaciente(){
        $user=\Auth::user();      
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);
        return view('medico.actualizar_paciente')
        		->with('medico_id',$user->id)                    
                ->with('paciente', $paciente);                    
    }

    function actualizarPacienteBuscar(Request $request){
        $paciente = paciente::find($request->paciente_id);    
    	//$paciente = $this->getPacienteDNI($request->paciente_dni);
    	$user_id = $request->user_id;
        return view('medico.actualizar_paciente')
                ->with('medico_id',$request->medico_id)                   
        		->with('paciente',$paciente);                    
    }

    function actualizarDatosPaciente(Request $request){
    	$paciente_dni = $request->dni;
        $paciente_id = $request->paciente_id;
        if($paciente_id != null){
            $paciente = paciente::find($request->paciente_id);
        } else {
        $pacienteAux = DB::table('pacientes')                                               
                    ->where('pacientes.dni',$paciente_dni)                                        
                    ->first();
            if($pacienteAux != null) {
                $paciente = paciente::find($pacienteAux->id);
            }
        }

        $huboUnCambio = 0;
        if($paciente != null) {    
            if(($request->dni != null) && (strcmp($request->dni, $paciente->dni) != 0)) {
                $paciente->dni = $request->dni;
                $huboUnCambio = 1;
            }        
            if(($request->nombre != null) && (strcmp($request->nombre, $paciente->nombre) != 0)) {
                $paciente->nombre = $request->nombre;
                $huboUnCambio = 1;
            }
            if(($request->apellido != null) && (strcmp($request->apellido, $paciente->apellido) != 0)) {
                $paciente->apellido = $request->apellido;
                $huboUnCambio = 1;
            }
            if(($request->fecha_nacimiento != null) && (strcmp($request->fecha_nacimiento, $paciente->fecha_nacimiento) != 0)) {
                $paciente->fecha_nacimiento = $request->fecha_nacimiento;
                $huboUnCambio = 1;
            }    
             if(($request->telefono != null) && (strcmp($request->telefono, $paciente->telefono) != 0)) {
                $paciente->telefono = $request->telefono;
                $huboUnCambio = 1;
            }
             if(($request->mail != null) && (strcmp($request->mail, $paciente->mail) != 0)) {
                $paciente->mail = $request->mail;
                $huboUnCambio = 1;
            }        
             if(($request->obra_social != null) && (strcmp($request->obra_social, $paciente->obra_social) != 0)) {
                $paciente->obra_social = $request->obra_social;
                $huboUnCambio = 1;
            }
             if(($request->numero_afiliado != null) && (strcmp($request->numero_afiliado, $paciente->numero_afiliado) != 0)) {
                $paciente->numero_afiliado = $request->numero_afiliado;
                $huboUnCambio = 1;
            }
             if(($request->obra_social_plan != null) && (strcmp($request->obra_social_plan, $paciente->obra_social_plan) != 0)) {
                $paciente->obra_social_plan = $request->obra_social_plan;
                $huboUnCambio = 1;
            }
            if(($request->domicilio != null) && (strcmp($request->domicilio, $paciente->domicilio) != 0)) {
                $paciente->domicilio = $request->domicilio;
                $huboUnCambio = 1;
            }
            if(($request->localidad != null) && (strcmp($request->localidad, $paciente->localidad) != 0)) {
                $paciente->localidad = $request->localidad;
                $huboUnCambio = 1;
            }
            if(($request->nombre_padre != null) && (strcmp($request->nombre_padre, $paciente->nombre_padre) != 0)) {
                $paciente->nombre_padre = $request->nombre_padre;
                $huboUnCambio = 1;
            }
            if(($request->nombre_madre != null) && (strcmp($request->nombre_madre, $paciente->nombre_madre) != 0)) {
                $paciente->nombre_madre = $request->nombre_madre;
                $huboUnCambio = 1;
            }
              if(($request->hermanos != null) && (strcmp($request->hermanos, $paciente->cantidad_hermanos) != 0)) {
                $paciente->cantidad_hermanos = $request->hermanos;
                $huboUnCambio = 1;
            }
            if(($request->sexo != null) && (strcmp($request->sexo, $paciente->sexo) != 0)) {
                $paciente->sexo = $request->sexo;
                $huboUnCambio = 1;
            }
            $paciente->save();        
        } 
        return response()->json(array('paciente'=>$paciente,'huboUnCambio'=>$huboUnCambio));
    }

	function pacienteListadoBuscar(){
		$user=\Auth::user();		
        if($user->usuario_tipo == 2){      
            $users = DB::table('pacientes')                                
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')                        
                                ->select('pacientes.*')                                
                                ->where('medico_pacientes.medico_user_id',$user->id)                                                       
                                ->where('pacientes.activo', 1) 
                                ->where('medico_pacientes.activo', 1)                                 
                                ->distinct()
                                ->orderby('pacientes.apellido');        
        } else {
            if($user->usuario_tipo == 3){      
                $users = DB::table('pacientes')             
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')
                                ->join('medico_secretarias','medico_secretarias.medico_user_id','=','medico_pacientes.medico_user_id')                                
                                ->select('pacientes.*')                                                                
                                ->where('medico_secretarias.secretaria_user_id', $user->id) 
                                ->where('pacientes.activo', 1) 
                                ->where('medico_pacientes.activo', 1)
                                ->where('medico_secretarias.activo', 1)   
                                ->distinct()                              
                                ->orderby('pacientes.apellido');            
            }
        }
        
        return datatables()->of($users)
                           ->addIndexColumn()
                           ->addColumn('action', function($row){                                   
                               $val = $row->dni;
                               $btn = "<button onclick=optionModal($val) class='rodri_button_aceptar_si'>...</button>";
                               return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);		
	}

    function pacienteConsultar(Request $request){
        $texto = $request->texto;
        $medico = $request->medico_id;
        $textoEsNumero = $request->esNumero;
   
        if($textoEsNumero == 1){            
           $pacientes = DB::table('pacientes')             
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')                                
                                ->select('pacientes.*')
                                ->where('medico_pacientes.medico_user_id', $medico) 
                                ->where('pacientes.dni', $texto) 
                                ->where('pacientes.activo', 1) 
                                ->where('medico_pacientes.activo', 1)                                                                
                                ->orderby('pacientes.apellido')
                                ->get();
        } else {
            $texto = strtoupper($texto);            
            $pacienteNombre = DB::table('pacientes')             
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')                                
                                ->select('pacientes.*')
                                ->where('medico_pacientes.medico_user_id', $medico)                                
                                ->where('pacientes.nombre', 'LIKE', '%' . $texto . '%')
                                ->where('pacientes.activo', 1) 
                                ->where('medico_pacientes.activo', 1)                                
                                ->distinct()                              
                                ->orderby('pacientes.apellido')
                                ->get();  
             $pacienteApellido = DB::table('pacientes')             
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')                                
                                ->select('pacientes.*')
                                ->where('medico_pacientes.medico_user_id', $medico)                                
                                ->where('pacientes.apellido', 'LIKE', '%' . $texto . '%')
                                ->where('pacientes.activo', 1) 
                                ->where('medico_pacientes.activo', 1)                                
                                ->distinct()                              
                                ->orderby('pacientes.apellido')
                                ->get();      

            $pacientes = $pacienteNombre->merge($pacienteApellido);
            $pacientes = $pacientes->unique('id');               
        }
        $cantPacientes = $pacientes->count();

        return response()->json(array('pacientes'=>$pacientes, 'texto'=>$texto, 'cantPacientes'=>$cantPacientes));
    }

    function getPacienteId(Request $request){
        $paciente_id = $request->paciente_id;
        $paciente = Paciente::find($paciente_id);
        $medico_id = $request->medico_id;

        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $medico_id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        $medicoInfo->paciente_id = $paciente_id;
        $medicoInfo->save();
        
        return response()->json(array('paciente'=>$paciente));
    }

    function manipularImagenes(){
        return view('utils.manipular_imagenes');
    }
}
