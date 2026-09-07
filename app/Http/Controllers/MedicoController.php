<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Paciente;
use App\Consulta;
use App\AntecedentesPerinatales;
use App\AntecedentesPersonales;
use App\AntecedentesFamiliares;
use App\Escolaridad;
use App\ActividadesExtraEscolares;
use App\Pantalla;
use App\Habitos;
use App\Menarca;
use App\Conducta;
use App\Observacion;
use App\Alimentacion;
use App\ExamenFisico;
use App\Interconsulta;
use Image;
use App\ExamenesComplementarios;
use App\ExamenesComplementariosFotos;
use App\AntecedentesNeonatales;
use App\AntecedentesNeonatalesFotos;
use App\VacunasPacientes;
use App\Vacunas;
use App\MedicoInfo;
use App\Interconsultores;
use App\AuxPendiente;
use Carbon;
use App\ConsultaFoto;
use App\DesarrolloMadurativoPaciente;
use App\DesarrolloMadurativo;
use App\Internacione;
use App\InternacionesFotos;
use App\MotivoConsulta;
use App\VacunasDos;
use App\Nota;
use App\Somnia;
use App\Catarsis;
use App\MedicoPaciente;
use App\DatosSubjetivos;
use App\DatosObjetivos;
use App\Screening;
use App\Familigrama;
use App\VacunaAntigripal;
use App\Familia;
use App\EmbarazoActual;
use App\AntecedentesObstetrico;
use App\LactanciaEmbarazoPrevio;
use App\Lactancia;
use GuzzleHttp\Client;

class MedicoController extends Controller
{

    function guardarVacunaOtraPaciente(Request $request){
        $vacuna_id = $request->vacuna_id;        
        $edad_meses = $request->edadMeses;
        $estado = 1;
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $vacunaViewId = $request->vacunaViewId;
        $otra = $request->otra;

        $vacunaPaciente = $this->checkExisteVacuna($paciente_id, $vacuna_id, $edad_meses);            
        if($vacunaPaciente != null){            
            $vacunaPaciente->estado = $estado;
            $vacunaPaciente->nombre = $otra;
            $vacunaPaciente->activo = 1;
            $vacunaPaciente->save();
        } else {
            $vacuna =  Vacunas::find($vacuna_id);                       
            $vacunaPaciente = new VacunasPacientes;
            $vacunaPaciente->paciente_id = $paciente_id;
            $vacunaPaciente->vacuna_id = $vacuna_id;
            $vacunaPaciente->nombre = $otra;
            $vacunaPaciente->edad_meses = $edad_meses;
            $vacunaPaciente->estado = $estado;
            $vacunaPaciente->vacuna_view_id = $vacunaViewId;
            $vacunaPaciente->activo = 1;
            $vacunaPaciente->save();
        }
        
        return response()->json(array('response'=>1, 'request'=>$vacuna_id));        
    }

    function crearVacunasPaciente($paciente_id) {
       $vacunasPaciente = DB::table('vacunas_pacientes')
                                ->where('vacunas_pacientes.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                         $query->where('vacunas_pacientes.activo', 1)
                                               ->orWhere('vacunas_pacientes.activo', 2);
                                    })                                                                            
                                ->first();
        if($vacunasPaciente == null) {
             $vacunas = DB::table('vacunas')                                
                                ->where('vacunas.activo', 1)                                               
                                ->get();
            foreach($vacunas as $v){
                $vacunaPacienteNueva = new VacunasPacientes;
                $vacunaPacienteNueva->paciente_id = $paciente_id;
                $vacunaPacienteNueva->vacuna_id = $v->id;
                $vacunaPacienteNueva->nombre = $v->nombre;
                $vacunaPacienteNueva->edad_meses = 0;
                $vacunaPacienteNueva->estado = 0;
                $vacunaPacienteNueva->activo = 1;
                $vacunaPacienteNueva->save();    
            }
        }
    }

    function getVacunasPaciente($paciente_id){
        $vacunasPaciente = DB::table('vacunas_pacientes')
                                ->where('vacunas_pacientes.paciente_id', $paciente_id)
                                 ->where(function ($query) {
                                         $query->where('vacunas_pacientes.activo', 1)
                                               ->orWhere('vacunas_pacientes.activo', 2);
                                    })                                                 
                                ->get();
        return $vacunasPaciente;
    }

    function guardarVacunaPaciente(Request $request){
        $vacuna_id = $request->vacuna_id;        
        $edad_meses = $request->edadMeses;
        $estado = $request->estado;
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $vacunaViewId = $request->vacunaViewId;

        $vacunaPaciente = $this->checkExisteVacuna($paciente_id, $vacuna_id, $edad_meses);            
        if($vacunaPaciente != null){            
            $vacunaPaciente->estado = $estado;
            $vacunaPaciente->activo = 1;
            $vacunaPaciente->save();
        } else {
            $vacuna =  Vacunas::find($vacuna_id);                       
            $vacunaPaciente = new VacunasPacientes;
            $vacunaPaciente->paciente_id = $paciente_id;
            $vacunaPaciente->vacuna_id = $vacuna_id;
            $vacunaPaciente->nombre = $vacuna->nombre;
            $vacunaPaciente->edad_meses = $edad_meses;
            $vacunaPaciente->estado = $estado;
            $vacunaPaciente->vacuna_view_id = $vacunaViewId;
            $vacunaPaciente->activo = 1;
            $vacunaPaciente->save();
        }
        
        return response()->json(array('response'=>1, 'request'=>$vacuna_id));        
    }

    function checkExisteVacuna($paciente_id, $vacuna_id, $meses){
        $vacunasPaciente = DB::table('vacunas_pacientes')
                                ->where('vacunas_pacientes.paciente_id', $paciente_id)
                                ->where('vacunas_pacientes.vacuna_id', $vacuna_id)
                                ->where('vacunas_pacientes.edad_meses', $meses)
                                ->where(function ($query) {
                                         $query->where('vacunas_pacientes.activo', 1)
                                               ->orWhere('vacunas_pacientes.activo', 2);
                                    })                                   
                                ->first();
        if($vacunasPaciente != null)
             $vacunasPaciente = VacunasPacientes::find($vacunasPaciente->id);                       
        return $vacunasPaciente;
    }

    function cargarVacunaPaciente(Request $request){
        $vacunaPaciente = $this->getVacunasPaciente($request->paciente);
        return response()->json(array('response'=>1, 'vacunaPaciente'=>$vacunaPaciente));        
    }

    function nuevaConsultaPaciente(){
           return view('medico.nueva')->with('cargar_foto_check', 1); 
    }    

    function nuevaConsultaOpciones(){
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);

        return view('medico.nueva_consulta_opciones')->with('paciente', $paciente)->with('medico_id', $user->id);    
    }

    function nuevaConsultaPacienteListadoBuscar(){
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
                               $val = $row->id;
                               $btn = "<button onclick=navegarNuevaConsulta($val) class='rodri_button_aceptar_si'>></button>";
                               /*$btn = "<form method='POST' action='{{ route('nuevaconsulta') }}'>
                                        @csrf                           
                                        <button class='rodri_button_aceptar_si' type='submit'>></button>                                        
                                        </form>";*/
                               return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //    DEPRECATED
    function consultaGet($paciente, $consulta){                
        $_paciente = paciente::find($paciente);
        if($consulta == null){
            if($_paciente != null){
                $consultaAbierta = $this->checkConsultaAbierta($paciente);
                if( $consultaAbierta == null){
                    $_consulta = new Consulta;
                    $_consulta->paciente_id = $paciente;
                    $_consulta->activo = 2;
                    $_consulta->save();
                } else {
                    $_consulta = $consultaAbierta;
                }
            } else {
                $_consulta = null;
            }
        } else {
            $_consulta = Consulta::find($consulta);
        }

         return view('medico.nueva_consulta')
                    ->with('nueva_consulta', 0)
                    ->with('consulta',$_consulta)                
                    ->with('paciente',$_paciente);                
    }

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //    DEPRECATED
    function nuevaConsulta(Request $request){
        if($request->paciente_id != null){
        	$paciente_id = $request->paciente_id;
        	$paciente = paciente::find($paciente_id);
        	if($paciente != null) {
                $consultaAbierta = $this->checkConsultaAbierta($paciente_id);
                if( $consultaAbierta == null){
            		$consulta = new Consulta;
            		$consulta->paciente_id = $paciente->id;
            		$consulta->activo = 2;
            		$consulta->save();
                } else {
                    $consulta = $consultaAbierta;
                }
        	} else {
        		$consulta = null;
        	}
        } else {
            $paciente = null;
            $consulta = null;
        }
        
        //$this->crearVacunasPaciente($paciente_id);
    	//$vacunasPaciente = $this->getVacunasPaciente($paciente_id);

        return view('medico.nueva_consulta')
    	 			->with('nueva_consulta', 1)
                    ->with('consulta',$consulta)                                    
    	 			->with('paciente',$paciente);                
    }

    // se considera consulta abierta aquella que tenga activo = 2
    function checkConsultaAbierta($paciente_id){
        $consultaActiva = DB::table('consultas')
                                    ->where('consultas.paciente_id', $paciente_id)
                                    ->where('consultas.activo', 2)                                               
                                    ->first();
        return $consultaActiva;
    }

    function getTablaInterconsultores(){
        $user=\Auth::user();
        $interconsultores = DB::table('interconsultores')
                            ->where('interconsultores.user_id', $user->id)
                            ->where('interconsultores.activo', 1)
                            ->orderby('interconsultores.especialidad')
                            ->get();
        return $interconsultores;
    }

    function interconsultores(){
        $interconsultores = $this->getTablaInterconsultores();
    	return view('medico.interconsultores')
                            ->with('interconsultores', $interconsultores);
    }

    function certficadoAptitudFisico(){        
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("d-m-Y");  
        $edad = \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age;                        
        return view('medico.certificado_aptitud_fisico')
                    ->with('paciente', $paciente)
                    ->with('edad', $edad)
                    ->with('fecha', $fecha);   
    }

    function resumenHistoriaClinica(){
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);
        $consulta = DB::table('consultas')
                            ->where('consultas.paciente_id', $paciente->id)
                            ->where('consultas.medico_id', $user->id)
                            ->where('consultas.tipo_consulta', 1)
                            ->orderby('consultas.id', 'desc')
                            ->first();
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("d-m-Y");  
        $edad = \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age; 
        if($consulta != null){                       
             return view('medico.resumen_historia_clinica')
                    ->with('paciente', $paciente)
                    ->with('consulta', $consulta)
                    ->with('edad', $edad)
                    ->with('fecha', $fecha);      
                } else {        
                    $mensaje = "El paciente no tiene consultas previas.";
                    return view('medico.mostrar_mensaje')
                                ->with('mensaje', $mensaje);
                
                }
    }

    function certficadoAptitudFisicoSeleccionar(){
        return view('medico.certificado_aptitud_fisico_seleccionar');      
    }

    function historiaClinicaResumenOs(){
        return view('medico.resumen_historia_clinica_seleccionar');      
    }

    function cafSeleccionarListado(){
        $user=\Auth::user();
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();        
        $paciente_id = $medicoInfoAux->paciente_id;
        $users = DB::table('pacientes')                                
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')                                
                                ->select('pacientes.id as paciente_id','pacientes.nombre','pacientes.apellido', 'pacientes.dni','pacientes.telefono')                                
                                ->where('medico_pacientes.medico_user_id',$user->id)                                
                                ->where('pacientes.activo', 1)                                 
                                ->where('medico_pacientes.activo', 1)
                                ->distinct()                                                                 
                                ->orderby('pacientes.apellido');        
                
        return datatables()->of($users)
                           ->addIndexColumn()
                           ->addColumn('action', function($row){                                                                  
                               $paciente_id = $row->paciente_id;
                               $btn = "<button onclick=navegarCertificado($paciente_id) class='rodri_button_aceptar_si'>></button>";
                               return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);    
         
    }    

    function navegarCertificadoAptitudFisico(Request $request) {
        $paciente_id = $request->paciente_id;   
            
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        if($medicoInfoAux != null){
            $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->consulta_id = 0;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->save();
        } else {
            $medicoInfo = new MedicoInfo;
            $medicoInfo->medico_user_id = $user->id;
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->consulta_id = 0;
            $medicoInfo-> save();
        }     
        return response()->json(array('response'=>1, 'paciente_id'=>$paciente_id));
    }

    function navegarResumenHistoriaClinica(Request $request) {
        $paciente_id = $request->paciente_id;   
        $ultimaConsulta = DB::table('consultas')
                            ->where('consultas.paciente_id', $paciente_id)
                            ->where('consultas.activo', 1)
                            //->orderby('consultas.id', 'desc')
                            ->first(); 

        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        if($medicoInfoAux != null){
            $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->consulta_id = $ultimaConsulta->id;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->save();
        } else {
            $medicoInfo = new MedicoInfo;
            $medicoInfo->medico_user_id = $user->id;
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->consulta_id = $ultimaConsulta->id;
            $medicoInfo-> save();
        }     
        return response()->json(array('response'=>1, 'paciente_id'=>$paciente_id));
    }

    function guardarAntecedentesPerinatales(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        if($this->existeAntecedentePerinatal($consulta_id, $paciente_id) == null) { 
            $antecedentesPerinatales = new AntecedentesPerinatales;
        } else {
            $antecedentesPerinatales_aux = $this->existeAntecedentePerinatal($consulta_id, $paciente_id);
            $antecedentesPerinatales = AntecedentesPerinatales::find($antecedentesPerinatales_aux->id);
        }
            
        $antecedentesPerinatales->consulta_id = $consulta_id;
        $antecedentesPerinatales->paciente_id = $paciente_id;
        $antecedentesPerinatales->embarazo = $request->embarazo;
        
        if($request->embarazo_numero_controles != null)
            $antecedentesPerinatales->embarazo_controles = $request->embarazo_numero_controles;
        else
            $antecedentesPerinatales->embarazo_controles = 0;

        $antecedentesPerinatales->patologias = $request->patologia;
        if($request->patologias_detalle != null)
            $antecedentesPerinatales->patologias_detalle = $request->patologias_detalle;
        else
            $antecedentesPerinatales->patologias_detalle = "";

        $antecedentesPerinatales->hisop_sbha = $request->hisop_sbha;
        if($request->hisop_sbha_detalle != null)
            $antecedentesPerinatales->hisop_sbha_detalle = $request->hisop_sbha_detalle;
        else
            $antecedentesPerinatales->hisop_sbha_detalle = "";

        $antecedentesPerinatales->serologia1 = $request->serologia1;
        if($request->serologia1_detalle != null)
            $antecedentesPerinatales->serologia1_detalle = $request->serologia1_detalle;
        else
            $antecedentesPerinatales->serologia1_detalle = "";

        $antecedentesPerinatales->serologia3 = $request->serologia3;
        if($request->serologia3_detalle != null)
            $antecedentesPerinatales->serologia3_detalle = $request->serologia3_detalle;
        else
            $antecedentesPerinatales->serologia3_detalle = "";
        
        $antecedentesPerinatales->vdrl = $request->vdrl;
        if($request->vdrl_detalle != null)
            $antecedentesPerinatales->vdrl_detalle = $request->vdrl_detalle;
        else
            $antecedentesPerinatales->vdrl_detalle = "";

        $antecedentesPerinatales->chagas = $request->chagas;
        if($request->chagas_detalle != null)
            $antecedentesPerinatales->chagas_detalle = $request->chagas_detalle;
        else
            $antecedentesPerinatales->chagas_detalle = "";

        $antecedentesPerinatales->parto = $request->parto;
        if($request->parto_detalle != null)
            $antecedentesPerinatales->parto_detalle = $request->parto_detalle;
        else
            $antecedentesPerinatales->parto_detalle = "";

        if($request->eg != null)
            $antecedentesPerinatales->eg = $request->eg;
        else
            $antecedentesPerinatales->eg = "";

        if($request->peso != null)
            $antecedentesPerinatales->peso = $request->peso;
        else
            $antecedentesPerinatales->peso = "";

        if($request->talla != null)
            $antecedentesPerinatales->talla = $request->talla;
        else
            $antecedentesPerinatales->talla = "";

        if($request->pc != null)
            $antecedentesPerinatales->pc = $request->pc;
        else
            $antecedentesPerinatales->pc = "";

        if($request->apgar != null)
            $antecedentesPerinatales->apgar = $request->apgar;
        else
            $antecedentesPerinatales->apgar = "";

        if($request->caida_cordon != null)
            $antecedentesPerinatales->caida_cordon = $request->caida_cordon;
        else
            $antecedentesPerinatales->caida_cordon = 0;

        if($request->meconio != null)
            $antecedentesPerinatales->meconio = $request->meconio;
        else
            $antecedentesPerinatales->meconio = 0;

         if($request->gyf != null)
            $antecedentesPerinatales->gyf = $request->gyf;
        else
            $antecedentesPerinatales->gyf = "";

        $antecedentesPerinatales->fei = $request->fei;
        if($request->fei_anormal_detalle != null)
            $antecedentesPerinatales->fei_anormal_detalle = $request->fei_anormal_detalle;
        else
            $antecedentesPerinatales->fei_anormal_detalle = "";

        $antecedentesPerinatales->oea = $request->oea;
        $antecedentesPerinatales->activo = 1; // quiere decir que todavia no esta confirmado
        $antecedentesPerinatales->save();

        return response()->json(array('response'=>1, 'request'=>$request));
        
    }    

    function existeAntecedentePerinatal($consulta_id, $paciente_id){
        $antecedentePerinatal = DB::table('antecedentes_perinatales')                                    
                                    ->where('antecedentes_perinatales.paciente_id', $paciente_id)
                                     ->where(function ($query) {
                                         $query->where('antecedentes_perinatales.activo', 1)
                                               ->orWhere('antecedentes_perinatales.activo', 2);
                                    })                           
                                    ->first();
        return $antecedentePerinatal;
    }

    function guardarAntecedentesPersonales(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $numero = $request->numero;
        if($numero == null){
            $numero = 1;
        }

        if($this->existeAntecedentesPersonales($consulta_id, $paciente_id) == null) { 
            $antecedentesPersonales = new AntecedentesPersonales;
        } else {
            $antecedentesPersonales_aux = $this->existeAntecedentesPersonales($consulta_id, $paciente_id);
            $antecedentesPersonales = AntecedentesPersonales::find($antecedentesPersonales_aux->id);
        }

        $antecedentesPersonales->consulta_id = $consulta_id;
        $antecedentesPersonales->paciente_id = $paciente_id;
        $antecedentesPersonales->numero = $numero;

        if($request->enfermedad_actual != null)
            $antecedentesPersonales->enfermedad_actual = $request->enfermedad_actual;
        else
            $antecedentesPersonales->enfermedad_actual = "";

        if($request->enfermedad_actual != null)
            $antecedentesPersonales->enfermedad_actual = $request->enfermedad_actual;
        else
            $antecedentesPersonales->enfermedad_actual = "";

        if($request->internaciones != null)
            $antecedentesPersonales->internaciones = $request->internaciones;
        else
            $antecedentesPersonales->internaciones = 2;
        /*if($request->internaciones_motivo != null)
            $antecedentesPersonales->internacion_motivo = $request->internaciones_motivo;
        else
            $antecedentesPersonales->internacion_motivo = "";

        if($request->interanaciones_lugar != null)
            $antecedentesPersonales->internacion_lugar = $request->interanaciones_lugar;
        else
            $antecedentesPersonales->internacion_lugar = "";

        if($request->interanaciones_duracion != null)
            $antecedentesPersonales->internacion_duracion = $request->interanaciones_duracion;
        else
            $antecedentesPersonales->internacion_duracion = "";

        if($request->interanaciones_indicacion_alta != null)
            $antecedentesPersonales->internacion_indicacion_alta = $request->interanaciones_indicacion_alta;
        else
            $antecedentesPersonales->internacion_indicacion_alta = "";
        */
        if($request->alergias != null)
            $antecedentesPersonales->alergias = $request->alergias;
        else
            $antecedentesPersonales->alergias = 2;
        if($request->alergia_detalle != null)
            $antecedentesPersonales->alergia_detalle = $request->alergia_detalle;
        else
            $antecedentesPersonales->alergia_detalle = "";       

        if($request->qx != null)
            $antecedentesPersonales->qx = $request->qx;
        else
            $antecedentesPersonales->qx = 2;
        
        if($request->qx_detalle != null)
            $antecedentesPersonales->qx_detalle = $request->qx_detalle;
        else
            $antecedentesPersonales->qx_detalle = "";               

        if($request->traumatismo != null)
            $antecedentesPersonales->traumatismos = $request->traumatismo;
        else
            $antecedentesPersonales->traumatismos = 2;

        if($request->traumatismo_detalle != null)
            $antecedentesPersonales->traumatismos_detalle = $request->traumatismo_detalle;
        else
            $antecedentesPersonales->traumatismos_detalle = "";                

        if($request->transfusiones != null)
            $antecedentesPersonales->transfusiones = $request->transfusiones;
        else
            $antecedentesPersonales->transfusiones = 2;

        if($request->transfusiones_detalle != null)
            $antecedentesPersonales->transfusiones_detalle = $request->transfusiones_detalle;
        else
            $antecedentesPersonales->transfusiones_detalle = "";               

        if($request->otro != null)
            $antecedentesPersonales->otro = $request->otro;
        else
            $antecedentesPersonales->otro = 2;

        if($request->otro_detalle != null)
            $antecedentesPersonales->otro_detalle = $request->otro_detalle;
        else
            $antecedentesPersonales->otro_detalle = "";

        $antecedentesPersonales->activo = $request->activo; // quiere decir que todavia no esta confirmado
        $antecedentesPersonales->save();

        return response()->json(array('response'=>1, 'request'=>$request, 'antecedentesPersonales'=>$antecedentesPersonales));

    }

   function existeAntecedentesPersonales($consulta_id, $paciente_id){
        $antecedentesPersonales = DB::table('antecedentes_personales')
                                ->where('antecedentes_personales.consulta_id', $consulta_id)
                                ->where('antecedentes_personales.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('antecedentes_personales.activo', 1)
                                           ->orWhere('antecedentes_personales.activo', 2);
                                })                                    
                                ->first();
        return $antecedentesPersonales;
    }

    function guardarAntecedentesFamiliares(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeAntecedentesFamiliares($consulta_id, $paciente_id) == null) { 
            $antecedentesFamiliares = new AntecedentesFamiliares;
        } else {
            $antecedentesFamiliares_aux = $this->existeAntecedentesFamiliares($consulta_id, $paciente_id);
            $antecedentesFamiliares = AntecedentesFamiliares::find($antecedentesFamiliares_aux->id);
        }

        $antecedentesFamiliares->consulta_id = $consulta_id;
        $antecedentesFamiliares->paciente_id = $paciente_id;

        $antecedentesFamiliares->hta = $request->hta;
        if($request->hta_detalle != null)
            $antecedentesFamiliares->hta_detalle = $request->hta_detalle;
        else
            $antecedentesFamiliares->hta_detalle = "";

        $antecedentesFamiliares->dbt = $request->dbt;
        if($request->dbt_detalle != null)
            $antecedentesFamiliares->dbt_detalle = $request->dbt_detalle;
        else
            $antecedentesFamiliares->dbt_detalle = "";

        $antecedentesFamiliares->asma = $request->asma;
        if($request->asma_detalle != null)
            $antecedentesFamiliares->asma_detalle = $request->asma_detalle;
        else
            $antecedentesFamiliares->asma_detalle = "";

        $antecedentesFamiliares->alergia = $request->alergia;
        if($request->alergia_detalle != null)
            $antecedentesFamiliares->alergia_detalle = $request->alergia_detalle;
        else
            $antecedentesFamiliares->alergia_detalle = "";

        $antecedentesFamiliares->enf_cv = $request->enf_cv;
        if($request->enf_cv_detalle != null)
            $antecedentesFamiliares->enf_cv_detalle = $request->enf_cv_detalle;
        else
            $antecedentesFamiliares->enf_cv_detalle = "";

        $antecedentesFamiliares->muerte_subita = $request->muerte_subita;
        if($request->muerte_subita_detalle != null)
            $antecedentesFamiliares->muerte_subita_detalle = $request->muerte_subita_detalle;
        else
            $antecedentesFamiliares->muerte_subita_detalle = "";

        $antecedentesFamiliares->enf_celiaca = $request->enf_celiaca;
        if($request->enf_celiaca_detalle != null)
            $antecedentesFamiliares->enf_celiaca_detalle = $request->enf_celiaca_detalle;
        else
            $antecedentesFamiliares->enf_celiaca_detalle = "";

        $antecedentesFamiliares->enf_tiroideas = $request->enf_tiroideas;
        if($request->enf_tiroideas_detalle != null)
            $antecedentesFamiliares->enf_tiroideas_detalle = $request->enf_tiroideas_detalle;
        else
            $antecedentesFamiliares->enf_tiroideas_detalle = "";

        $antecedentesFamiliares->enf_neurologicas = $request->enf_neurologicas;
        if($request->enf_neurologicas_detalle != null)
            $antecedentesFamiliares->enf_neurologicas_detalle = $request->enf_neurologicas_detalle;
        else
            $antecedentesFamiliares->enf_neurologicas_detalle = "";

         $antecedentesFamiliares->convulsion_febril = $request->convulsion_febril;
        if($request->convulsion_febril_detalle != null)
            $antecedentesFamiliares->convulsion_febril_detalle = $request->convulsion_febril_detalle;
        else
            $antecedentesFamiliares->convulsion_febril_detalle = "";

        $antecedentesFamiliares->enf_psiquiatrica = $request->enf_psiquiatrica;
        if($request->enf_psiquiatrica_detalle != null)
            $antecedentesFamiliares->enf_psiquiatrica_detalle = $request->enf_psiquiatrica_detalle;
        else
            $antecedentesFamiliares->enf_psiquiatrica_detalle = "";

        $antecedentesFamiliares->enf_oh = $request->enf_oh;
        if($request->enf_oh_detalle != null)
            $antecedentesFamiliares->enf_oh_detalle = $request->enf_oh_detalle;
        else
            $antecedentesFamiliares->enf_oh_detalle = "";

        $antecedentesFamiliares->tabaquismo = $request->tabaquismo;
        if($request->tabaquismo_detalle != null)
            $antecedentesFamiliares->tabaquismo_detalle = $request->tabaquismo_detalle;
        else
            $antecedentesFamiliares->tabaquismo_detalle = "";

        $antecedentesFamiliares->otro = $request->otro;
        if($request->otro_detalle != null)
            $antecedentesFamiliares->otro_detalle = $request->otro_detalle;
        else
            $antecedentesFamiliares->otro_detalle = "";

        if($request->nota != null)
            $antecedentesFamiliares->nota = $request->nota;
        else
            $antecedentesFamiliares->nota = "";

        $antecedentesFamiliares->activo = 1;
        $antecedentesFamiliares->save();

         return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeAntecedentesFamiliares($consulta_id, $paciente_id){
        $antecedentesFamiliares = DB::table('antecedentes_familiares')
                                ->where('antecedentes_familiares.consulta_id', $consulta_id)
                                ->where('antecedentes_familiares.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('antecedentes_familiares.activo', 1)
                                           ->orWhere('antecedentes_familiares.activo', 2);
                                })                                    
                                ->first();
        return $antecedentesFamiliares;
    }

    function guardarEscolaridad(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeEscolaridad($consulta_id, $paciente_id) == null) { 
            $escolaridad = new Escolaridad;
        } else {
            $escolaridad_aux = $this->existeEscolaridad($consulta_id, $paciente_id);
            $escolaridad = Escolaridad::find($escolaridad_aux->id);
        }

        $escolaridad->consulta_id = $consulta_id;
        $escolaridad->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $escolaridad->descripcion = $request->descripcion;
        else
            $escolaridad->descripcion = "";    

        $escolaridad->activo = 1;
        $escolaridad->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeEscolaridad($consulta_id, $paciente_id){
        $escolaridad = DB::table('escolaridads')
                                ->where('escolaridads.consulta_id', $consulta_id)
                                ->where('escolaridads.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('escolaridads.activo', 1)
                                           ->orWhere('escolaridads.activo', 2);
                                })                                    
                                ->first();
        return $escolaridad;
    }

    function guardarActividadesExtraEscolares(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeActividadesExtraEscolares($consulta_id, $paciente_id) == null) { 
            $actividadesExtraEscolares = new ActividadesExtraEscolares;
        } else {
            $actividadesExtraEscolares_aux = $this->existeActividadesExtraEscolares($consulta_id, $paciente_id);
            $actividadesExtraEscolares = ActividadesExtraEscolares::find($actividadesExtraEscolares_aux->id);
        }

        $actividadesExtraEscolares->consulta_id = $consulta_id;
        $actividadesExtraEscolares->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $actividadesExtraEscolares->descripcion = $request->descripcion;
        else
            $actividadesExtraEscolares->descripcion = "";    

        $actividadesExtraEscolares->activo = 1;
        $actividadesExtraEscolares->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

     function existeActividadesExtraEscolares($consulta_id, $paciente_id){
        $actividadesExtraEscolares = DB::table('actividades_extra_escolares')
                                ->where('actividades_extra_escolares.consulta_id', $consulta_id)
                                ->where('actividades_extra_escolares.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('actividades_extra_escolares.activo', 1)
                                           ->orWhere('actividades_extra_escolares.activo', 2);
                                })                                    
                                ->first();
        return $actividadesExtraEscolares;
    }

    function guardarMotivoConsulta(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeMotivoConsulta($consulta_id, $paciente_id) == null) { 
            $motivoConsulta = new MotivoConsulta;
        } else {
            $motivoConsulta_aux = $this->existeMotivoConsulta($consulta_id, $paciente_id);
            $motivoConsulta = MotivoConsulta::find($motivoConsulta_aux->id);
        }

        $motivoConsulta->consulta_id = $consulta_id;
        $motivoConsulta->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $motivoConsulta->descripcion = $request->descripcion;
        else
            $motivoConsulta->descripcion = "";    

        $motivoConsulta->activo = 1;
        $motivoConsulta->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

     function existeMotivoConsulta($consulta_id, $paciente_id){
        $motivoConsulta = DB::table('motivo_consultas')
                                ->where('motivo_consultas.consulta_id', $consulta_id)
                                ->where('motivo_consultas.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('motivo_consultas.activo', 1)
                                           ->orWhere('motivo_consultas.activo', 2);
                                })                                    
                                ->first();
        return $motivoConsulta;
    }

    function guardarVacunasDos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeVacunasDos($consulta_id, $paciente_id) == null) { 
            $vacunasDos = new VacunasDos;
        } else {
            $vacunas_dos_aux = $this->existeVacunasDos($consulta_id, $paciente_id);
            $vacunasDos = VacunasDos::find($vacunas_dos_aux->id);
        }

        $vacunasDos->consulta_id = $consulta_id;
        $vacunasDos->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $vacunasDos->descripcion = $request->descripcion;
        else
            $vacunasDos->descripcion = "";    

        $vacunasDos->activo = 1;
        $vacunasDos->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeVacunasDos($consulta_id, $paciente_id){
        $vacunasDos = DB::table('vacunas_dos')
                                ->where('vacunas_dos.consulta_id', $consulta_id)
                                ->where('vacunas_dos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('vacunas_dos.activo', 1)
                                           ->orWhere('vacunas_dos.activo', 2);
                                })                                    
                                ->first();
        return $vacunasDos;
    }

    function guardarNota(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeNota($consulta_id, $paciente_id) == null) { 
            $nota = new Nota;
        } else {
            $nota_aux = $this->existeNota($consulta_id, $paciente_id);
            $nota = Nota::find($nota_aux->id);
        }

        $nota->consulta_id = $consulta_id;
        $nota->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $nota->descripcion = $request->descripcion;
        else
            $nota->descripcion = "";    

        $nota->activo = 1;
        $nota->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeNota($consulta_id, $paciente_id){
        $notas = DB::table('notas')
                                ->where('notas.consulta_id', $consulta_id)
                                ->where('notas.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('notas.activo', 1)
                                           ->orWhere('notas.activo', 2);
                                })                                    
                                ->first();
        return $notas;
    }

    function guardarPantallas(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existePantallas($consulta_id, $paciente_id) == null) { 
            $pantallas = new Pantalla;
        } else {
            $pantallas_aux = $this->existePantallas($consulta_id, $paciente_id);
            $pantallas = Pantalla::find($pantallas_aux->id);
        }

        $pantallas->consulta_id = $consulta_id;
        $pantallas->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $pantallas->descripcion = $request->descripcion;
        else
            $pantallas->descripcion = "";    

        $pantallas->activo = 1;
        $pantallas->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existePantallas($consulta_id, $paciente_id){
        $pantallas = DB::table('pantallas')
                                ->where('pantallas.consulta_id', $consulta_id)
                                ->where('pantallas.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('pantallas.activo', 1)
                                           ->orWhere('pantallas.activo', 2);
                                })                                    
                                ->first();
        return $pantallas;
    }

    function guardarHabitos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeHabitos($consulta_id, $paciente_id) == null) { 
            $habitos = new Habitos;
        } else {
            $habitos_aux = $this->existeHabitos($consulta_id, $paciente_id);
            $habitos = Habitos::find($habitos_aux->id);
        }

        $habitos->consulta_id = $consulta_id;
        $habitos->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $habitos->descripcion = $request->descripcion;
        else
            $habitos->descripcion = "";    

        $habitos->activo = 1;
        $habitos->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeHabitos($consulta_id, $paciente_id){
        $habitos = DB::table('habitos')
                                ->where('habitos.consulta_id', $consulta_id)
                                ->where('habitos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('habitos.activo', 1)
                                           ->orWhere('habitos.activo', 2);
                                })                                    
                                ->first();
        return $habitos;
    }

    function guardarDiuresisCatarsis(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeCatarsis($consulta_id, $paciente_id) == null) { 
            $diuresisCatarsis = new Catarsis;
        } else {
            $diuresisCatarsis_aux = $this->existeCatarsis($consulta_id, $paciente_id);
            $diuresisCatarsis = Catarsis::find($diuresisCatarsis_aux->id);
        }

        $diuresisCatarsis->consulta_id = $consulta_id;
        $diuresisCatarsis->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $diuresisCatarsis->descripcion = $request->descripcion;
        else
            $diuresisCatarsis->descripcion = "";    

        $diuresisCatarsis->activo = 1;
        $diuresisCatarsis->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeCatarsis($consulta_id, $paciente_id){
        $catarsis = DB::table('catarses')
                                ->where('catarses.consulta_id', $consulta_id)
                                ->where('catarses.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('catarses.activo', 1)
                                           ->orWhere('catarses.activo', 2);
                                })                                    
                                ->first();
        return $catarsis;
    }

    function guardarSomnia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeSomnia($consulta_id, $paciente_id) == null) { 
            $somnia = new Somnia;
        } else {
            $somnia_aux = $this->existeSomnia($consulta_id, $paciente_id);
            $somnia = Somnia::find($somnia_aux->id);
        }

        $somnia->consulta_id = $consulta_id;
        $somnia->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $somnia->descripcion = $request->descripcion;
        else
            $somnia->descripcion = "";    

        $somnia->activo = 1;
        $somnia->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeSomnia($consulta_id, $paciente_id){
        $somnia = DB::table('somnias')
                        ->where('somnias.consulta_id', $consulta_id)
                        ->where('somnias.paciente_id', $paciente_id)
                        ->where(function ($query) {
                             $query->where('somnias.activo', 1)
                                   ->orWhere('somnias.activo', 2);
                        })                                    
                        ->first();
        return $somnia;
    }

    function guardarMenarca(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeMenarca($paciente_id) == null) { 
            $menarca = new Menarca;
        } else {
            $menarca_aux = $this->existeMenarca($paciente_id);
            $menarca = Menarca::find($menarca_aux->id);
        }

        $menarca->consulta_id = $consulta_id;
        $menarca->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $menarca->descripcion = $request->descripcion;
        else
            $menarca->descripcion = "";    

        $menarca->activo = 1;
        $menarca->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeMenarca($paciente_id){
        $menarca = DB::table('menarcas')                                
                                ->where('menarcas.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('menarcas.activo', 1)
                                           ->orWhere('menarcas.activo', 2);
                                })                                    
                                ->first();
        return $menarca;
    }

    function guardarDatosSubjetivos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeDatosSubjetivos($consulta_id, $paciente_id) == null) { 
            $datosSubjetivos = new DatosSubjetivos;
        } else {
            $conducta_aux = $this->existeDatosSubjetivos($consulta_id, $paciente_id);
            $datosSubjetivos = DatosSubjetivos::find($conducta_aux->id);
        }

        $datosSubjetivos->consulta_id = $consulta_id;
        $datosSubjetivos->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $datosSubjetivos->descripcion = $request->descripcion;
        else
            $datosSubjetivos->descripcion = "";    

        $datosSubjetivos->activo = 1;
        $datosSubjetivos->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeDatosSubjetivos($consulta_id, $paciente_id){
        $datos_subjetivos = DB::table('datos_subjetivos')
                                ->where('datos_subjetivos.consulta_id', $consulta_id)
                                ->where('datos_subjetivos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('datos_subjetivos.activo', 1)
                                           ->orWhere('datos_subjetivos.activo', 2);
                                })                                    
                                ->first();
        return $datos_subjetivos;
    }

    function guardarDatosObjetivos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeDatosObjetivos($consulta_id, $paciente_id) == null) { 
            $datosObjetivos = new DatosObjetivos;
        } else {
            $conducta_aux = $this->existeDatosObjetivos($consulta_id, $paciente_id);
            $datosObjetivos = DatosObjetivos::find($conducta_aux->id);
        }

        $datosObjetivos->consulta_id = $consulta_id;
        $datosObjetivos->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $datosObjetivos->descripcion = $request->descripcion;
        else
            $datosObjetivos->descripcion = "";    

        $datosObjetivos->activo = 1;
        $datosObjetivos->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeDatosObjetivos($consulta_id, $paciente_id){
        $datos_objetivos = DB::table('datos_objetivos')
                                ->where('datos_objetivos.consulta_id', $consulta_id)
                                ->where('datos_objetivos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('datos_objetivos.activo', 1)
                                           ->orWhere('datos_objetivos.activo', 2);
                                })                                    
                                ->first();
        return $datos_objetivos;
    }

    function guardarConductas(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeConducta($consulta_id, $paciente_id) == null) { 
            $conducta = new Conducta;
        } else {
            $conducta_aux = $this->existeConducta($consulta_id, $paciente_id);
            $conducta = Conducta::find($conducta_aux->id);
        }

        $conducta->consulta_id = $consulta_id;
        $conducta->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $conducta->descripcion = $request->descripcion;
        else
            $conducta->descripcion = "";    

        $conducta->activo = 1;
        $conducta->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeConducta($consulta_id, $paciente_id){
        $conductas = DB::table('conductas')
                                ->where('conductas.consulta_id', $consulta_id)
                                ->where('conductas.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('conductas.activo', 1)
                                           ->orWhere('conductas.activo', 2);
                                })                                    
                                ->first();
        return $conductas;
    }

    function guardarObservaciones(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeObservacion($consulta_id, $paciente_id) == null) { 
            $observacion = new Observacion;
        } else {
            $observacion_aux = $this->existeObservacion($consulta_id, $paciente_id);
            $observacion = Observacion::find($observacion_aux->id);
        }

        $observacion->consulta_id = $consulta_id;
        $observacion->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $observacion->descripcion = $request->descripcion;
        else
            $observacion->descripcion = "";    

        $observacion->activo = 1;
        $observacion->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeObservacion($consulta_id, $paciente_id){
        $observaciones = DB::table('observaciones')
                                ->where('observaciones.consulta_id', $consulta_id)
                                ->where('observaciones.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('observaciones.activo', 1)
                                           ->orWhere('observaciones.activo', 2);
                                })                                    
                                ->first();
        return $observaciones;
    }

    function guardarAlimentacion(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeAlimentacion($consulta_id, $paciente_id) == null) { 
            $alimentacion = new Alimentacion;
        } else {
            $alimentacion_aux = $this->existeAlimentacion($consulta_id, $paciente_id);
            $alimentacion = Alimentacion::find($alimentacion_aux->id);
        }

        $alimentacion->consulta_id = $consulta_id;
        $alimentacion->paciente_id = $paciente_id;
        
        $alimentacion->pecho = $request->pecho;
        if($request->pecho_detalle != null)
            $alimentacion->pecho_detalle = $request->pecho_detalle;
        else
            $alimentacion->pecho_detalle = "";

        $alimentacion->leche_maternizada = $request->leche_maternizada;
        if($request->leche_maternizada_detalle != null)
            $alimentacion->leche_maternizada_detalle = $request->leche_maternizada_detalle;
        else
            $alimentacion->leche_maternizada_detalle = "";

        $alimentacion->leche_vaca = $request->leche_vaca;
        if($request->leche_vaca_detalle != null)
            $alimentacion->leche_vaca_detalle = $request->leche_vaca_detalle;
        else
            $alimentacion->leche_vaca_detalle = "";    

        if($request->dieta_tipo != null)
            $alimentacion->dieta_tipo = $request->dieta_tipo;
        else
            $alimentacion->dieta_tipo = "";    

        if($request->dieta_comidas != null)
            $alimentacion->dieta_comidas = $request->dieta_comidas;
        else
            $alimentacion->dieta_comidas = "";    

        $alimentacion->hierro = $request->hierro;
        if($request->hierro_dosis != null)
            $alimentacion->hierro_dosis = $request->hierro_dosis;
        else
            $alimentacion->hierro_dosis = "";    

        $alimentacion->vitamina = $request->vitamina;
        if($request->vitamina_dosis != null)
            $alimentacion->vitamina_dosis = $request->vitamina_dosis;
        else
            $alimentacion->vitamina_dosis = "";    

        if($request->catarsis != null)
            $alimentacion->catarsis = $request->catarsis;
        else
            $alimentacion->catarsis = "";    

        if($request->somnia != null)
            $alimentacion->somnia = $request->somnia;
        else
            $alimentacion->somnia = "";    

        $alimentacion->activo = 1;
        $alimentacion->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeAlimentacion($consulta_id, $paciente_id){
        $alimentacion = DB::table('alimentacions')
                                ->where('alimentacions.consulta_id', $consulta_id)
                                ->where('alimentacions.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('alimentacions.activo', 1)
                                           ->orWhere('alimentacions.activo', 2);
                                })                                    
                                ->first();
        return $alimentacion;
    }

    function guardarExamenFisico(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeExamenFisico($consulta_id, $paciente_id) == null) { 
            $examenFisico = new ExamenFisico;
        } else {
            $examenFisico_aux = $this->existeExamenFisico($consulta_id, $paciente_id);
            $examenFisico = ExamenFisico::find($examenFisico_aux->id);
        }

        $examenFisico->consulta_id = $consulta_id;
        $examenFisico->paciente_id = $paciente_id;
                
        if($request->peso != null)
            $examenFisico->peso = $request->peso;
        else
            $examenFisico->peso = "";

        if($request->peso_percentil != null)
            $examenFisico->peso_percentil = $request->peso_percentil;
        else
            $examenFisico->peso_percentil = "";

        if($request->talla != null)
            $examenFisico->talla = $request->talla;
        else
            $examenFisico->talla = "";

        if($request->talla_percentil != null)
            $examenFisico->talla_percentil = $request->talla_percentil;
        else
            $examenFisico->talla_percentil = "";

          if($request->pc != null)
            $examenFisico->pc = $request->pc;
        else
            $examenFisico->pc = "";

        if($request->pc_percentil != null)
            $examenFisico->pc_percentil = $request->pc_percentil;
        else
            $examenFisico->pc_percentil = "";

        if($request->ipd != null)
            $examenFisico->ipd = $request->ipd;
        else
            $examenFisico->ipd = "";

        if($request->ta != null)
            $examenFisico->ta = $request->ta;
        else
            $examenFisico->ta = "";

        if($request->imc != null)
            $examenFisico->imc = $request->imc;
        else
            $examenFisico->imc = "";

        if($request->imc_percentil != null)
            $examenFisico->imc_percentil = $request->imc_percentil;
        else
            $examenFisico->imc_percentil = "";

        if($request->nota != null)
            $examenFisico->nota = $request->nota;
        else
            $examenFisico->nota = "";

        $examenFisico->activo = 1;
        $examenFisico->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function existeExamenFisico($consulta_id, $paciente_id){
        $examenFisico = DB::table('examen_fisicos')
                                ->where('examen_fisicos.consulta_id', $consulta_id)
                                ->where('examen_fisicos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('examen_fisicos.activo', 1)
                                           ->orWhere('examen_fisicos.activo', 2);
                                })                                    
                                ->first();
        return $examenFisico;
    }

    function existeInterconsulta($paciente, $numero){
        $cantidadInterconsultas = DB::table('interconsultas')                                
                                ->where('interconsultas.paciente_id', $paciente)
                                ->where('interconsultas.numero', $numero)
                                ->where('interconsultas.activo', 1)
                                ->first();    

        return $cantidadInterconsultas;
    }

    function guardarInterconsulta(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $numero = $request->numero;
        if($numero == 0){
            $numero = 1;            
        }
        $numero_consulta_aux = DB::table('interconsultas')                                
                                ->where('interconsultas.paciente_id', $paciente_id)
                                ->where('interconsultas.consulta_id', $consulta_id)
                                ->where('interconsultas.activo', 2)
                                ->get();
        $numero_consulta = $numero_consulta_aux->count()+1;

        $interconsultas_aux = $this->existeInterconsulta($paciente_id, $numero);
        if($interconsultas_aux != null){
            $interconsulta = Interconsulta::find($interconsultas_aux->id);
            if($request->respuesta != null) {
                $interconsulta->respuesta = $request->respuesta;
                $interconsulta->consulta_respuesta = $consulta_id;
            } else {
                $interconsulta->respuesta = "";
                $interconsulta->consulta_respuesta = 0;
            }
            $interconsulta->save();
        } else {
            $interconsulta = new Interconsulta;
            $interconsulta->consulta_id = $consulta_id;
            $interconsulta->paciente_id = $paciente_id;        
            $interconsulta->numero = $numero;
            $interconsulta->numero_consulta = $numero_consulta;

            if($request->especialista != null)
                $interconsulta->especialista = $request->especialista;
            else
                $interconsulta->especialista = "";

            if($request->solicito != null)
                $interconsulta->solicito = $request->solicito;
            else
                $interconsulta->solicito = "";

            if($request->respuesta != null) {
                $interconsulta->respuesta = $request->respuesta;
                $interconsulta->consulta_respuesta = $consulta_id;
            } else {
                $interconsulta->respuesta = "";
                $interconsulta->consulta_respuesta = 0;
            }

            if($request->fechaSolicitud != null)
                $interconsulta->fechaSolicitud = $this->convertirFechaGuardar($request->fechaSolicitud);
            else
                $interconsulta->fechaSolicitud = "";

            $interconsulta->activo = 2;
            $interconsulta->save();
        }

        $cantidadInterconsultas_aux = DB::table('interconsultas')                                
                                ->where('interconsultas.paciente_id', $paciente_id)
                                ->where('interconsultas.activo', 1)
                                ->get();            
        $cantidadInterconsultas_actual_aux = DB::table('interconsultas')                                
                                ->where('interconsultas.consulta_id', $consulta_id)
                                ->where('interconsultas.activo', 2)
                                ->get();
        $cantidadInterconsultas = $cantidadInterconsultas_aux->count() + $cantidadInterconsultas_actual_aux->count();                        
        
        return response()->json(array('response'=>1, 'cantidadInterconsultas' => $cantidadInterconsultas,'numero'=>$numero));
    }

    function cargarInterconsulta(Request $request){
        $paciente_id = $request->paciente;
        $consulta_id = $request->consulta;
        $consulta_actual = $request->consulta_actual;
        $numero = $request->numero;
        if($numero < 1){
            $numero = 1;
        }
        if($consulta_id == 1) {
            $cantidadInterconsultas_aux = DB::table('interconsultas')                                
                                ->where('interconsultas.paciente_id', $paciente_id)
                                //->where('interconsultas.consulta_id', $consulta_id)
                                ->orWhere('interconsultas.consulta_id', $consulta_actual)
                                ->where('interconsultas.activo', 1)                                
                                ->get();    
            if($cantidadInterconsultas_aux->count()>0) {
                $tope = $cantidadInterconsultas_aux->count();
            } else {
                $tope = 0;
            }
            //1 > 1
            if($numero > $tope - 1){ 
                $cantidadInterconsultas = $tope;
                $response_data = $cantidadInterconsultas_aux[$cantidadInterconsultas - 1];
            } else {
                $cantidadInterconsultas = $numero;
                $response_data = $cantidadInterconsultas_aux[$cantidadInterconsultas - 1];
            }                  
        } else { 
        // en caso de que sea una consulta entro por aca           
            $cantidadInterconsultas_aux = DB::table('interconsultas')                                
                                ->where('interconsultas.paciente_id', $paciente_id)
                                ->Where('interconsultas.consulta_id', '<=',$consulta_actual)
                                ->where('interconsultas.activo', 1)
                                ->get();    
            if($cantidadInterconsultas_aux->count() > 0) {
                $tope = $cantidadInterconsultas_aux->count();
                $cantidadInterconsultas = $cantidadInterconsultas_aux->count();
                if($numero>$cantidadInterconsultas)
                    $numero = $cantidadInterconsultas;

                $response_data = DB::table('interconsultas')                                
                                            ->where('interconsultas.paciente_id', $paciente_id)
                                            ->where('interconsultas.numero', $numero)
                                            ->where('interconsultas.activo', 1)                                        
                                            ->first();
                if($response_data == null){
                    $response_data = DB::table('interconsultas')                                
                                            ->where('interconsultas.paciente_id', $paciente_id)
                                            ->where('interconsultas.numero', $numero)
                                            ->where('interconsultas.consulta_id', $consulta_actual)                                        
                                            ->first();
                }
            } else {
                $tope = 0;
            }              
        }
        return response()->json(array('response'=>1, 'tope'=>$tope ,'numero' => $cantidadInterconsultas,'interconsulta'=>$response_data));
    }

    function cargarNumeroInterconsulta(Request $request) {
        $paciente_id = $request->paciente;
        $consulta_id = $request->consulta;
        $consulta_actual = $request->consulta_actual;
        if($consulta_id != null) {
            $interconsultas_aux = DB::table('interconsultas')                                
                                        ->where('interconsultas.paciente_id', $paciente_id)
                                        ->where('interconsultas.consulta_id','<=', $consulta_id)
                                        //->orWhere('interconsultas.consulta_respuesta', $consulta_id)
                                        ->where('interconsultas.activo', 1)
                                        ->orderby('interconsultas.id', 'desc') // con esto obtengo la ultima                                        
                                        ->first();
            if($interconsultas_aux != null){
                $numero = $interconsultas_aux->numero;
               // $interconsultas_aux = $interconsultas_aux[$numero-1];
                $response = 1;
            } else {
                $numero = 0;
                $response = 0;
            }
        } else {
            $interconsultas_aux = DB::table('interconsultas')                                
                                        ->where('interconsultas.paciente_id', $paciente_id)
                                        ->orWhere('interconsultas.consulta_id', $consulta_actual)
                                        ->where('interconsultas.activo', 1)
                                        ->orderby('interconsultas.numero', 'desc')
                                        ->first();
            if($interconsultas_aux != null){
                $response = 1;
                $numero = $interconsultas_aux->numero;
            } else {
                $response = 0;
                $numero = 0;
            }
        }

        return response()->json(array('response'=> $response, 'numero' => $numero, 'interconsulta' =>$interconsultas_aux,'request'=>$request));
    }

    // input : 15/05/2020 return 2020-05-15
    function convertirFechaGuardar($fecha){
        $fecha_aux = explode('/',$fecha);
        $_fecha = $fecha_aux[2].'-'.$fecha_aux[1].'-'.$fecha_aux[0];
        return $_fecha;
    }

    // input 2020-05-15 return 15/05/2020
    function convertirFechaMostrar($fecha){
        $fecha_aux = explode('-',$fecha);
        $_fecha = $fecha_aux[2].'/'.$fecha_aux[1].'/'.$fecha_aux[0];
        return $_fecha;
    }

    function setActivoExamenesComplementarios($consulta_id){
        $response = DB::table('examenes_complementarios')                                                        
                        ->where('examenes_complementarios.consulta_id', $consulta_id)       
                        ->get();
        foreach ($response as $value) {
            $examenComplementarioAux = ExamenesComplementarios::find($value->id);
            $examenComplementarioAux->activo = 1;
            $examenComplementarioAux->save();
        }        
    }

    function setActivoInterconsulta($consulta_id){
        $response = DB::table('interconsultas')                                                        
                        ->where('interconsultas.consulta_id', $consulta_id)                                                            
                        ->get();
        foreach ($response as $value) {
            $interconsultaAux = Interconsulta::find($value->id);
            $interconsultaAux->activo = 1;
            $interconsultaAux->save();
        }        
    }

    function guardarExamenesComplementarios(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $mostrarSnack = $request->mostrarSnack;
        $examenes_complementarios_id = $request->ex_compl_id;
        $numero = $request->ex_compl_numero;
        if($numero == 0) {
            $numero = 1;            
        }

        $numero_consulta_aux = DB::table('examenes_complementarios')                                
                                        ->where('examenes_complementarios.paciente_id', $paciente_id)
                                        ->where('examenes_complementarios.consulta_id', $consulta_id)
                                        ->where('examenes_complementarios.activo', 2)                                        
                                        ->get();
        $numero_consulta = $numero_consulta_aux->count() + 1;

        if(($examenes_complementarios_id == 0) || ($examenes_complementarios_id == -1)) {
            $examenesComplementarios = new ExamenesComplementarios;
            $examenesComplementarios->consulta_id = $consulta_id;
            $examenesComplementarios->paciente_id = $paciente_id;

            if($request->solicito != null)
                $examenesComplementarios->solicito = $request->solicito;
            else
                $examenesComplementarios->solicito = "";

            if($request->respuesta != null && strcmp($request->respuesta, '') != 0){
                $examenesComplementarios->respuesta = $request->respuesta;
                $examenesComplementarios->consulta_respuesta = $consulta_id;
            } else {
                $examenesComplementarios->respuesta = "";
                $examenesComplementarios->consulta_respuesta = 0;
            }

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date("Y-m-d");                         
            $examenesComplementarios->fechaSolicitud = $fecha;            
            
            $examenesComplementarios->numero_consulta = $numero_consulta;    
            $examenesComplementarios->numero = $numero;
            $examenesComplementarios->activo = 2;

        } else {
            $examenesComplementarios = ExamenesComplementarios::find($examenes_complementarios_id);
            if($request->solicito != null)
                $examenesComplementarios->solicito = $request->solicito;
            else
                $examenesComplementarios->solicito = "";

            if($request->respuesta != null && strcmp($request->respuesta, '') != 0){
                $examenesComplementarios->respuesta = $request->respuesta;
                $examenesComplementarios->consulta_respuesta = $consulta_id;
            } else {
                $examenesComplementarios->respuesta = "";
                $examenesComplementarios->consulta_respuesta = 0;
            }
        }
        $examenesComplementarios->save();
        
        $examenComplementario_aux = DB::table('examenes_complementarios')                                
                                    ->where('examenes_complementarios.paciente_id', $request->paciente)
                                    ->where('examenes_complementarios.activo', 1)                                                   
                                    ->get();
        $examenComplementario_aux_2 = DB::table('examenes_complementarios')                                
                                    ->where('examenes_complementarios.consulta_id', $consulta_id)                           
                                    ->get();                                    
        $numero_consulta_tope = $examenComplementario_aux_2->count() + $examenComplementario_aux->count();
        
        return response()->json(array('response'=>1, 'examenesComplementarios' =>$examenesComplementarios, 'mostrarSnack'=>$mostrarSnack, 'numero_consulta_tope'=>$numero_consulta_tope));
    }

    function cargarExamenesComplementarios(Request $request){
        $paciente_id = $request->paciente;
        $consulta_id = $request->consulta;  
        $consulta_actual = $request->consulta_actual;      
        if($consulta_id != null) {
            $examenes_complementarios_aux = DB::table('examenes_complementarios')                                
                                        ->where('examenes_complementarios.paciente_id', $paciente_id)                                        
                                        ->where('examenes_complementarios.consulta_id', '<=', $consulta_id)
                                        //->orWhere('examenes_complementarios.consulta_respuesta', $consulta_id)
                                        ->where('examenes_complementarios.activo', 1)
                                        ->orderby('examenes_complementarios.id', 'desc') // con esto obtengo la ultima
                                        ->first();
            if($examenes_complementarios_aux != null){
                $numero = $examenes_complementarios_aux->numero;
               // $examenes_complementarios_aux = $examenes_complementarios_aux[$numero-1];
                $examenes_complementarios_fotos_aux = DB::table('examenes_complementarios_fotos')                                
                                        ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                        ->where('examenes_complementarios_fotos.consulta_id', $consulta_id)
                                        ->where('examenes_complementarios_fotos.examen_complementario_id', $examenes_complementarios_aux->id)
                                        ->where('examenes_complementarios_fotos.activo', 1)                                        
                                        ->get();
                $numero_fotos = $examenes_complementarios_fotos_aux->count();
                $response = 1;
            } else {
                $numero = 0;
                $response = 0;
                $numero_fotos = 0;
            }            
        } else {     
            $examenes_complementarios_aux = DB::table('examenes_complementarios')                                
                                        ->where('examenes_complementarios.paciente_id', $paciente_id)
                                        ->where('examenes_complementarios.activo', 1)
                                        ->orWhere('examenes_complementarios.consulta_id', $consulta_actual)
                                        ->orderby('examenes_complementarios.numero', 'desc')
                                        ->first();
        
            if($examenes_complementarios_aux != null){
                $numero = $examenes_complementarios_aux->numero;
                $examenes_complementarios_fotos_aux = DB::table('examenes_complementarios_fotos')                                
                                        ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                        ->where('examenes_complementarios_fotos.examen_complementario_id', $examenes_complementarios_aux->id)
                                        ->where('examenes_complementarios_fotos.activo', 1)                                        
                                        ->get();
                $numero_fotos = $examenes_complementarios_fotos_aux->count();
                $response = 1;
            } else {
                $numero = 0;
                $numero_fotos = 0;
                $response = 0;
            }
        }
        return response()->json(array('response'=>$response, 'numero' => $numero,'examenes_complementarios' =>$examenes_complementarios_aux,'request'=>$request, 'numero_fotos'=>$numero_fotos));   
    }

    function cargarExamenesComplementariosAntSigFoto(Request $request){
        $paciente_id = $request->paciente;
        $ex_compl_id = $request->ex_compl_id;
        $numero = $request->numero;
        if($numero < 1){
            $numero = 1;
        }
        $cantidadExamenesComplementariosFoto_aux = DB::table('examenes_complementarios_fotos')                                
                                ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)                                
                                ->where('examenes_complementarios_fotos.activo', 1)
                                ->get();    
        $cantidadExamenesComplementariosFotos = $cantidadExamenesComplementariosFoto_aux->count();
        if($numero>$cantidadExamenesComplementariosFotos)
            $numero = $cantidadExamenesComplementariosFotos;

        $response_data = DB::table('examenes_complementarios_fotos')                                
                                        ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                        ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                        ->where('examenes_complementarios_fotos.numero', $numero)
                                        ->where('examenes_complementarios_fotos.activo', 1)                                        
                                        ->first();

        return response()->json(array('response'=>1, 'tope' => $cantidadExamenesComplementariosFotos,'examenComplementarioFoto'=>$response_data));
    }

    function guardarExamenesComplementariosFotos(Request $request){
        $consulta_id = $request->examenes_complementarios_consulta_id;
        $paciente_id = $request->examenes_complementarios_paciente_id;
        $examenes_complementarios_id = $request->examenes_complementarios_id;     
        $es_nueva_consulta_cargar_fotos = $request->es_nueva_consulta_cargar_fotos;   
        if($examenes_complementarios_id == -1 || $examenes_complementarios_id == 0){
            $examenesComplementarios = new ExamenesComplementarios;
            $examenesComplementarios->consulta_id = $request->examenes_complementarios_consulta_id;
            $examenesComplementarios->paciente_id = $request->examenes_complementarios_paciente_id;

            if($request->examenes_complementarios_solicito != null)
                $examenesComplementarios->solicito = $request->examenes_complementarios_solicito;
            else
                $examenesComplementarios->solicito = "";

            if($request->examenes_complementarios_respuesta != null)
                $examenesComplementarios->respuesta = $request->examenes_complementarios_respuesta;
            else
                $examenesComplementarios->respuesta = "";

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fecha = date("Y-m-d");                         
            $examenesComplementarios->fechaSolicitud = $fecha;            
            
            $examenesComplementarios->numero_consulta = 1;    
            $examenesComplementarios->numero = 1;
            $examenesComplementarios->activo = 1;
            $examenesComplementarios->save();
        } else {
            $examenesComplementarios = ExamenesComplementarios::find($examenes_complementarios_id); 
        }
 
        $cantidadFotos = intval($request->ex_comp_cantidad_fotos);
        $usuario_actual_nombre=\Auth::user()->email;   
        $pathFolderMedico_aux = explode('@', $usuario_actual_nombre);
        $pathFolderMedico = $pathFolderMedico_aux[0];
        
        for ($i = 1; $i <= $cantidadFotos; $i++) {
            $request_foto = 'ex_comp_foto_'.$i;

            if($request->$request_foto != null){
                
                if($request->hasfile($request_foto)){
                    $examenesComplementariosFotos = new ExamenesComplementariosFotos;
                    $examenesComplementariosFotos->activo = 1;
                    $examenesComplementariosFotos->paciente_id = $paciente_id;
                    $examenesComplementariosFotos->consulta_id = $consulta_id;
                    $examenesComplementariosFotos->numero = $this->getNumeroExamenComplementarioFoto($paciente_id, $examenesComplementarios->id);
                    if($request->foto_ex_complementario_id == 0)
                        $examenesComplementariosFotos->examen_complementario_id = $examenesComplementarios->id;
                    else
                        $examenesComplementariosFotos->examen_complementario_id = $request->foto_ex_complementario_id;

                    $pathName = $request->file($request_foto)->store('img/'.$pathFolderMedico.'/examenes_complementarios');
                    $name = collect(explode('/', $pathName))->last();
                    $image = $request->file($request_foto);
                    //$name  = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
                    $path = 'img/'.$pathFolderMedico.'/examenes_complementarios/'.$name;        
                    Image::make($image->getRealPath())->resize(1980, 1920)->save($path);  
                    $examenesComplementariosFotos->foto = $pathFolderMedico.'/examenes_complementarios/'.$name;
                    $examenesComplementariosFotos->save();           
                }               
            } 
        }

        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        if($es_nueva_consulta_cargar_fotos==null)
            $medicoInfo->es_nueva_consulta = 3;
        else
            $medicoInfo->es_nueva_consulta = 4;
        $medicoInfo->save();
        
        return redirect()->route('navegarconsultaseleccionada');
        
    }

    function getNumeroExamenComplementarioFoto($paciente_id, $examenComplementarioId){
        $examenComplementario_aux = DB::table('examenes_complementarios_fotos')
                                ->where('examenes_complementarios_fotos.examen_complementario_id', $examenComplementarioId)
                                ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('examenes_complementarios_fotos.activo', 1)
                                           ->orWhere('examenes_complementarios_fotos.activo', 2);
                                })                                                                
                                ->get();  
        return $examenComplementario_aux->count()+1;
    }

    function getExamenesComplementariosUltimo(Request $request){        
         $examenComplementario = DB::table('examenes_complementarios')
                                ->where('examenes_complementarios.consulta_id', $request->consulta)
                                ->where('examenes_complementarios.paciente_id', $request->paciente)
                                ->where(function ($query) {
                                     $query->where('examenes_complementarios.activo', 1)
                                           ->orWhere('examenes_complementarios.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();
        return response()->json(array('response'=>1, 'examenComplementario'=>$examenComplementario));
    }

    function cargarExamenesComplementariosAntSig(Request $request){
        $paciente_id = $request->paciente;
        $numero = $request->numero;
        $consulta_id = $request->consulta;
        $ex_compl_id = $request->ex_compl_id;
        $consultaActual = $request->consultaActual;
        if($numero < 1){
            $numero = 1;
        }
        if($consulta_id == 1) {
        //if(strcmp($consulta_id, '-1') != 0) {
            $cantidadExamenesComplementarios_aux = DB::table('examenes_complementarios')                                
                                    ->where('examenes_complementarios.paciente_id', $paciente_id)
                                    ->where('examenes_complementarios.activo', 1)
                                    ->orWhere('examenes_complementarios.consulta_id', $consultaActual)                                    
                                    ->get();
            if($cantidadExamenesComplementarios_aux->count()>0) {
                $tope = $cantidadExamenesComplementarios_aux->count();
            } else {
                $tope = 0;
            }                     
            if($numero > $tope - 1){ 
                $cantidadExamenesComplementarios = $tope;
                $response_data = $cantidadExamenesComplementarios_aux[$cantidadExamenesComplementarios - 1];
            } else {
                $cantidadExamenesComplementarios = $numero;
                $response_data = $cantidadExamenesComplementarios_aux[$cantidadExamenesComplementarios - 1];
            }

            $cantidadExamenesComplementariosFoto_aux = DB::table('examenes_complementarios_fotos')                                
                                    ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                    //->where('examenes_complementarios_fotos.consulta_id', $consulta_id)                                
                                    ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)                                
                                    ->where('examenes_complementarios_fotos.activo', 1)
                                    ->get();    
            $cantidadExamenesComplementariosFotos = $cantidadExamenesComplementariosFoto_aux->count();        
            $numero_foto = $cantidadExamenesComplementariosFotos;

            $response_data_foto = DB::table('examenes_complementarios_fotos')                                
                                            ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                            //->where('examenes_complementarios_fotos.consulta_id', $consulta_id)
                                            ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                            ->where('examenes_complementarios_fotos.numero', $numero_foto)
                                            ->where('examenes_complementarios_fotos.activo', 1)                                        
                                            ->first();   
            // $tope = 50;         
        } else {
               // en caso de que sea una consulta entro por aca   
            $cantidadExamenesComplementarios_aux = DB::table('examenes_complementarios')                                
                                ->where('examenes_complementarios.paciente_id', $paciente_id)
                                ->where('examenes_complementarios.activo', 1)
                                ->where('examenes_complementarios.consulta_id', '<=', $consultaActual)
                                ->get();    
            $cantidadExamenesComplementarios = $cantidadExamenesComplementarios_aux->count();
            $tope = $cantidadExamenesComplementarios; 
            // tengo dos opciones, o es consulta actual y tiene activo = 2 o es una consulta vieja con activo = 1
            $consultaActualExComp = DB::table('examenes_complementarios')                                
                                    ->where('examenes_complementarios.paciente_id', $paciente_id)                                    
                                    ->where('examenes_complementarios.numero', $numero)
                                    ->where('examenes_complementarios.consulta_id', $consultaActual)
                                    ->first();    
            if($consultaActualExComp != null) {
                // quiere decir que es consulta actual 
                $response_data = $consultaActualExComp;               
               // $cantidadExamenesComplementarios = $consultaActualExComp->numero;
            } else {       
                if($numero>$cantidadExamenesComplementarios)
                    $numero = $cantidadExamenesComplementarios;

                $response_data = DB::table('examenes_complementarios')                                
                                                ->where('examenes_complementarios.paciente_id', $paciente_id)
                                                ->where('examenes_complementarios.numero', $numero)
                                                ->where('examenes_complementarios.activo', 1)                                        
                                                ->first();
            }
            
            $cantidadExamenesComplementariosFoto_aux = DB::table('examenes_complementarios_fotos')                                
                                    ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                    ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)                                
                                    ->where('examenes_complementarios_fotos.activo', 1)
                                    ->get();    
            $cantidadExamenesComplementariosFotos = $cantidadExamenesComplementariosFoto_aux->count();        
            $numero_foto = $cantidadExamenesComplementariosFotos;

            $response_data_foto = DB::table('examenes_complementarios_fotos')                                
                                            ->where('examenes_complementarios_fotos.paciente_id', $paciente_id)
                                            ->where('examenes_complementarios_fotos.examen_complementario_id', $ex_compl_id)
                                            ->where('examenes_complementarios_fotos.numero', $numero_foto)
                                            ->where('examenes_complementarios_fotos.activo', 1)                                        
                                            ->first();
           // $tope = 30;
        }
        
        return response()->json(array('response'=>1, 'numero' => $cantidadExamenesComplementarios,'examenesComplementarios'=>$response_data, 'examenComplementarioFoto'=>$response_data_foto, 'numero_foto'=>$numero_foto, 'tope' => $tope));
    }

    function nuevoExamenComplementario(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        $examenComplementario_aux = DB::table('examenes_complementarios')                                
                                ->where('examenes_complementarios.paciente_id', $request->paciente)                                
                                ->where('examenes_complementarios.activo', 1)                                                   
                                ->get();        
        $exaComp_aux = DB::table('examenes_complementarios')                                
                                ->where('examenes_complementarios.paciente_id', $request->paciente)                                
                                ->where('examenes_complementarios.consulta_id', $consulta_id)                                                   
                                ->get();                        
        $examenesComplementarios = $exaComp_aux->count() + $examenComplementario_aux->count() + 1;       

        return response()->json(array('response'=>1, 'examenComplementario'=>$examenesComplementarios));
    }

    function getFotosExamenesComplementarios(Request $request) {       
        $fotosExamenComplementario = DB::table('examenes_complementarios_fotos')                                
                                ->where('examenes_complementarios_fotos.paciente_id', $request->paciente)
                                ->where('examenes_complementarios_fotos.examen_complementario_id', $request->ex_compl_id)
                                ->where(function ($query) {
                                     $query->where('examenes_complementarios_fotos.activo', 1)
                                           ->orWhere('examenes_complementarios_fotos.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();
        return response()->json(array('response'=>1, 'fotosExamenComplementario'=>$fotosExamenComplementario));       
    }

    function getFotosAntecedentesNeonantales(Request $request){
        $fotos_aux = DB::table('antecedentes_neonatales_fotos')                                
                                ->where('antecedentes_neonatales_fotos.paciente_id', $request->paciente)                                
                                ->where(function ($query) {
                                     $query->where('antecedentes_neonatales_fotos.activo', 1)
                                           ->orWhere('antecedentes_neonatales_fotos.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();
        return response()->json(array('response'=>1, 'fotosAntecedentesNeonatales'=>$fotos_aux));       
    }

    function guardarNotaAntecedentesNeonatalesPatologicos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        if($this->existeAntecedentesNeonantales($consulta_id, $paciente_id) == null) { 
            $antecedentesNeonatales = new AntecedentesNeonatales;
        } else {
            $antecedentesNeonatales_aux = $this->existeAntecedentesNeonantales($consulta_id, $paciente_id);
            $antecedentesNeonatales = AntecedentesNeonatales::find($antecedentesNeonatales_aux->id);
        }
        $antecedentesNeonatales->consulta_id = $consulta_id;
        $antecedentesNeonatales->paciente_id = $paciente_id;

        if($request->nota != null)
            $antecedentesNeonatales->nota = $request->nota;
        else
            $antecedentesNeonatales->nota = "";

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");                         
        $antecedentesNeonatales->fechaSolicitud = $fecha;
        $antecedentesNeonatales->activo = 1;
        $antecedentesNeonatales->save();
        
        return response()->json(array('response'=>1, 'antecedentesNeonatales'=>$antecedentesNeonatales));   
    }

    function existeAntecedentesNeonantales($consulta_id, $paciente_id){
        $antecedentesNeonatales = DB::table('antecedentes_neonatales')
                                ->where('antecedentes_neonatales.consulta_id', $consulta_id)
                                ->where('antecedentes_neonatales.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('antecedentes_neonatales.activo', 1)
                                           ->orWhere('antecedentes_neonatales.activo', 2);
                                })                                    
                                ->first();
        return $antecedentesNeonatales;
    }

    function getNumeroFotosAntecedentesNeonantales($paciente_id){
        $antecedentesNeonatalesFotos = DB::table('antecedentes_neonatales_fotos')                                
                                ->where('antecedentes_neonatales_fotos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('antecedentes_neonatales_fotos.activo', 1)
                                           ->orWhere('antecedentes_neonatales_fotos.activo', 2);
                                })                                    
                                ->get();
        return $antecedentesNeonatalesFotos->count()+1;
    }

    function guardarFamiligrama(Request $request){        
        $paciente_id = $request->paciente_id_fm;
        $es_nueva_consulta_cargar_fotos_fg = $request->es_nueva_consulta_cargar_fotos_fg;    
        
        $usuario_actual_nombre=\Auth::user()->email;   
        $pathFolderMedico_aux = explode('@', $usuario_actual_nombre);
        $pathFolderMedico = $pathFolderMedico_aux[0];
        
        $request_foto = 'familigrama_foto_1';

        if($request->$request_foto != null){
            
            if($request->hasfile($request_foto)){
                $familigrama = new Familigrama;
                $familigrama->activo = 1;
                $familigrama->paciente_id = $paciente_id;                                                

                $pathName = $request->file($request_foto)->store('img/'.$pathFolderMedico.'familigrama');
                $name = collect(explode('/', $pathName))->last();
                $image = $request->file($request_foto);
                //$name  = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
                $path = 'img/'.$pathFolderMedico.'/familigrama/'.$name;        
                Image::make($image->getRealPath())->resize(1980, 1920)->save($path);  
                $familigrama->foto = $pathFolderMedico.'/familigrama/'.$name;
                $familigrama->save();           
            }               
        } 
        
        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        if($es_nueva_consulta_cargar_fotos_fg == 3)
            $medicoInfo->es_nueva_consulta = 3;
        else
            $medicoInfo->es_nueva_consulta = 4;
        $medicoInfo->save();

        return redirect()->route('navegarconsultaseleccionada');
    }

    function cargarFotoFamiligrama(Request $request){
        $fotos_aux = DB::table('familigramas')                                
                                ->where('familigramas.paciente_id', $request->paciente)                                
                                ->where(function ($query) {
                                     $query->where('familigramas.activo', 1)
                                           ->orWhere('familigramas.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();
        return response()->json(array('response'=>1, 'familigrama'=>$fotos_aux));       
    }

    function guardarAntecedentesNeonatales(Request $request){
        $consulta_id = $request->antecedentes_neonatales_consulta_id;
        $paciente_id = $request->antecedentes_neonatales_paciente_id;
        $es_nueva_consulta_cargar_fotos_an = $request->es_nueva_consulta_cargar_fotos_an;
        
        if($this->existeAntecedentesNeonantales($consulta_id, $paciente_id) == null) { 
            $antecedentesNeonatales = new AntecedentesNeonatales;
        } else {
            $antecedentesNeonatales_aux = $this->existeAntecedentesNeonantales($consulta_id, $paciente_id);
            $antecedentesNeonatales = AntecedentesNeonatales::find($antecedentesNeonatales_aux->id);
        }
        $antecedentesNeonatales->consulta_id = $consulta_id;
        $antecedentesNeonatales->paciente_id = $paciente_id;

        if($request->antecedentes_neonatales_patologicos_nota != null)
            $antecedentesNeonatales->nota = $request->antecedentes_neonatales_patologicos_nota;
        else
            $antecedentesNeonatales->nota = "";

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");                         
        $antecedentesNeonatales->fechaSolicitud = $fecha;
        $antecedentesNeonatales->activo = 1;
        $antecedentesNeonatales->save();

        $cantidadFotos = intval($request->ant_neonantales_cantidad_fotos);
        $usuario_actual_nombre=\Auth::user()->email;   
        $pathFolderMedico_aux = explode('@', $usuario_actual_nombre);
        $pathFolderMedico = $pathFolderMedico_aux[0];
        for ($i = 1; $i <= $cantidadFotos; $i++) {
            $request_foto = 'ant_neonatales_foto_'.$i;

            if($request->$request_foto != null){
                
                if($request->hasfile($request_foto)){
                    $antecedentesNeonatalesFotos = new AntecedentesNeonatalesFotos;
                    $antecedentesNeonatalesFotos->activo = 1;
                    $antecedentesNeonatalesFotos->paciente_id = $paciente_id;
                    $antecedentesNeonatalesFotos->consulta_id = $consulta_id;
                    $antecedentesNeonatalesFotos->numero = $this->getNumeroFotosAntecedentesNeonantales($paciente_id);
                    $antecedentesNeonatalesFotos->antecedentes_neonatales_id = $antecedentesNeonatales->id;

                    $pathName = $request->file($request_foto)->store('img/'.$pathFolderMedico.'antecedentes_neonatales');
                    $name = collect(explode('/', $pathName))->last();
                    $image = $request->file($request_foto);
                    //$name  = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
                    $path = 'img/'.$pathFolderMedico.'/antecedentes_neonatales/'.$name;        
                    Image::make($image->getRealPath())->resize(1980, 1920)->save($path);  
                    $antecedentesNeonatalesFotos->foto = $pathFolderMedico.'/antecedentes_neonatales/'.$name;
                    $antecedentesNeonatalesFotos->save();           
                }               
            } 
        }

        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        if($es_nueva_consulta_cargar_fotos_an==null)
            $medicoInfo->es_nueva_consulta = 3;
        else
            $medicoInfo->es_nueva_consulta = 4;
        $medicoInfo->save();

        return redirect()->route('navegarconsultaseleccionada');
    }

    function cargarAntecedentesNeonatalesAntSigFoto(Request $request){
        $paciente_id = $request->paciente;        
        $numero = $request->numero;
        if($numero < 1){
            $numero = 1;
        }
        $cantidad_fotos_aux = DB::table('antecedentes_neonatales_fotos')                                
                                ->where('antecedentes_neonatales_fotos.paciente_id', $paciente_id)                                
                                ->where('antecedentes_neonatales_fotos.activo', 1)
                                ->get();    
        $cantidadFotos = $cantidad_fotos_aux->count();
        if($numero>$cantidadFotos)
            $numero = $cantidadFotos;

        $response_data = DB::table('antecedentes_neonatales_fotos')                                
                                        ->where('antecedentes_neonatales_fotos.paciente_id', $paciente_id)                                        
                                        ->where('antecedentes_neonatales_fotos.numero', $numero)
                                        ->where('antecedentes_neonatales_fotos.activo', 1)                                        
                                        ->first();

        return response()->json(array('response'=>1, 'cantidadFotos' => $cantidadFotos,'response_data'=>$response_data));
    }

    function consultarPaciente() {
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();         
        $listadoConsultas = DB::table('consultas')
                            ->select('consultas.*')
                            ->join('medico_pacientes', 'medico_pacientes.paciente_id', 'consultas.paciente_id')
                            ->where('medico_pacientes.medico_user_id', $user->id)
                            ->where('consultas.medico_id', $user->id)
                            ->where('consultas.paciente_id', $medicoInfoAux->paciente_id)
                            ->where('consultas.activo', 1)
                            ->distinct()
                            ->orderby('consultas.id','desc')
                            ->get();    
        
        $paciente = Paciente::find($medicoInfoAux->paciente_id);    
        $fecha_nacimiento_aux = explode('-', $paciente->fecha_nacimiento);
        $fecha_nacimiento = $fecha_nacimiento_aux[2].'/'.$fecha_nacimiento_aux[1].'/'.$fecha_nacimiento_aux[0];
        $moduloVacunas = $this->getModuloVacuna($user->id);
        
        $consulta = null;
        $response = 0; 
        
        if($listadoConsultas->count() > 0){
            $consulta = $listadoConsultas[0];
            $response = 1; 
        }
        if($response == 1){
            return view('medico.consultas_paciente')
                        ->with('response', $response)                
                        ->with('nueva_consulta', 0)                
                        ->with('consulta', $consulta)                
                        ->with('paciente', $paciente)
                        ->with('moduloVacunas', $moduloVacunas)
                        ->with('fecha_nacimiento', $fecha_nacimiento)                
                        ->with('listadoConsultas', $listadoConsultas);                         
        } else {
            $mensaje = "El paciente no tiene consultas.";
            return view('medico.mostrar_mensaje')
                        ->with('mensaje', $mensaje);
        }
    }

    function crearNuevaConsulta(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_foto = $request->consulta_foto;
        $tipo_consulta = $request->tipo_consulta;

        $paciente = Paciente::find($paciente_id);
       /* if($paciente != null) {
            $consultaAbierta = $this->checkConsultaAbierta($paciente_id);
           // $consultaAbierta = null;
            if( $consultaAbierta == null){
                $consulta = new Consulta;
                $consulta->paciente_id = $paciente->id;
                $consulta->tipo_consulta = $tipo_consulta;
                $consulta->es_foto = $consulta_foto;
                $consulta->edad_paciente = 0;
                $consulta->activo = 2;
                $consulta->save();
            } else {
                $consulta = $consultaAbierta;
            }
        } else {
            $consulta = null;
        }*/
        $user=\Auth::user(); 
        $consulta = new Consulta;
                $consulta->paciente_id = $paciente->id;
                $consulta->medico_id = $user->id;
                $consulta->tipo_consulta = $tipo_consulta;
                $consulta->es_foto = $consulta_foto;
                $consulta->edad_paciente = 0;
                $consulta->edad_mostrar = '';
                $consulta->activo = 2;
                $consulta->save();

                       
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        if($medicoInfoAux != null){
            $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->consulta_id = $consulta->id;
            $medicoInfo->es_nueva_consulta = 1;
            $medicoInfo->save();
        } else {
            $medicoInfo = new MedicoInfo;
            $medicoInfo->medico_user_id = $user->id;
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->es_nueva_consulta = 1;
            $medicoInfo->consulta_id = $consulta->id;
            $medicoInfo-> save();
        }     

        return redirect()->route('navegarconsultaseleccionada');
        //return response()->json(array('response'=>1, 'paciente_id'=>$paciente_id, 'consulta_id'=>$consulta->id));
       
    }

    function crearNuevaConsultaFoto() {
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        
        $paciente = Paciente::find($medicoInfoAux->paciente_id);
        $consulta = new Consulta;
                $consulta->paciente_id = $paciente->id;
                $consulta->medico_id = $user->id;
                $consulta->tipo_consulta = 3;
                $consulta->es_foto = 1;
                $consulta->edad_paciente = 0;
                $consulta->edad_mostrar = '';
                $consulta->activo = 2;
                $consulta->save();        

        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        $medicoInfo->paciente_id = $paciente->id;
        $medicoInfo->consulta_id = $consulta->id;
        $medicoInfo->es_nueva_consulta = 1;
        $medicoInfo->save();

        return redirect()->route('navegarconsultaseleccionada');
    }

    function pacienteConsultas($paciente_id){
         return view('medico.seleccionar_consulta')->with('paciente_id', $paciente_id);                
    }

    function pacienteConsultasListado(){
        $user=\Auth::user();
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();        
        $paciente_id = $medicoInfoAux->paciente_id;
        $users = DB::table('pacientes')                                
                                ->join('medico_pacientes','medico_pacientes.paciente_id','=','pacientes.id')
                                ->join('consultas','consultas.paciente_id','=','pacientes.id')                        
                                ->select('pacientes.id as paciente_id', 'consultas.id as consulta_id', 'consultas.created_at as fechaConsulta')                                
                                ->where('consultas.paciente_id', $paciente_id)
                                ->where('medico_pacientes.medico_user_id',$user->id)                                
                                ->where('pacientes.activo', 1)                                 
                                ->where('medico_pacientes.activo', 1)                                                                 
                                ->orderBy('consultas.id', 'desc');        
                
        return datatables()->of($users)
                           ->addIndexColumn()
                           ->addColumn('fecha', function($row){                                   
                               $fecha_aux = $row->fechaConsulta;                                                              
                               $fecha_aux = explode(' ', $fecha_aux);                               
                               $fecha_aux1 = explode('-', $fecha_aux[0]);                               
                               $fecha = $fecha_aux1[2].'/'.$fecha_aux1[1].'/'.$fecha_aux1[0];
                                                        
                               return $fecha;
                            })
                           ->addColumn('action', function($row){                                                                  
                               $consulta_id = $row->consulta_id;
                               $btn = "<button onclick=navegarConsulta($consulta_id) class='rodri_button_aceptar_si'>></button>";
                               /*$btn = "<form method='POST' action='{{ route('nuevaconsulta') }}'>
                                        @csrf                           
                                        <button class='rodri_button_aceptar_si' type='submit'>></button>                                        
                                        </form>";*/
                               return $btn;
                            })
                            ->rawColumns(['fecha','action'])
                            ->make(true);    
    }

    function navegarConsultas(Request $request){
        $user=\Auth::user();        
        $paciente_id = $request->paciente_id;

        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        if($medicoInfoAux != null){
            $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
            $medicoInfo->paciente_id = $paciente_id;            
            $medicoInfo->save();
        } else {
            $medicoInfo = new MedicoInfo;
            $medicoInfo->medico_user_id = $user->id;
            $medicoInfo->paciente_id = $paciente_id;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->consulta_id = 0;
            $medicoInfo-> save();
        }        

        return response()->json(array('response'=>1, 'paciente_id'=>$paciente_id));
    }

    function navegarConsultaSeleccionada() {
        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente_id = $medicoInfoAux->paciente_id;
        $consulta_id = $medicoInfoAux->consulta_id;
        $esNuevaConsulta = $medicoInfoAux->es_nueva_consulta;

        $_consulta = Consulta::find($consulta_id);
        $_paciente = Paciente::find($paciente_id);

        if($esNuevaConsulta == 4){
            return redirect()->route('cargarfotos');
        }

        $moduloVacunas = $this->getModuloVacuna($user->id);
        //return $moduloVacunas;
        if($_consulta->es_foto == 0){
            if($_consulta->tipo_consulta == 1){ // 1 control salud | 2 enfermedad
                return view('medico.nueva_consulta')
                        ->with('nueva_consulta', $esNuevaConsulta)
                        ->with('moduloVacunas',$moduloVacunas)                
                        ->with('consulta',$_consulta)                
                        ->with('paciente',$_paciente);  
            } else {
                if($_consulta->tipo_consulta == 4) {
                    return view('medico.nueva_consulta_telemedicina')
                        ->with('nueva_consulta', $esNuevaConsulta)
                        ->with('consulta',$_consulta)                
                        ->with('paciente',$_paciente);  
                } else {
                    if($_consulta->tipo_consulta == 5) {
                        return view('medico.consulta_prenatal.prenatal')
                        ->with('nueva_consulta', $esNuevaConsulta)
                        ->with('consulta',$_consulta)                
                        ->with('paciente',$_paciente);  
                    } else {
                        if($_consulta->tipo_consulta == 6) {                            
                            return view('medico.lactancia.consulta')
                            ->with('nueva_consulta', $esNuevaConsulta)
                            ->with('consulta',$_consulta)                
                            ->with('paciente',$_paciente);  
                        } else {
                            return view('medico.nueva_consulta_enfermedad')
                            ->with('nueva_consulta', $esNuevaConsulta)
                            ->with('consulta',$_consulta)                
                            ->with('paciente',$_paciente);  
                        }
                    }
                }
            }
        } else {
            return view('medico.nueva_consulta_foto')
                    ->with('nueva_consulta', $esNuevaConsulta)
                    ->with('consulta',$_consulta)                
                    ->with('paciente',$_paciente);  
        }
    }

    function agregarVacunaAntigripalPaciente(Request $request) {
        $consulta = $request->consulta;
        $paciente = $request->paciente;
        $fecha = $request->fecha;
        $dosis = $request->dosis;

        $vacunaAntigripal = new VacunaAntigripal;
        $vacunaAntigripal->paciente_id = $paciente;
        $vacunaAntigripal->consulta_id = $consulta;
        $vacunaAntigripal->fecha = $fecha;
        $vacunaAntigripal->dosis = $dosis;
        $vacunaAntigripal->activo = 1;
        $vacunaAntigripal->save();

        return response()->json(array('response'=>1));
    }

    function mostrarVacunaAntigripalPaciente(Request $request){        
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('vacuna_antigripals')                        
                        ->where('vacuna_antigripals.paciente_id', $paciente_id)
                        ->where('vacuna_antigripals.activo', 1)
                        ->orderby('vacuna_antigripals.fecha', 'desc')
                        ->get();        
        $response = 0;
        if($tablaAux!=null && $tablaAux->count() > 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }


    function getModuloVacuna($user_id){
        $modulo = DB::table('user_modulos')
                            ->where('user_modulos.user_id', $user_id)                            
                            ->first();
        if($modulo != null){
            return $modulo->modulo_id;
        } else {
            return 2;
        }
    }

    function guardarConsultaMedicoInfo(Request $request){
        $user=\Auth::user();        
        $consulta_id = $request->consulta_id;

        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        if($medicoInfoAux != null){
            $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
            $medicoInfo->consulta_id = $consulta_id;
            $medicoInfo->es_nueva_consulta = 0;
            $medicoInfo->save();
            return response()->json(array('response'=>1));
        }      

         return response()->json(array('response'=>0));
           
    }

    function establecerActivo(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;        
        $fecha_consulta = $request->fecha_consulta;
        $edad_mostrar = $request->edad;
        $consultas = Consulta::find($consulta_id);
        if($edad_mostrar != null)
            $consultas->edad_mostrar = $edad_mostrar;
        $consultas->activo = 1;
        if($fecha_consulta!=null)
            $consultas->created_at = $fecha_consulta;        
        $consultas->save();
        $this->setActivoExamenesComplementarios($consulta_id);
        $this->setActivoInterconsulta($consulta_id);
        return response()->json(array('response'=>1));
    }

    function desactivarConsulta(Request $request){
        $consulta_id = $request->consulta_id;
        $consulta = Consulta::find($consulta_id);
        if($consulta == null){
            return response()->json(array('response'=>0, 'message'=>'Consulta no encontrada'));
        }
        $consulta->activo = 0;
        $consulta->save();
        return response()->json(array('response'=>1));
    }


    function checkConsultaExiste(Request $request){         
        $consulta_id = $request->consulta_id;

        $consulta = Consulta::find($consulta_id);
        if($consulta->activo == 1){
            return response()->json(array('response'=>1));    
        } else {
            if($consulta->activo == 2){
                return response()->json(array('response'=>0));    
            } else {
                return response()->json(array('response'=>2));    
            }
        }
    }

    function cargarEscolaridad(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('escolaridads')
                        ->where('escolaridads.consulta_id', $consulta_id)
                        ->where('escolaridads.paciente_id', $paciente_id)
                        ->where('escolaridads.activo', 1)
                        ->first();
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }                
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarActividadesExtraEscolares(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('actividades_extra_escolares')
                        ->where('actividades_extra_escolares.consulta_id', $consulta_id)
                        ->where('actividades_extra_escolares.paciente_id', $paciente_id)
                        ->where('actividades_extra_escolares.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarMotivoConsulta(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('motivo_consultas')
                        ->where('motivo_consultas.consulta_id', $consulta_id)
                        ->where('motivo_consultas.paciente_id', $paciente_id)
                        ->where('motivo_consultas.activo', 1)
                        ->first();
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }        
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarVacunasDos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('vacunas_dos')
                        ->where('vacunas_dos.consulta_id', $consulta_id)
                        ->where('vacunas_dos.paciente_id', $paciente_id)
                        ->where('vacunas_dos.activo', 1)
                        ->first();
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }        
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function getConsulta(Request $request){
        $consulta_id = $request->consulta;
        $consulta = Consulta::find($consulta_id);
        return response()->json(array('response'=>1, 'response_data' =>$consulta));
    }

    function cargarDesarrolloMadurativoConsulta(Request $request){
        $consulta_id = $request->consulta;
        $tablaAux = DB::table('desarrollo_madurativo_pacientes')
                        ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)                        
                        ->where('desarrollo_madurativo_pacientes.activo', 1)
                        ->get(); 
        $response = 0;
        if($tablaAux != null) 
            if($tablaAux->count()>1 || strcmp($tablaAux[0]->observacion, '') != 0)  
                $response = 1;
        return response()->json(array('response'=>$response));                   
    }

    function cargarDesarrolloMadurativoObservacion(Request $request){
         $consulta_id = $request->consulta_id;
         $paciente_id = $request->paciente_id;
         $observacion_id = $this->getDesarrolloMadurativoObservacionId();

         $response_data = DB::table('desarrollo_madurativo_pacientes')
                        ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)
                        ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)
                        ->where('desarrollo_madurativo_pacientes.desarrollo_madurativo_id', $observacion_id)
                        ->where('desarrollo_madurativo_pacientes.activo', 1)
                        ->first();

        return response()->json(array('response_data'=>$response_data, 'observacion_id'=>$observacion_id));                      
    }

    function cargarNota(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('notas')
                        ->where('notas.consulta_id', $consulta_id)
                        ->where('notas.paciente_id', $paciente_id)
                        ->where('notas.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarPantallas(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('pantallas')
                        ->where('pantallas.consulta_id', $consulta_id)
                        ->where('pantallas.paciente_id', $paciente_id)
                        ->where('pantallas.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarHabitos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('habitos')
                        ->where('habitos.consulta_id', $consulta_id)
                        ->where('habitos.paciente_id', $paciente_id)
                        ->where('habitos.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarDiuresisCatarsis(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('catarses')
                        ->where('catarses.consulta_id', $consulta_id)
                        ->where('catarses.paciente_id', $paciente_id)
                        ->where('catarses.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarSomnia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('somnias')
                        ->where('somnias.consulta_id', $consulta_id)
                        ->where('somnias.paciente_id', $paciente_id)
                        ->where('somnias.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    
    function cargarDatosSubjetivos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('datos_subjetivos')
                        ->where('datos_subjetivos.consulta_id', $consulta_id)
                        ->where('datos_subjetivos.paciente_id', $paciente_id)
                        ->where('datos_subjetivos.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarDatosObjetivos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('datos_objetivos')
                        ->where('datos_objetivos.consulta_id', $consulta_id)
                        ->where('datos_objetivos.paciente_id', $paciente_id)
                        ->where('datos_objetivos.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarConductas(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('conductas')
                        ->where('conductas.consulta_id', $consulta_id)
                        ->where('conductas.paciente_id', $paciente_id)
                        ->where('conductas.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarObservaciones(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('observaciones')
                        ->where('observaciones.consulta_id', $consulta_id)
                        ->where('observaciones.paciente_id', $paciente_id)
                        ->where('observaciones.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));
    }

    function cargarExamenFisico(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $tipo_consulta = $request->tipo_consulta;
        
        $tablaAux = DB::table('examen_fisicos')
                        ->where('examen_fisicos.consulta_id', $consulta_id)
                        ->where('examen_fisicos.paciente_id', $paciente_id)
                        ->where('examen_fisicos.activo', 1)
                        ->first();
        $response = 0;
        if($tablaAux != null){
            if(strcmp($tablaAux->peso, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->peso_percentil, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->talla, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->talla_percentil, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->pc, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->pc_percentil, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->ipd, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->ta, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->imc, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)
                $response = 1;
            if(strcmp($tablaAux->imc_percentil, '') != 0 && strcmp($tablaAux->peso, '0.00') != 0)            
                $response = 1;
            if(strcmp($tablaAux->nota, '') != 0)
                $response = 1;
        }
        
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux, 'tipo_consulta'=>$tipo_consulta));
    }

    function cargarAlimentacion(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('alimentacions')
                        ->where('alimentacions.consulta_id', $consulta_id)
                        ->where('alimentacions.paciente_id', $paciente_id)
                        ->where('alimentacions.activo', 1)
                        ->first();        
        
        return response()->json(array('response'=>1, 'response_data' =>$tablaAux));   
    }

    function cargarMenarca(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('menarcas')                        
                        ->where('menarcas.paciente_id', $paciente_id)
                        ->where('menarcas.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null && strcmp($tablaAux->descripcion, '') != 0){
            $response = 1;
        }
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));   
    }

    function cargarAntecedentesPerinatales(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('antecedentes_perinatales')                        
                        ->where('antecedentes_perinatales.paciente_id', $paciente_id)
                        ->where('antecedentes_perinatales.activo', 1)
                        ->first();        
        
        return response()->json(array('response'=>1, 'response_data' =>$tablaAux));      
    }

    function cargarAntecedentesPersonales(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $tipo_consulta = $request->tipo_consulta;

        if($tipo_consulta != null && $tipo_consulta == 4){
            $tablaAux = DB::table('antecedentes_personales')   
                            ->join('consultas', 'consultas.id', 'antecedentes_personales.consulta_id')                     
                            ->select('antecedentes_personales.*')
                            ->where('antecedentes_personales.paciente_id', $paciente_id)
                            ->where('antecedentes_personales.consulta_id', $consulta_id)
                            ->where('antecedentes_personales.activo', 1)
                            ->where('consultas.activo', 1)
                            ->orderby('antecedentes_personales.id', 'desc')
                            ->first();
            $internacion = null;
            $response = 0;
            if($tablaAux != null){
                $response = 1;
                $internacion = DB::table('internaciones')                        
                            ->where('internaciones.paciente_id', $paciente_id)
                            ->where('internaciones.consulta_id', $tablaAux->consulta_id)
                           // ->where('internaciones.antecedentes_personales_id', $tablaAux->id)
                            ->where('internaciones.activo', 1)
                            ->orderby('numero', 'desc')
                            ->first();
            }
        } else {
            $tablaAux = DB::table('antecedentes_personales')   
                            ->join('consultas', 'consultas.id', 'antecedentes_personales.consulta_id')                     
                            ->select('antecedentes_personales.*')
                            ->where('antecedentes_personales.paciente_id', $paciente_id)
                            //->where('antecedentes_personales.consulta_id', $consulta_id)
                            ->where('antecedentes_personales.activo', 1)
                            ->where('consultas.activo', 1)
                            ->orderby('antecedentes_personales.id', 'desc')
                            ->first();
            $internacion = null;
            $response = 0;
            if($tablaAux != null){
                $response = 1;
                $internacion = DB::table('internaciones')                        
                            ->where('internaciones.paciente_id', $paciente_id)
                            ->where('internaciones.consulta_id', $tablaAux->consulta_id)
                           // ->where('internaciones.antecedentes_personales_id', $tablaAux->id)
                            ->where('internaciones.activo', 1)
                            ->orderby('numero', 'desc')
                            ->first();
            } 
        }

        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux, 'internacion'=>$internacion));      
    }

    function cargarAntecedentesFamiliares(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('antecedentes_familiares')                        
                        ->where('antecedentes_familiares.paciente_id', $paciente_id)
                        ->where('antecedentes_familiares.activo', 1)
                        ->orderby('antecedentes_familiares.id', 'desc')
                        ->first();        
        
        return response()->json(array('response'=>1, 'response_data' =>$tablaAux));         
    }

    function cargarAntecedentesNeonantalesNota(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('antecedentes_neonatales')                        
                        ->where('antecedentes_neonatales.paciente_id', $paciente_id)
                        ->where('antecedentes_neonatales.activo', 1)
                        ->first();        
        
        return response()->json(array('response'=>1, 'response_data' =>$tablaAux));         
    }

    function guardarinterconsultores(Request $request) {
        $user_id = \Auth::user()->id;        
        $interconsultores = new Interconsultores;
        $interconsultores->user_id = $user_id;
        
        if($request->nombre != null){
            $interconsultores->nombre = $request->nombre;
        } else {
            $interconsultores->nombre = "";
        }

        if($request->apellido != null){
            $interconsultores->apellido = $request->apellido;
        } else {
            $interconsultores->apellido = "";
        }

        if($request->especialidad != null){
            $interconsultores->especialidad = $request->especialidad;
        } else {
            $interconsultores->especialidad = "";
        }

        if($request->direccion != null){
            $interconsultores->direccion = $request->direccion;
        } else {
            $interconsultores->direccion = "";
        }

        if($request->telefono_p != null){
            $interconsultores->telefono_particular = $request->telefono_p;
        } else {
            $interconsultores->telefono_particular = "";
        }

        if($request->telefono_c != null){
            $interconsultores->telefono_consultorio = $request->telefono_c;
        } else {
            $interconsultores->telefono_consultorio = "";
        }

        $interconsultores->activo = 1;
        $interconsultores->save();

        $interconsultores_tabla = $this->getTablaInterconsultores();

        return response()->json(array('response'=>1, 'response_data'=>$interconsultores, 'interconsultores'=>$interconsultores_tabla));         
    }

    function getInterconsultor(Request $request){
        $id = $request->id;
        $interconsultor = Interconsultores::find($id);
        return response()->json(array('response'=>1, 'response_data'=>$interconsultor));
    }

    function modificarInterconsultor(Request $request){
        $id = $request->id;
        $interconsultores = Interconsultores::find($id);
        if($request->nombre != null){
            $interconsultores->nombre = $request->nombre;
        } else {
            $interconsultores->nombre = "";
        }

        if($request->apellido != null){
            $interconsultores->apellido = $request->apellido;
        } else {
            $interconsultores->apellido = "";
        }

        if($request->especialidad != null){
            $interconsultores->especialidad = $request->especialidad;
        } else {
            $interconsultores->especialidad = "";
        }

        if($request->direccion != null){
            $interconsultores->direccion = $request->direccion;
        } else {
            $interconsultores->direccion = "";
        }

        if($request->telefono_p != null){
            $interconsultores->telefono_particular = $request->telefono_p;
        } else {
            $interconsultores->telefono_particular = "";
        }

        if($request->telefono_c != null){
            $interconsultores->telefono_consultorio = $request->telefono_c;
        } else {
            $interconsultores->telefono_consultorio = "";
        }

        $interconsultores->activo = 1;
        $interconsultores->save();

        $interconsultores_tabla = $this->getTablaInterconsultores();
        return response()->json(array('response'=>1, 'interconsultores'=>$interconsultores_tabla));
    }

    function eliminarInterconsultor(Request $request){
        $id = $request->id;
        $interconsultor = Interconsultores::find($id);
        $interconsultor->delete();

        $interconsultores_tabla = $this->getTablaInterconsultores();
        return response()->json(array('response'=>1, 'interconsultores'=>$interconsultores_tabla));
    }

    function filtrarInterconsultores(Request $request){
        $user_id = \Auth::user()->id; 
        $texto = $request->texto;        
        $textoEsNumero = $request->esNumero;
        
        $texto = strtoupper($texto);   
        
        $interconsultores_1 = DB::table('interconsultores')                                             
                                ->select('interconsultores.*')
                                ->where('interconsultores.user_id', $user_id)                                 
                                ->where('interconsultores.telefono_particular', 'LIKE', '%' . $texto . '%')
                                ->where('interconsultores.activo', 1)
                                ->orderby('interconsultores.especialidad')
                                ->get();

        $interconsultores_2 = DB::table('interconsultores')                                             
                                ->select('interconsultores.*')
                                ->where('interconsultores.user_id', $user_id)                                 
                                ->where('interconsultores.telefono_consultorio', 'LIKE', '%' . $texto . '%')
                                ->where('interconsultores.activo', 1)
                                ->orderby('interconsultores.especialidad')
                                ->get();

        $interconsultores_3 = DB::table('interconsultores')                                             
                                ->select('interconsultores.*')
                                ->where('interconsultores.user_id', $user_id)                                 
                                ->where('interconsultores.especialidad', 'LIKE', '%' . $texto . '%')
                                ->where('interconsultores.activo', 1)
                                ->orderby('interconsultores.especialidad')
                                ->get();

        $interconsultores_4 = DB::table('interconsultores')                                             
                                ->select('interconsultores.*')
                                ->where('interconsultores.user_id', $user_id)                                 
                                ->where('interconsultores.nombre', 'LIKE', '%' . $texto . '%')
                                ->where('interconsultores.activo', 1)
                                ->orderby('interconsultores.especialidad')
                                ->get();

        $interconsultores_5 = DB::table('interconsultores')                                             
                                ->select('interconsultores.*')
                                ->where('interconsultores.user_id', $user_id)                                 
                                ->where('interconsultores.apellido', 'LIKE', '%' . $texto . '%')
                                ->where('interconsultores.activo', 1)
                                ->orderby('interconsultores.especialidad')
                                ->get();

        $interconsultores = $interconsultores_1->merge($interconsultores_2)->merge($interconsultores_3)->merge($interconsultores_4)->merge($interconsultores_5);
        $interconsultores = $interconsultores->unique('id');               
            
        return response()->json(array('interconsultores'=>$interconsultores, 'texto'=>$texto));
    }

    /*function interconsultoresListadoBuscar(){
        $user=\Auth::user();                
        $users = DB::table('interconsultores')                                                            
                            ->where('interconsultores.user_id',$user->id)                                                       
                            ->where('interconsultores.activo', 1)                                 
                            ->orderby('interconsultores.apellido');        

        return datatables()->of($users)
                           ->addIndexColumn()                                                       
                           ->make(true);    
    } */   

    function guardarPendientesCancelar(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;
        $pendiente_text = '';        

        $existePendiente = DB::table('aux_pendientes')                                                            
                            ->where('aux_pendientes.paciente_id',$paciente_id)
                            ->where('aux_pendientes.consulta_id',$consulta_id)                                                       
                            ->where('aux_pendientes.activo', 1)                                 
                            ->first();
        if($existePendiente!=null){
            $pendiente = AuxPendiente::find($existePendiente->id);
            if(($pendiente_text == null || strcmp($pendiente_text, '') == 0))
                $pendiente->pendientes = '';
            else                            
                $pendiente->pendientes = $pendiente_text;                        
            $pendiente->save();
        }
        else{
            $pendiente = new AuxPendiente;
            $pendiente->paciente_id = $paciente_id;
            $pendiente->consulta_id = $consulta_id;

            if(($pendiente_text == null || strcmp($pendiente_text, '') == 0))
                $pendiente->pendientes = '';
            else                            
                $pendiente->pendientes = $pendiente_text;                        

            $pendiente->activo = 1;            
            $pendiente->save();
        }
        
        return response()->json(array('response'=>1, 'response_data'=>$pendiente));
    }   

    function guardarPendientes(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;
        $pendiente_text = $request->pendiente;
        $opcion = $request->opcion;

        $existePendiente = DB::table('aux_pendientes')                                                            
                            ->where('aux_pendientes.paciente_id',$paciente_id)
                            ->where('aux_pendientes.consulta_id',$consulta_id)                                                       
                            ->where('aux_pendientes.activo', 1)                                 
                            ->first();
        if($existePendiente!=null){
            $pendiente = AuxPendiente::find($existePendiente->id);
            if(($pendiente_text == null || strcmp($pendiente_text, '') == 0))
                $pendiente->pendientes = '';
            else                            
                $pendiente->pendientes = $pendiente_text;                        
            $pendiente->save();
        }
        else{
            $pendiente = new AuxPendiente;
            $pendiente->paciente_id = $paciente_id;
            $pendiente->consulta_id = $consulta_id;

            if(($pendiente_text == null || strcmp($pendiente_text, '') == 0))
                $pendiente->pendientes = '';
            else                            
                $pendiente->pendientes = $pendiente_text;                        

            $pendiente->activo = 1;            
            $pendiente->save();
        }
        
        return response()->json(array('response'=>1, 'response_data'=>$pendiente, 'opcion'=>$opcion));         
    }

    function cargarPendientes(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;
        $opcion = $request->opcion;

        if($consulta_id != null){
            $existePendiente = DB::table('aux_pendientes')                                                            
                            ->where('aux_pendientes.paciente_id',$paciente_id)                            
                            ->where('aux_pendientes.consulta_id',$consulta_id)                            
                            ->where('aux_pendientes.activo', 1)                            
                            ->first();
        } else {
            $existePendiente = DB::table('aux_pendientes')                                                            
                            ->where('aux_pendientes.paciente_id',$paciente_id)                            
                            ->where('aux_pendientes.activo', 1)
                            ->orderby('aux_pendientes.id','desc')                                 
                            ->first();
        }

        return response()->json(array('response'=>1, 'response_data'=>$existePendiente, 'opcion'=>$opcion));  
    }

    function checkAlimentacionVacio($alimentacion){
        $vacio = 0;
        if($alimentacion!=null) {
            if(($alimentacion->pecho == 1 || strcmp($alimentacion->pecho_detalle, '') != 0) ||
                ($alimentacion->leche_maternizada == 1 || strcmp($alimentacion->leche_maternizada_detalle, '') != 0) ||
                    ($alimentacion->leche_vaca == 1 || strcmp($alimentacion->leche_vaca_detalle, '') != 0) ||
                        ($alimentacion->hierro == 1 || $alimentacion->vitamina == 1) ||
                            (strcmp($alimentacion->dieta_tipo, '') != 0) || (strcmp($alimentacion->dieta_comidas, '') != 0) ||
                                (strcmp($alimentacion->catarsis, '') != 0) || (strcmp($alimentacion->somnia, '') != 0))
                                    $vacio = 1;
        }
        return $vacio;
    }

    function cargarResumenConsulta(Request $request) {
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;
        $medico_id=\Auth::user()->id;
        $moduloVacunas = $this->getModuloVacuna($medico_id);               

        $paciente = Paciente::find($paciente_id);
        $consulta = Consulta::find($consulta_id);

        if($moduloVacunas == 2){
            $vacunas = DB::table('vacunas_dos')
                    ->where('vacunas_dos.paciente_id', $paciente_id)  
                    ->where('vacunas_dos.consulta_id', $consulta_id)                                           
                    ->where('vacunas_dos.activo', 1)                    
                    ->first();
        } else {
            $vacunas = DB::table('vacunas_pacientes')
                        //->join('vacunas','vacunas.id', 'vacunas_pacientes.vacuna_id')
                        //->select(count('vacuna_id'), 'vacuna_id','estado', 'nombre')
                        ->select(DB::raw('count(vacunas_pacientes.vacuna_id) as vac_count, vacuna_id, estado, nombre'))
                        ->where('vacunas_pacientes.paciente_id', $paciente_id)                                                              
                        ->where('vacunas_pacientes.activo', 1)                    
                        ->groupby('vacunas_pacientes.vacuna_id', 'vacunas_pacientes.estado', 'vacunas_pacientes.nombre')
                        ->get();
        }
        $alimentacion = DB::table('alimentacions')
                    ->where('alimentacions.paciente_id', $paciente_id)  
                    ->where('alimentacions.consulta_id', $consulta_id)                                           
                    ->where('alimentacions.activo', 1)                    
                    ->first();
        $alimentacionMostrar = $this->checkAlimentacionVacio($alimentacion);
        $conductas = DB::table('conductas')                                                            
                    ->where('conductas.paciente_id', $paciente_id)   
                    ->where('conductas.consulta_id', $consulta_id)                         
                    ->where('conductas.activo', 1)                    
                    ->first();
        $observaciones = DB::table('observaciones')                                                            
                    ->where('observaciones.paciente_id', $paciente_id)   
                    ->where('observaciones.consulta_id', $consulta_id)                         
                    ->where('observaciones.activo', 1)                    
                    ->first();
        $escolaridad = DB::table('escolaridads')                                                            
                    ->where('escolaridads.paciente_id', $paciente_id)   
                    ->where('escolaridads.consulta_id', $consulta_id)                         
                    ->where('escolaridads.activo', 1)                    
                    ->first();
        $actividades_extra_escolares = DB::table('actividades_extra_escolares')                                                            
                    ->where('actividades_extra_escolares.paciente_id', $paciente_id)   
                    ->where('actividades_extra_escolares.consulta_id', $consulta_id)                         
                    ->where('actividades_extra_escolares.activo', 1)                    
                    ->first();
        $pantallas = DB::table('pantallas')
                    ->where('pantallas.paciente_id', $paciente_id)   
                    ->where('pantallas.consulta_id', $consulta_id)                         
                    ->where('pantallas.activo', 1)                    
                    ->first();
        $habitos = DB::table('habitos')
                    ->where('habitos.paciente_id', $paciente_id)   
                    ->where('habitos.consulta_id', $consulta_id)                         
                    ->where('habitos.activo', 1)                    
                    ->first();
        $menarca = DB::table('menarcas')
                    ->where('menarcas.paciente_id', $paciente_id)                                           
                    ->where('menarcas.activo', 1)                    
                    ->first();

        $examen_fisico = DB::table('examen_fisicos')
                    ->where('examen_fisicos.paciente_id', $paciente_id)  
                    ->where('examen_fisicos.consulta_id', $consulta_id)                                           
                    ->where('examen_fisicos.activo', 1)                    
                    ->first();
        $examen_fisico_completo = $this->examenFisicoCompleto($examen_fisico);
        $interconsultas = DB::table('interconsultas')
                    ->where('interconsultas.paciente_id', $paciente_id)  
                    ->where('interconsultas.consulta_id', $consulta_id)                                           
                    ->where('interconsultas.activo', 1)                    
                    ->get();

        $examenes_complementarios = DB::table('examenes_complementarios')                                        
                    ->where('examenes_complementarios.paciente_id', $paciente_id)  
                    ->where('examenes_complementarios.consulta_id', $consulta_id)                                           
                    ->where('examenes_complementarios.activo', 1)                                       
                    ->orderby('examenes_complementarios.id', 'desc')
                    ->get();        

        $desarrollo_madurativo = DB::table('desarrollo_madurativo_pacientes')
                    ->join('desarrollo_madurativos', 'desarrollo_madurativos.id', 'desarrollo_madurativo_pacientes.desarrollo_madurativo_id')
                    ->select('desarrollo_madurativo_pacientes.*', 'desarrollo_madurativos.descripcion')
                    ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)  
                    ->where('desarrollo_madurativos.mes', $consulta->edad_paciente)                                           
                    ->where('desarrollo_madurativo_pacientes.activo', 1)                    
                    ->get();
        $observacion_id = $this->getDesarrolloMadurativoObservacionId();
        $desarrollo_madurativo_observacion = DB::table('desarrollo_madurativo_pacientes')
                                            ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)
                                            ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)        
                                            ->where('desarrollo_madurativo_pacientes.desarrollo_madurativo_id', $observacion_id)
                                            ->where('desarrollo_madurativo_pacientes.activo', 1)
                                            ->first();
        
        $antecedentes_perinatales = DB::table('antecedentes_perinatales')                                        
                    ->where('antecedentes_perinatales.paciente_id', $paciente_id)                                                          
                    ->where('antecedentes_perinatales.activo', 1)                                       
                    ->first();

         $antecedentes_neonatales = DB::table('antecedentes_neonatales')                                        
                    ->where('antecedentes_neonatales.paciente_id', $paciente_id)                                                          
                    ->where('antecedentes_neonatales.activo', 1)                                       
                    ->first();

        $notas = DB::table('notas')
                    ->where('notas.paciente_id', $paciente_id)   
                    ->where('notas.consulta_id', $consulta_id)                         
                    ->where('notas.activo', 1)                    
                    ->first();
        $edad = \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age;

        return response()->json(array('response'=>1, 'response_conductas'=>$conductas, 'response_observaciones'=>$observaciones, 'response_escolaridad' => $escolaridad, 'response_actividades_extra_escolares' => $actividades_extra_escolares, 'response_pantallas'=>$pantallas, 'response_habitos'=>$habitos, 'response_menarca'=>$menarca, 'response_examen_fisicos'=>$examen_fisico, 'response_interconsultas'=>$interconsultas, 'response_alimentacion' =>$alimentacion,'alimentacion_mostrar'=>$alimentacionMostrar, 'response_examenes_complementarios' => $examenes_complementarios, 'response_desarrollo_madurativo'=> $desarrollo_madurativo, 'response_paciente' =>$paciente, 'response_antecedentes_perinatales' => $antecedentes_perinatales, 'response_paciente_edad'=>$edad, 'response_notas'=>$notas, 'vacunas_response'=>$vacunas,'modulo_vacunas'=>$moduloVacunas, 'response_desarrollo_madurativo_observacion'=> $desarrollo_madurativo_observacion, 'response_antecedentes_neonatales'=>$antecedentes_neonatales, 'response_examen_fisico_completo'=>$examen_fisico_completo)); 
    }    

    function examenFisicoCompleto($examenFisico){
        $response = 0;
        if($examenFisico != null){
            if((strcmp($examenFisico->peso, '') != 0 && strcmp($examenFisico->peso, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->peso_percentil, '') != 0 && strcmp($examenFisico->peso_percentil, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->talla, '') != 0 && strcmp($examenFisico->talla, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->talla_percentil, '') != 0 && strcmp($examenFisico->talla_percentil, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->pc, '') != 0 && strcmp($examenFisico->pc, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->pc_percentil, '') != 0 && strcmp($examenFisico->pc_percentil, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->ipd, '') != 0 && strcmp($examenFisico->ipd, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->ta, '') != 0 && strcmp($examenFisico->ta, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->imc, '') != 0 && strcmp($examenFisico->imc, '0.00') != 0)){
                $response = 1;
            }
            if((strcmp($examenFisico->imc_percentil, '') != 0 && strcmp($examenFisico->imc_percentil, '0.00') != 0)){
                $response = 1;
            }
            if(strcmp($examenFisico->nota, '') != 0 ){
                $response = 1;
            }
        }
        return $response;
    }

    function guardarNuevaConsultaFoto(Request $request){
        $consulta_id = $request->consulta_id;
        $paciente_id = $request->paciente_id;
        
        $_consulta = Consulta::find($consulta_id);
        $_consulta->activo = 1;        
        $_consulta->save();

        $cantidadFotos = intval($request->nueva_consulta_fotos_cantidad_fotos);
        $usuario_actual_nombre=\Auth::user()->email;   
        $pathFolderMedico_aux = explode('@', $usuario_actual_nombre);
        $pathFolderMedico = $pathFolderMedico_aux[0];
        for ($i = 1; $i <= $cantidadFotos; $i++) {
            $request_foto = 'nueva_consulta_foto_'.$i;

            if($request->$request_foto != null){
                
                if($request->hasfile($request_foto)){
                    $nuevaConsultaFoto = new ConsultaFoto;
                    $nuevaConsultaFoto->activo = 1;
                    $nuevaConsultaFoto->paciente_id = $paciente_id;
                    $nuevaConsultaFoto->consulta_id = $consulta_id;
                    $nuevaConsultaFoto->numero = $this->getNumeroFotosNuevaConsulta($paciente_id, $consulta_id);                    
                    $pathName = $request->file($request_foto)->store('img/'.$pathFolderMedico.'consulta_foto');
                    $name = collect(explode('/', $pathName))->last();
                    $image = $request->file($request_foto);
                    //$name  = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
                    $path = 'img/'.$pathFolderMedico.'/consulta_foto/'.$name;        
                    Image::make($image->getRealPath())->resize(1980, 1920)->save($path);  
                    $nuevaConsultaFoto->foto = $pathFolderMedico.'/consulta_foto/'.$name;
                    $nuevaConsultaFoto->save();           
                }               
            } 
        }

        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
        $medicoInfo->es_nueva_consulta = 3;
        $medicoInfo->save();

        return redirect()->route('navegarconsultaseleccionada');
    }


    function getNumeroFotosNuevaConsulta($paciente_id, $consulta_id){
        $cantidadFotos = DB::table('consulta_fotos')                                
                                ->where('consulta_fotos.paciente_id', $paciente_id)
                                ->where('consulta_fotos.consulta_id', $consulta_id)
                                ->where(function ($query) {
                                     $query->where('consulta_fotos.activo', 1)
                                           ->orWhere('consulta_fotos.activo', 2);
                                })                                    
                                ->get();
        return $cantidadFotos->count()+1;
    }

    function getFotosNuevaConsulta(Request $request){
         $fotos_aux = DB::table('consulta_fotos')                                
                                ->where('consulta_fotos.paciente_id', $request->paciente)
                                ->where('consulta_fotos.consulta_id', $request->consulta)                                
                                ->where(function ($query) {
                                     $query->where('consulta_fotos.activo', 1)
                                           ->orWhere('consulta_fotos.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();        
        return response()->json(array('response'=>1, 'consultaFotos'=>$fotos_aux)); 
    }

    function cargarAntPersonalesAntSig(Request $request){
        $paciente_id = $request->paciente;
        $numero = $request->numero;
        $consulta_id = $request->consulta;
        if($numero < 1){
            $numero = 1;
        }
        $response_data_aux1 = DB::table('antecedentes_personales')        
                                ->join('consultas','consultas.id', 'antecedentes_personales.consulta_id')                        
                                ->select('antecedentes_personales.*')
                                ->where('antecedentes_personales.paciente_id', $paciente_id)                                 
                                ->where('consultas.activo', 1)
                                ->where('antecedentes_personales.activo', 1)
                                ->get();    
        $response_data_aux2 = DB::table('antecedentes_personales')                                        
                                ->select('antecedentes_personales.*')
                                ->where('antecedentes_personales.paciente_id', $paciente_id)                                 
                                ->where('antecedentes_personales.consulta_id', $consulta_id)
                                ->where('antecedentes_personales.activo', 1)
                                ->get();    

        $response_data_aux = $response_data_aux1->merge($response_data_aux2);
        $response_data_aux = $response_data_aux->unique('id');

        $cantidad = $response_data_aux->count();
        if($numero>$cantidad)
            $numero = $cantidad;

        $internacion = null;
        $response_data = null;
        $response_data = DB::table('antecedentes_personales')                                
                                        ->join('consultas','consultas.id','antecedentes_personales.consulta_id')
                                        ->select('antecedentes_personales.*')
                                        ->where('antecedentes_personales.paciente_id', $paciente_id)                                        
                                        ->where('antecedentes_personales.numero', $numero)
                                        ->where('consultas.activo', 1)
                                        ->where('antecedentes_personales.activo', 1)                                        
                                        ->first();
        if($response_data == null){
            $response_data = DB::table('antecedentes_personales')                                                                        
                                        ->where('antecedentes_personales.paciente_id', $paciente_id)                                        
                                        ->where('antecedentes_personales.numero', $numero)                                        
                                        ->where('antecedentes_personales.consulta_id', $consulta_id)      
                                        ->where('antecedentes_personales.activo', 1)                                        
                                        ->first();
        }
        
        if($response_data != null) {
            $internacion = DB::table('internaciones')
                                        ->where('internaciones.antecedentes_personales_id', $response_data->id)
                                        ->where('internaciones.activo', 1)
                                        ->orderby('internaciones.id', 'desc')                                        
                                        ->first();
        }

        return response()->json(array('response'=>1, 'response_data'=>$response_data, 'tope'=>$cantidad, 'internacion'=>$internacion)); 
    }

    function antPersonalesNuevo(Request $request){
        $paciente_id = $request->paciente;
        $response_data = DB::table('antecedentes_personales')
                                    ->join('consultas','consultas.id','antecedentes_personales.consulta_id')                                
                                        ->where('antecedentes_personales.paciente_id', $paciente_id)
                                        ->where('antecedentes_personales.activo', 1)
                                        ->where('consultas.activo', 1)
                                        ->get();
        $nuevo = $response_data->count() + 1;

        return response()->json(array('response'=>1, 'response_data'=>$response_data, 'nuevo'=>$nuevo));    
    }

    function cargarFotosNuevaConsultaAntSigFoto(Request $request){
        $paciente_id = $request->paciente;        
        $consulta_id = $request->consulta;        
        
        $numero = $request->numero;
        if($numero < 1){
            $numero = 1;
        }
        $cantidad_fotos_aux = DB::table('consulta_fotos')                                
                                ->where('consulta_fotos.paciente_id', $paciente_id) 
                                ->where('consulta_fotos.consulta_id', $consulta_id)                                
                                ->where('consulta_fotos.activo', 1)
                                ->get();    
        $cantidadFotos = $cantidad_fotos_aux->count();
        if($numero>$cantidadFotos)
            $numero = $cantidadFotos;

        $response_data = DB::table('consulta_fotos')                                
                                        ->where('consulta_fotos.paciente_id', $paciente_id)  
                                        ->where('consulta_fotos.consulta_id', $consulta_id)                                       
                                        ->where('consulta_fotos.numero', $numero)
                                        ->where('consulta_fotos.activo', 1)                                        
                                        ->first();

        return response()->json(array('response'=>1, 'cantidadFotos' => $cantidadFotos,'response_data'=>$response_data));
    }

    function guardarDesarrolloMadurativoEdad(Request $request){        
        $consulta_id = $request->consulta;
        $edad = $request->edad;
        $consulta = Consulta::find($consulta_id);
        $consulta->edad_paciente = $edad;
        $consulta->save();

        return response()->json(array('response'=>1));   
    }

    function getDesarrolloMadurativoTabla(Request $request){
        $edad = $request->edad;
        $opcion = $request->opcion;

        $motor_grueso = DB::table('desarrollo_madurativos')                                
                                ->where('desarrollo_madurativos.tipo', 'Motor Grueso')                                 
                                ->where('desarrollo_madurativos.mes', $edad)                                 
                                ->where('desarrollo_madurativos.activo', 1)
                                ->get();  
        $motor_fino = DB::table('desarrollo_madurativos')                                
                                ->where('desarrollo_madurativos.tipo', 'Motor Fino')                                 
                                ->where('desarrollo_madurativos.mes', $edad)                                 
                                ->where('desarrollo_madurativos.activo', 1)
                                ->get();  
        $psicosocial = DB::table('desarrollo_madurativos')                                
                                ->where('desarrollo_madurativos.tipo', 'Psicosocial')                                 
                                ->where('desarrollo_madurativos.mes', $edad)                                 
                                ->where('desarrollo_madurativos.activo', 1)
                                ->get();  
        $lenguaje = DB::table('desarrollo_madurativos')                                
                                ->where('desarrollo_madurativos.tipo', 'Lenguaje')                                 
                                ->where('desarrollo_madurativos.mes', $edad)                                 
                                ->where('desarrollo_madurativos.activo', 1)
                                ->get();
        $tope = $motor_grueso->count();
        if($motor_fino->count()> $tope)
            $tope = $motor_fino->count();
        if($psicosocial->count()> $tope)
            $tope = $psicosocial->count();
        if($lenguaje->count()> $tope)
            $tope = $lenguaje->count();


        return response()->json(array('response'=>1, 'tope' => $tope,'motor_grueso'=>$motor_grueso,'motor_fino'=>$motor_fino,'psicosocial'=>$psicosocial,'lenguaje'=>$lenguaje, 'opcion'=>$opcion ));                           
    }

    function guardarDesarrolloMadurativoCheck(Request $request){
        $paciente_id = $request->paciente_id;
        $consulta_id = $request->consulta_id;
        $desarrollo_madurativo_id = $request->desarrollo_madurativo_id;
        $checked_value = $request->checked;

        $desarrolloMadurativoPaciente = $this->existeDesarrolloMadurativoPaciente($consulta_id, $paciente_id, $desarrollo_madurativo_id);
        if($desarrolloMadurativoPaciente!=null){
            $desarrolloMadurativoPaciente->checked = $checked_value;
            $desarrolloMadurativoPaciente->save();
        } else {
            $desarrolloMadurativoPaciente = new desarrolloMadurativoPaciente;
            $desarrolloMadurativoPaciente->paciente_id = $paciente_id;
            $desarrolloMadurativoPaciente->consulta_id = $consulta_id;
            $desarrolloMadurativoPaciente->desarrollo_madurativo_id = $desarrollo_madurativo_id;
            $desarrolloMadurativoPaciente->checked = $checked_value;
            $desarrolloMadurativoPaciente->observacion = '';
            $desarrolloMadurativoPaciente->activo = 1;
            $desarrolloMadurativoPaciente->save();
        }

        return response()->json(array('response'=>1));  
    }

    function existeDesarrolloMadurativoPaciente($consulta_id, $paciente_id, $desarrollo_madurativo_id){
            $response = DB::table('desarrollo_madurativo_pacientes')                                
                                ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)                                 
                                ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)
                                ->where('desarrollo_madurativo_pacientes.desarrollo_madurativo_id', $desarrollo_madurativo_id)
                                ->where('desarrollo_madurativo_pacientes.activo', 1)
                                ->first();
            if($response !=null)
                $response = DesarrolloMadurativoPaciente::find($response->id);
            return $response;
    }

    function cargarDesarrolloMadurativo(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $_mes = $request->mes;
        $mes = $_mes;
        if($_mes != -2){
            $response = DB::table('desarrollo_madurativo_pacientes')
                                ->join('desarrollo_madurativos', 'desarrollo_madurativos.id', 'desarrollo_madurativo_pacientes.desarrollo_madurativo_id')
                                ->select('desarrollo_madurativo_pacientes.*')                     
                                ->where('desarrollo_madurativos.mes', $_mes)                                 
                                ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)                                
                                ->where('desarrollo_madurativo_pacientes.activo', 1)
                                ->get();            
        } else {
            $response = DB::table('desarrollo_madurativo_pacientes')                                
                                ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)                                 
                                ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)                                
                                ->where('desarrollo_madurativo_pacientes.activo', 1)
                                ->get();
        }        
        if($response != null) {
            $tope = $response->count();
            /*$dm = DesarrolloMadurativo::find($response[0]->desarrollo_madurativo_id);
            if($mes != -2)
                $mes = $dm->mes;*/
            $res = 1;
        } else {
            $tope = 0;
            $res = 0;
            if($mes != -2)
                $mes = 0;
        }

        $observacion_id = $this->getDesarrolloMadurativoObservacionId();
        $observacion_detalle = $this->getDesarrolloMadurativoObservacionDetalle($consulta_id, $paciente_id, $observacion_id);
        return response()->json(array('response'=>$res, 'response_data'=>$response, 'tope'=>$tope, 'mes'=>$mes, 'observacion_id' =>$observacion_id, 'observacion_detalle'=> $observacion_detalle));  
    }

    function getDesarrolloMadurativoObservacionDetalle($consulta_id, $paciente_id, $observacion_id){
        $response = DB::table('desarrollo_madurativo_pacientes')                                
                                ->select('desarrollo_madurativo_pacientes.observacion')                     
                                ->where('desarrollo_madurativo_pacientes.desarrollo_madurativo_id', $observacion_id)
                                ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)                                 
                                ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)                                
                                ->where('desarrollo_madurativo_pacientes.activo', 1)
                                ->first();   
        return $response;
    }

    function getDesarrolloMadurativoEdadConsulta(Request $request){
        $consulta_id = $request->consulta; 
        $paciente_id = $request->paciente; 

        $observacion_id = $this->getDesarrolloMadurativoObservacionId();
        $response = DB::table('desarrollo_madurativo_pacientes')                                
                                ->where('desarrollo_madurativo_pacientes.consulta_id', $consulta_id)                                 
                                ->where('desarrollo_madurativo_pacientes.paciente_id', $paciente_id)                                
                                ->where('desarrollo_madurativo_pacientes.desarrollo_madurativo_id', '!=', $observacion_id)
                                ->where('desarrollo_madurativo_pacientes.activo', 1)
                                ->get();   
        $mes = null;
        if($response != null) {     
            $dm = DesarrolloMadurativo::find($response[0]->desarrollo_madurativo_id);
            $mes = $dm->mes;
        }
        return response()->json(array('response'=>1, 'mes'=>$mes));  
    }

    function getDesarrolloMadurativoObservacionId(){
        $response = DB::table('desarrollo_madurativos')                                
                                ->where('desarrollo_madurativos.tipo', 'Observacion')                                                       
                                ->where('desarrollo_madurativos.activo', 1)
                                ->first();      
        return $response->id;
    }

    function guardarDesarrolloMadurativoObservacion(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $observacion = $request->observacion;
        $desarrollo_madurativo_id = $this->getDesarrolloMadurativoObservacionId();
        if($observacion == null)
            $observacion = '';
        $desarrolloMadurativoPaciente = $this->existeDesarrolloMadurativoPaciente($consulta_id, $paciente_id, $desarrollo_madurativo_id);
        if($desarrolloMadurativoPaciente!=null){
            $desarrolloMadurativoPaciente->checked = 2;
            $desarrolloMadurativoPaciente->observacion = $observacion;
            $desarrolloMadurativoPaciente->save();
        } else {
            $desarrolloMadurativoPaciente = new DesarrolloMadurativoPaciente;
            $desarrolloMadurativoPaciente->paciente_id = $paciente_id;
            $desarrolloMadurativoPaciente->consulta_id = $consulta_id;
            $desarrolloMadurativoPaciente->desarrollo_madurativo_id = $desarrollo_madurativo_id;
            $desarrolloMadurativoPaciente->checked = 2;
            $desarrolloMadurativoPaciente->observacion = $observacion;
            $desarrolloMadurativoPaciente->activo = 1;
            $desarrolloMadurativoPaciente->save();
        }

        return response()->json(array('response'=>1));  
    }

    function nuevoInternaciones(Request $request) {
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        $internaciones_aux = DB::table('internaciones')                                
                                ->where('internaciones.consulta_id', $consulta_id)
                                ->where('internaciones.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('internaciones.activo', 1)
                                           ->orWhere('internaciones.activo', 2);
                                })                                                                
                                ->get();        
        $internaciones = $internaciones_aux->count() + 1;               

        if(($internaciones_id == 0) || ($internaciones_id == -1)) {
            $internaciones = new Internacione;
            $internaciones->consulta_id = $consulta_id;
            $internaciones->paciente_id = $paciente_id;
            $internaciones->antecedentes_personales_id = $antecedentes_personales_id;

            if($request->motivo != null)
                $internaciones->motivo = $request->motivo;
            else
                $internaciones->motivo = "";

            if($request->lugar != null)
                $internaciones->lugar = $request->lugar;
            else
                $internaciones->lugar = "";

            if($request->duracion != null)
                $internaciones->duracion = $request->duracion;
            else
                $internaciones->duracion = "";                
            
            if($request->indicacion_alta != null)
                $internaciones->indicacion_alta = $request->indicacion_alta;
            else
                $internaciones->indicacion_alta = "";

            $internaciones->numero = $numero;
            $internaciones->activo = 1;

        }

        
        return response()->json(array('response'=>1, 'internaciones'=>$internaciones));
    }

    function guardarInternaciones(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        $internaciones_id = $request->internaciones_id;
        $antecedentes_personales_id = $request->antecedentes_personales_id;
        $numero = $request->internaciones_numero;
        if($numero == 0){
            $numero = 1;            
        }

        if(($internaciones_id == 0) || ($internaciones_id == -1)) {
            $internaciones = new Internacione;
            $internaciones->consulta_id = $consulta_id;
            $internaciones->paciente_id = $paciente_id;
            $internaciones->antecedentes_personales_id = $antecedentes_personales_id;

            if($request->motivo != null)
                $internaciones->motivo = $request->motivo;
            else
                $internaciones->motivo = "";

            if($request->lugar != null)
                $internaciones->lugar = $request->lugar;
            else
                $internaciones->lugar = "";

            if($request->duracion != null)
                $internaciones->duracion = $request->duracion;
            else
                $internaciones->duracion = "";                
            
            if($request->indicacion_alta != null)
                $internaciones->indicacion_alta = $request->indicacion_alta;
            else
                $internaciones->indicacion_alta = "";

            $internaciones->numero = $numero;
            $internaciones->activo = 1;

        } else {
            $internaciones = Internacione::find($internaciones_id);
            if($request->motivo != null)
                $internaciones->motivo = $request->motivo;
            else
                $internaciones->motivo = "";

            if($request->lugar != null)
                $internaciones->lugar = $request->lugar;
            else
                $internaciones->lugar = "";

            if($request->duracion != null)
                $internaciones->duracion = $request->duracion;
            else
                $internaciones->duracion = "";                
            
            if($request->indicacion_alta != null)
                $internaciones->indicacion_alta = $request->indicacion_alta;
            else
                $internaciones->indicacion_alta = "";            
        }
        $internaciones->save();

        $antecedentes_personales = AntecedentesPersonales::find($antecedentes_personales_id);
        $antecedentes_personales->internaciones = 1;
        $antecedentes_personales->save();
        
        $internaciones_aux = DB::table('internaciones')                                
                                    ->where('internaciones.consulta_id', $request->consulta)
                                    ->where('internaciones.paciente_id', $request->paciente)
                                    ->where(function ($query) {
                                         $query->where('internaciones.activo', 1)
                                               ->orWhere('internaciones.activo', 2);
                                    })                                                                
                                    ->get();

        $cantidad = $internaciones_aux->count();

        return response()->json(array('response'=>1, 'cantidad' => $cantidad,'internaciones' =>$internaciones));        
    }

    function cargarInternacionesAntSig(Request $request){
        $paciente_id = $request->paciente;
        $consulta_id = $request->consulta;        
        $numero = $request->numero;
        $antecedentes_personales_id = $request->antecedentes_personales_id;
        $internaciones_id = $request->internaciones_id;
        if($numero < 1){
            $numero = 1;
        }
        $cantidadInternaciones_aux = DB::table('internaciones')                                
                                ->where('internaciones.antecedentes_personales_id', $antecedentes_personales_id)
                                ->where('internaciones.paciente_id', $paciente_id)
                                ->where('internaciones.activo', 1)
                                ->get();    
        $cantidadInternaciones = $cantidadInternaciones_aux->count();
        if($numero>$cantidadInternaciones)
            $numero = $cantidadInternaciones;

        $response_data = DB::table('internaciones')                                
                                        ->where('internaciones.paciente_id', $paciente_id)
                                        ->where('internaciones.numero', $numero)
                                        ->where('internaciones.antecedentes_personales_id', $antecedentes_personales_id)
                                        ->where('internaciones.activo', 1)                                        
                                        ->first();

                        
        $cantidadInternacionesFoto_aux = DB::table('internaciones_fotos')                                
                                ->where('internaciones_fotos.internaciones_id', $internaciones_id)                                                            
                                ->where('internaciones_fotos.activo', 1)
                                ->get();    
        $cantidadInternacionesFotos = $cantidadInternacionesFoto_aux->count();        
        $numero_foto = $cantidadInternacionesFotos;

        $response_data_foto = DB::table('internaciones_fotos')                                
                                        ->where('internaciones_fotos.paciente_id', $paciente_id)
                                        ->where('internaciones_fotos.internaciones_id', $internaciones_id)
                                        ->where('internaciones_fotos.numero', $numero_foto)
                                        //->where('internaciones_fotos.antecedentes_personales_id', $antecedentes_personales_id)
                                        ->where('internaciones_fotos.activo', 1)                                        
                                        ->first();
        
        return response()->json(array('response'=>1, 'numero' => $cantidadInternaciones,'internaciones'=>$response_data, 'internacionesFoto'=>$response_data_foto, 'numero_foto'=>$numero_foto));
    }

    function guardarInternacionesFotos(Request $request){
        $consulta_id = $request->internaciones_consulta_id;
        $paciente_id = $request->internaciones_paciente_id;
        $es_nueva_consulta_cargar_fotos_ap = $request->es_nueva_consulta_cargar_fotos_ap;
        $internaciones_id = $request->internaciones_id;        
        $internaciones = Internacione::find($internaciones_id);                      
 
        $cantidadFotos = intval($request->internaciones_cantidad_fotos);
        $usuario_actual_nombre=\Auth::user()->email;   
        $pathFolderMedico_aux = explode('@', $usuario_actual_nombre);
        $pathFolderMedico = $pathFolderMedico_aux[0];
        
        for ($i = 1; $i <= $cantidadFotos; $i++) {
            $request_foto = 'internaciones_foto_'.$i;

            if($request->$request_foto != null){
                
                if($request->hasfile($request_foto)){
                    $internacionesFotos = new InternacionesFotos;
                    $internacionesFotos->activo = 1;
                    $internacionesFotos->paciente_id = $paciente_id;
                    $internacionesFotos->consulta_id = $consulta_id;
                    $internacionesFotos->numero = $this->getNumeroInternacionesFoto($paciente_id, $internaciones->id);
                    if($request->foto_internaciones_id == 0)
                        $internacionesFotos->internaciones_id = $internaciones->id;
                    else
                        $internacionesFotos->internaciones_id = $request->foto_internaciones_id;

                    $pathName = $request->file($request_foto)->store('img/'.$pathFolderMedico.'/internaciones');
                    $name = collect(explode('/', $pathName))->last();
                    $image = $request->file($request_foto);
                    //$name  = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
                    $path = 'img/'.$pathFolderMedico.'/internaciones/'.$name;        
                    Image::make($image->getRealPath())->resize(1980, 1920)->save($path);  
                    $internacionesFotos->foto = $pathFolderMedico.'/internaciones/'.$name;
                    $internacionesFotos->save();           
                }               
            } 
        }

        $user=\Auth::user();   
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $medicoInfo = MedicoInfo::find($medicoInfoAux->id); 
        if($es_nueva_consulta_cargar_fotos_ap != null)       
            $medicoInfo->es_nueva_consulta = 4;
        else
            $medicoInfo->es_nueva_consulta = 3;
        $medicoInfo->save();
        
        return redirect()->route('navegarconsultaseleccionada');
        
    }

    function getNumeroInternacionesFoto($paciente_id, $internacionesId){
        $internaciones_aux = DB::table('internaciones_fotos')
                                ->where('internaciones_fotos.internaciones_id', $internacionesId)
                                ->where('internaciones_fotos.paciente_id', $paciente_id)
                                ->where(function ($query) {
                                     $query->where('internaciones_fotos.activo', 1)
                                           ->orWhere('internaciones_fotos.activo', 2);
                                })                                                                
                                ->get();  
        return $internaciones_aux->count()+1;
    }

    function cargarInternacionesAntSigFoto(Request $request){
        $paciente_id = $request->paciente;
        $internaciones_id = $request->internaciones_id;
        $numero = $request->numero;
        if($numero < 1){
            $numero = 1;
        }
        $cantidadinternacionesFoto_aux = DB::table('internaciones_fotos')                                
                                ->where('internaciones_fotos.internaciones_id', $internaciones_id)
                                ->where('internaciones_fotos.paciente_id', $paciente_id)                                
                                ->where('internaciones_fotos.activo', 1)
                                ->get();    
        $cantidadInternacionesFotos = $cantidadinternacionesFoto_aux->count();
        if($numero>$cantidadInternacionesFotos)
            $numero = $cantidadInternacionesFotos;

        $response_data = DB::table('internaciones_fotos')                                
                                        ->where('internaciones_fotos.paciente_id', $paciente_id)
                                        ->where('internaciones_fotos.internaciones_id', $internaciones_id)
                                        ->where('internaciones_fotos.numero', $numero)
                                        ->where('internaciones_fotos.activo', 1)                                        
                                        ->first();

        return response()->json(array('response'=>1, 'tope' => $cantidadInternacionesFotos,'internacionesFoto'=>$response_data));
    }

    function getFotosInternaciones(Request $request){
        $fotosInternaciones = DB::table('internaciones_fotos')                                
                                ->where('internaciones_fotos.paciente_id', $request->paciente)
                                ->where('internaciones_fotos.internaciones_id', $request->internaciones_id)
                                ->where(function ($query) {
                                     $query->where('internaciones_fotos.activo', 1)
                                           ->orWhere('internaciones_fotos.activo', 2);
                                })                                
                                ->orderBy('id','desc')
                                ->first();
        return response()->json(array('response'=>1, 'fotosInternaciones'=>$fotosInternaciones));               
    }

    function cargarFotos(){
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();         
        $listadoConsultas = DB::table('consultas')
                            ->select('consultas.*')
                            ->join('medico_pacientes', 'medico_pacientes.paciente_id', 'consultas.paciente_id')
                            ->where('medico_pacientes.medico_user_id', $user->id)
                            ->where('consultas.medico_id', $user->id)
                            ->where('consultas.paciente_id', $medicoInfoAux->paciente_id)
                            ->where('consultas.activo', 1)
                            ->distinct()
                            ->orderby('consultas.id','desc')
                            ->get();    
        
        $paciente = Paciente::find($medicoInfoAux->paciente_id);                    
        $consulta = null;
        $response = 0; 
        
        if($listadoConsultas->count() > 0){
            $consulta = $listadoConsultas[0];
            $response = 1; 
        }
        if($response == 1){
            return view('medico.cargar_fotos')
                        ->with('response', $response)                                                    
                        ->with('consulta', $consulta)                
                        ->with('paciente', $paciente)  
                        ->with('nueva_consulta', 4)  
                        ->with('listadoConsultas', $listadoConsultas);                         
        } else {
            $mensaje = "El paciente no tiene consultas.";
            return view('medico.mostrar_mensaje')
                        ->with('mensaje', $mensaje);
        }
    }


    function listadoPacientesDia(){
        $user=\Auth::user(); 
        $client = new Client([
            'base_uri' => 'https://turnosonlinebb.com',
        ]);

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");        
        //$fecha = '2020-08-26';
        $medico = $user->medico_id_tobb;
        if($medico != 0){
            $response = $client->request('GET', 'generar_listado_dia_tobb/'.$fecha.'/'.$medico);            
            $posts = json_decode($response->getBody()->getContents(), true);        
           // foreach($posts as $value) {
                //$this->crearPacienteTOBB($value, $medico);                
           // }
        } else {
            $posts = null;   
        }
        
        // $posts = null; 
        return view('medico.listado_pacientes_dia')
                    ->with('medico_id', $user->id)
                    ->with('listadoPacientes', $posts);
    }

    function crearPacienteTOBB($paciente, $medico_id) {
        if($paciente['dni'] != 99999){
            $existePaciente = DB::table('pacientes')
                        ->where('pacientes.dni', $paciente['dni'])
                        ->where('pacientes.activo', 1)
                        ->first();
            if($existePaciente == null){
                $nuevoPaciente = new Paciente;
                $nuevoPaciente->nombre = $paciente['nombre'];
                $nuevoPaciente->apellido = $paciente['apellido'];
                $nuevoPaciente->dni = $paciente['dni'];
                $nuevoPaciente->telefono = $paciente['telefono'];
                $nuevoPaciente->domicilio = $paciente['domicilio'];
                $nuevoPaciente->mail = $paciente['mail'];
                $nuevoPaciente->fecha_nacimiento = $paciente['fecha_nacimiento'];
                $nuevoPaciente->obra_social = $paciente['obra_social'];
                $nuevoPaciente->numero_afiliado = $paciente['numero_afiliado'];
                $nuevoPaciente->obra_social_plan = $paciente['obra_social_plan'];
                $nuevoPaciente->obra_social_foto = $paciente['obra_social_foto']; 
                $nuevoPaciente->sexo = '';
                $nuevoPaciente->nombre_padre = '';
                $nuevoPaciente->nombre_madre = '';
                $nuevoPaciente->cantidad_hermanos = 0;
                $nuevoPaciente->localidad = '';
                $nuevoPaciente->activo = 1;
                $nuevoPaciente->save(); 

                $medicoPaciente = new MedicoPaciente;
                $medicoPaciente->medico_user_id = $medico_id;  
                $medicoPaciente->paciente_id = $nuevoPaciente->id;
                $medicoPaciente->activo = 1;
                $medicoPaciente->save();            
            }
        }
    }

    function pacienteConsultarTobb(Request $request){
        $medico_id = $request->medico_id;
        $paciente_dni = $request->dni;

        $existePaciente = DB::table('pacientes')
                        ->where('pacientes.dni', $paciente_dni)
                        ->where('pacientes.activo', 1)
                        ->first();
        if($existePaciente!=null){        
            $paciente = $existePaciente;                                    
            $medicoInfoAux = DB::table('medico_infos')
                                ->where('medico_infos.medico_user_id', $medico_id)
                                ->first();
             $medicoInfo = MedicoInfo::find($medicoInfoAux->id);
             $medicoInfo->paciente_id = $paciente_id;
             $medicoInfo->save();
             $response = 1;         
        } else {
            // en caso de que no exista el paciente
            
            $client = new Client([
              'base_uri' => 'https://turnosonlinebb.com',
            ]);
            $response = $client->request('GET', 'get_paciente_tobb/'.$paciente_dni);            
            $posts = json_decode($response->getBody()->getContents(), true);
            //foreach($posts as $value) {
            //    $this->crearPacienteTOBB($value, $medico_id);                
            //}
            $paciente = $posts[0];
            $response = 2;
        }

        return response()->json(array('response'=>$response, 'paciente'=>$paciente));
    }

    function nuevoScreening(Request $request){
        $id = $request->id;
        $fechaSolicitud_aux = $request->fechaSolicitud;
        $evaluacion = $request->evaluacion;
        $respuesta = $request->respuesta;

        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);

        $numero = DB::table('screenings')
                            ->where('screenings.paciente_id', $paciente->id)
                            ->where('screenings.activo', 1)
                            ->get()->count();

        if($fechaSolicitud_aux != null){
            $fechaSolicitud_array = explode("/", $fechaSolicitud_aux);
            $fechaSolicitud = $fechaSolicitud_array[2]."-".$fechaSolicitud_array[1]."-".$fechaSolicitud_array[0];
        } else {
            $fechaSolicitud = '1900-01-01';
        }
        
        $numero += 1;
        if($id == null){
            $screening = new Screening;        
            $screening->numero = $numero;
            $screening->paciente_id = $paciente->id;    
        } else {
            $screening = Screening::find($id);        
        }    
        if($evaluacion != null)
            $screening->evaluacion = $evaluacion;
        else
            $screening->evaluacion = '';

        if($fechaSolicitud != null)
            $screening->fechaSolicitud = $fechaSolicitud;
        else
            $screening->fechaSolicitud = '1900-01-01';

        if($respuesta != null)
            $screening->respuesta = $respuesta;
        else
            $screening->respuesta = '';
        $screening->activo = 1;
        $screening->save();

        $mostrarTopeNuevo = 0;
        if($id == null){
            $mostrarTopeNuevo = 1;
        }

        return response()->json(array('paciente'=>$paciente, 'tope'=>$numero, 'mostrarTopeNuevo'=>$mostrarTopeNuevo, 'screening'=>$screening));
    }

    function filtrarScreening(Request $request){
        $texto = $request->texto;
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);

        $screening = DB::table('screenings')
                            ->where('screenings.paciente_id', $paciente->id)
                            ->where('screenings.evaluacion', 'LIKE', '%' . $texto . '%')
                            ->where('screenings.activo', 1)
                            ->orderby('screenings.id', 'desc')
                            ->get();

        $cantidad = $screening->count();        

        return response()->json(array('paciente'=>$paciente, 'tope'=>$cantidad, 'screenings'=>$screening));
    }

    function getPacienteSeleccionado(Request $request){
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);

        $screening = DB::table('screenings')
                            ->where('screenings.paciente_id', $paciente->id)
                            ->where('screenings.activo', 1)
                            ->orderby('screenings.id', 'desc')
                            ->get();

        $cantidad = $screening->count();        

        return response()->json(array('paciente'=>$paciente, 'tope'=>$cantidad, 'screenings'=>$screening));
    }

    function anteriorSiguienteScreening(Request $request){
        $texto = $request->texto;
        $numero = $request->numero;
        $user=\Auth::user();                
        $medicoInfoAux = DB::table('medico_infos')
                            ->where('medico_infos.medico_user_id', $user->id)
                            ->first();
        $paciente = Paciente::find($medicoInfoAux->paciente_id);
        if(strcmp($texto, '') != 0){
            $screening = DB::table('screenings')
                            ->where('screenings.paciente_id', $paciente->id)
                            ->where('screenings.evaluacion', 'LIKE', '%' . $texto . '%')
                            ->where('screenings.activo', 1)                            
                            ->get();
        } else {
        $screening = DB::table('screenings')
                            ->where('screenings.paciente_id', $paciente->id)
                            ->where('screenings.activo', 1)
                            //->orderby('screenings.id', 'desc')
                            ->get();
        }
        $cantidad = $screening->count();
        $numero--; 
        if($numero < 0)
            $numero = 0;
        if($numero > $cantidad-1)
            $numero = $cantidad-1;
    
        $screening = $screening[$numero];
        

        return response()->json(array('paciente'=>$paciente, 'tope'=>$cantidad, 'numero'=>$numero, 'screenings'=>$screening));
    }


    function verLicenciaExpira(Request $request){
        $user=\Auth::user(); 
        $licencia = DB::table('medico_licencias')
                            ->where('medico_licencias.medico_user_id', $user->id)                                   
                            ->first();
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");  
        $aviso = 0;
        if($fecha >= $licencia->fecha_aviso_expiracion)
            $aviso = 1;        
        return response()->json(array('licencia'=>$licencia, 'aviso'=>$aviso));   
    }

    function guardarLactanciaEmbarazoPrevio(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeTabla('lactancia_embarazo_previos', $consulta_id, $paciente_id) == null) { 
            $lactancia = new LactanciaEmbarazoPrevio;
        } else {
            $lactancia_aux = $this->existeTabla('lactancia_embarazo_previos',$consulta_id, $paciente_id);
            $lactancia = LactanciaEmbarazoPrevio::find($lactancia_aux->id);
        }

        $lactancia->consulta_id = $consulta_id;
        $lactancia->paciente_id = $paciente_id;
        
        if($request->descripcion != null)
            $lactancia->descripcion = $request->descripcion;
        else
            $lactancia->descripcion = "";    

        $lactancia->activo = 1;
        $lactancia->save();

        return response()->json(array('response'=>1, 'request'=>$request));        
    }

    function existeTabla($tabla, $consulta_id, $paciente_id){
        $conductas = DB::table($tabla)
                                ->where($tabla.'.consulta_id', $consulta_id)
                                ->where($tabla.'.paciente_id', $paciente_id)
                                ->where($tabla.'.activo', 1)                                
                                ->first();
        return $conductas;
    }

    function guardarDetalleConsultaLactancia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeTabla('lactancias', $consulta_id, $paciente_id) == null) { 
            $lactancia = new Lactancia;
        } else {
            $lactancia_aux = $this->existeTabla('lactancias',$consulta_id, $paciente_id);
            $lactancia = Lactancia::find($lactancia_aux->id);
        }

        if($request->descripcion != null)
            $lactancia->descripcion = $request->descripcion;
        else
            $lactancia->descripcion = "";

        $lactancia->consulta_id = $consulta_id;
        $lactancia->paciente_id = $paciente_id;
        $lactancia->activo = 1;
        $lactancia->save();

        return response()->json(array('response'=>1, 'request'=>$request));

    }

    function cargarDetalleConsultaLactancia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('lactancias')
                        ->where('lactancias.consulta_id', $consulta_id)
                        ->where('lactancias.paciente_id', $paciente_id)
                        ->where('lactancias.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));        
    }

    function guardarFamilia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeTabla('familias', $consulta_id, $paciente_id) == null) { 
            $familia = new Familia;
        } else {
            $familia_aux = $this->existeTabla('familias',$consulta_id, $paciente_id);
            $familia = Familia::find($familia_aux->id);
        }

        $familia->consulta_id = $consulta_id;
        $familia->paciente_id = $paciente_id;
        
        if($request->cp_familia_mama != null)
            $familia->mama = $request->cp_familia_mama;
        else
            $familia->mama = "";    

        if($request->cp_familia_mama_edad != null)
            $familia->mama_edad = $request->cp_familia_mama_edad;
        else
            $familia->mama_edad = "";    

        if($request->cp_familia_mama_ocupacion != null)
            $familia->mama_ocupacion = $request->cp_familia_mama_ocupacion;
        else
            $familia->mama_ocupacion = "";    

        if($request->cp_familia_papa != null)
            $familia->papa = $request->cp_familia_papa;
        else
            $familia->papa = "";    

        if($request->cp_familia_papa_edad != null)
            $familia->papa_edad = $request->cp_familia_papa_edad;
        else
            $familia->papa_edad = "";    

        if($request->cp_familia_papa_ocupacion != null)
            $familia->papa_ocupacion = $request->cp_familia_papa_ocupacion;
        else
            $familia->papa_ocupacion = "";    

        if($request->cp_familia_bebe != null)
            $familia->bebe = $request->cp_familia_bebe;
        else
            $familia->bebe = "";    

        if($request->cp_familia_bebe_eg != null)
            $familia->eg = $request->cp_familia_bebe_eg;
        else
            $familia->eg = "";    

        if($request->cp_familia_bebe_fpp != null)
            $familia->fpp = $request->cp_familia_bebe_fpp;
        else
            $familia->fpp = "";    

        $familia->hermanos = $request->hermanos;
        $familia->activo = 1;
        $familia->save();

        return response()->json(array('response'=>1, 'request'=>$request));            
    }

    function guardarEmbarazoActual(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeTabla('embarazo_actuals', $consulta_id, $paciente_id) == null) { 
            $embarazoActual = new EmbarazoActual;
        } else {
            $embarazoActual_aux = $this->existeTabla('embarazo_actuals',$consulta_id, $paciente_id);
            $embarazoActual = EmbarazoActual::find($embarazoActual_aux->id);
        }

        $embarazoActual->consulta_id = $consulta_id;
        $embarazoActual->paciente_id = $paciente_id;
        
        if($request->embarazo_actual_obstetra != null)
            $embarazoActual->obstetra = $request->embarazo_actual_obstetra;
        else
            $embarazoActual->obstetra = "";

        if($request->embarazo_actual_eg != null)
            $embarazoActual->eg = $request->embarazo_actual_eg;
        else
            $embarazoActual->eg = "";    
        
        if($request->embarazo_actual_n_controles != null)
            $embarazoActual->n_controles = $request->embarazo_actual_n_controles;
        else
            $embarazoActual->n_controles = "";

        if($request->embarazo_actual_vacunas != null)
            $embarazoActual->vacunas = $request->embarazo_actual_vacunas;
        else
            $embarazoActual->vacunas = "";    

        if($request->embarazo_actual_observaciones != null)
            $embarazoActual->observaciones = $request->embarazo_actual_observaciones;
        else
            $embarazoActual->observaciones = "";    

        if($request->embarazo_actual_ecografias != null)
            $embarazoActual->ecografia = $request->embarazo_actual_ecografias;
        else
            $embarazoActual->ecografia = "";    

        $embarazoActual->serologia_1 = $request->serologia_1;
        if($request->serologia_1_detalle != null)
            $embarazoActual->serologia_1_detalle = $request->serologia_1_detalle;
        else
            $embarazoActual->serologia_1_detalle = "";    

        $embarazoActual->serologia_2 = $request->serologia_2;
        if($request->serologia_2_detalle != null)
            $embarazoActual->serologia_2_detalle = $request->serologia_2_detalle;
        else
            $embarazoActual->serologia_2_detalle = "";    

        $embarazoActual->hisop_sbhb = $request->hisopsbha;
        if($request->hisopsbha_detalle != null)
            $embarazoActual->hisop_sbhb_detalle = $request->hisopsbha_detalle;
        else
            $embarazoActual->hisop_sbhb_detalle = "";    

        $embarazoActual->ptog = $request->ptog;
        if($request->ptog_detalle != null)
            $embarazoActual->ptog_detalle = $request->ptog_detalle;
        else
            $embarazoActual->ptog_detalle = "";    

        $embarazoActual->parto = $request->parto;
        if($request->cesarea_detalle != null)
            $embarazoActual->cesarea_detalle = $request->cesarea_detalle;
        else
            $embarazoActual->cesarea_detalle = "";    
        
        $embarazoActual->activo = 1;
        $embarazoActual->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function guardarAntecedentesObstetricos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;

        if($this->existeTabla('antecedentes_obstetricos', $consulta_id, $paciente_id) == null) { 
            $antecedentesObstetrico = new AntecedentesObstetrico;
        } else {
            $antecedentesObstetrico_aux = $this->existeTabla('antecedentes_obstetricos',$consulta_id, $paciente_id);
            $antecedentesObstetrico = AntecedentesObstetrico::find($antecedentesObstetrico_aux->id);
        }

        $antecedentesObstetrico->consulta_id = $consulta_id;
        $antecedentesObstetrico->paciente_id = $paciente_id;
        
        if($request->ant_obstetricos_g != null)
            $antecedentesObstetrico->g = $request->ant_obstetricos_g;
        else
            $antecedentesObstetrico->g = -1;

        if($request->ant_obstetricos_p != null)
            $antecedentesObstetrico->p = $request->ant_obstetricos_p;
        else
            $antecedentesObstetrico->p = -1;

        if($request->ant_obstetricos_a != null)
            $antecedentesObstetrico->a = $request->ant_obstetricos_a;
        else
            $antecedentesObstetrico->a = -1;

        if($request->ant_obstetricos_detalles != null)
            $antecedentesObstetrico->descripcion = $request->ant_obstetricos_detalles;
        else
            $antecedentesObstetrico->descripcion = "";
        
        $antecedentesObstetrico->activo = 1;
        $antecedentesObstetrico->save();

        return response()->json(array('response'=>1, 'request'=>$request));
    }

    function cargarFamilia(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('familias')
                        ->where('familias.consulta_id', $consulta_id)
                        ->where('familias.paciente_id', $paciente_id)
                        ->where('familias.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));        
    }   

    function cargarEmbarazoActual(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('embarazo_actuals')
                        ->where('embarazo_actuals.consulta_id', $consulta_id)
                        ->where('embarazo_actuals.paciente_id', $paciente_id)
                        ->where('embarazo_actuals.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));        
    }

    function cargarAntecedentesObstetricos(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('antecedentes_obstetricos')
                        ->where('antecedentes_obstetricos.consulta_id', $consulta_id)
                        ->where('antecedentes_obstetricos.paciente_id', $paciente_id)
                        ->where('antecedentes_obstetricos.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));        
    }

    function cargarLactanciaEmbarazoPrevio(Request $request){
        $consulta_id = $request->consulta;
        $paciente_id = $request->paciente;
        
        $tablaAux = DB::table('lactancia_embarazo_previos')
                        ->where('lactancia_embarazo_previos.consulta_id', $consulta_id)
                        ->where('lactancia_embarazo_previos.paciente_id', $paciente_id)
                        ->where('lactancia_embarazo_previos.activo', 1)
                        ->first();        
        $response = 0;
        if($tablaAux!=null){
            $response = 1;
        } 
        return response()->json(array('response'=>$response, 'response_data' =>$tablaAux));        
    }
}
