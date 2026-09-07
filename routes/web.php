<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/original', function () {
    return view('plantillas.plantilla_original');
});


Route::get('/licencia_expirada', function () {
	return view('auth.licencia_expirada');   
})->name('licencia_expirada');

Route::get('/aviso_licencia_expirada', function () {
	return view('auth.aviso_licencia_expirada');   
})->name('aviso_licencia_expirada');

Route::get('/', function () {
	return view('auth.login');
   // return view('plantillas.plantilla_medico');
});

Route::get('/home', 'HomeController@index')->name('home');

// Aca van a ir todas las rutas que tiene acceso un administrador.
Route::group(['middleware' => ['usuarioAdmin']], function () {

	Route::post('/seleccionar_medico_admin', 'AdminController@seleccionarMedicoAdmin')->name('seleccionarmedicoadmin');

	Route::get('/admin_home', function () {
	    return view('plantillas.plantilla_admin');
	})->name('admin_home');

	Route::get('/admin_nuevo_usuario', function () {
	    return view('admin.nuevo_usuario');
	});

	Route::get('/admin_secretaria', function () {
		$secretarias = DB::table('users')->where('usuario_tipo', 3)->get();
		$medicos = DB::table('users')->where('usuario_tipo', 2)->get();

    	return View('admin.admin_secretaria')
    	->with('secretarias',$secretarias)
    	->with('medicos',$medicos);   
    });

	Auth::routes();

	Route::get('/admin_medicos', 'AdminController@adminMedicos')->name('adminmedicos');

	Route::post('/registrar', 'Auth\RegisterController@registrar')->name('registrar');

	Route::post('/vincular_secretaria_medico', 'AdminController@vincularSecretariaMedico')->name('vincularsecretariamedico');

	Route::post('/update_exp_licencia', 'AdminController@updateExpLicencia')->name('updateexplicencia');

	Route::post('/update_aviso_licencia', 'AdminController@updateAvisoLicencia')->name('updateavisolicencia');

	Route::post('/update_importe', 'AdminController@updateImporte')->name('updateimporte');

	Route::post('/update_activo', 'AdminController@updateActivo')->name('updateactivo');
	
 });

// Aca van a ir todas las rutas que tiene acceso un medico.
Route::group(['middleware' => ['usuarioMedico']], function () {

	/*Route::get('/medico_home', function () {
	    return view('plantillas.plantilla_medico');
	})->name('medico_home');
*/
	Route::get('medico_home','MedicoController@listadoPacientesDia')->name('medico_home');

	Route::get('/medico_nuevo_paciente', function () {
	    return view('medico.nuevo_paciente')->with('medico_id', Auth::user()->id);
	});

	Route::get('/medico_buscar_paciente', function () {
	    $user = \Auth::user();	
	    return view('medico.buscar_paciente')->with('medico_id', $user->id);
	});	

	Route::get('medico_actualizar_paciente','PacienteController@actualizarPaciente')->name('medicoactualizarpaciente');

	Route::post('medico_actualizar_paciente_buscar','PacienteController@actualizarPacienteBuscar')->name('medicoactualizarpacientebuscar');

	Route::post('nueva_consulta','MedicoController@nuevaConsulta')->name('nuevaconsulta');

	Route::get('consulta/{paciente}/{consulta}','MedicoController@consultaGet')->name('consulta');	

	Route::get('interconsultores','MedicoController@interconsultores')->name('interconsultores');
	
	Route::post('get_interconsultor','MedicoController@getInterconsultor')->name('getinterconsultor');

	Route::post('modificar_interconsultor','MedicoController@modificarInterconsultor')->name('modificarinterconsultor');

	Route::post('eliminar_interconsultor','MedicoController@eliminarInterconsultor')->name('eliminarinterconsultor');
	
	Route::post('filtrar_interconsultores','MedicoController@filtrarInterconsultores')->name('filtrarinterconsultores');

	Route::get('certficado_aptitud_fisico_seleccionar','MedicoController@certficadoAptitudFisicoSeleccionar')->name('certficadoaptitudfisicoseleccionar');
	
	Route::post('navegar_certificado_aptitud_fisico','MedicoController@navegarCertificadoAptitudFisico')->name('navegarcertificadoaptitudfisico');
	
	Route::post('navegar_resumen_historia_clinica','MedicoController@navegarResumenHistoriaClinica')->name('navegarresumenhistoriaclinica');

	Route::get('caf_seleccionar_listado','MedicoController@cafSeleccionarListado')->name('cafseleccionarlistado');
	
	Route::get('certficado_aptitud_fisico','MedicoController@certficadoAptitudFisico')->name('certficadoaptitudfisico');

	Route::get('resumen_historia_clinica','MedicoController@resumenHistoriaClinica')->name('resumenhistoriaclinica');		

	Route::post('ir_paciente_historia_clinica','MedicoController@irPacienteHistoriaClinica')->name('irpacientehistoriaclinica');
	
	Route::post('guardar_antecedentes_perinatales','MedicoController@guardarAntecedentesPerinatales')->name('guardarantecedentesperinatales');

	Route::post('guardar_antecedentes_personales','MedicoController@guardarAntecedentesPersonales')->name('guardarantecedentespersonales');
	
	Route::post('nuevo_internaciones','MedicoController@nuevoInternaciones')->name('nuevointernaciones');
	
	Route::post('guardar_internaciones','MedicoController@guardarInternaciones')->name('guardarinternaciones');

	Route::post('guardar_antecedentes_familiares','MedicoController@guardarAntecedentesFamiliares')->name('guardarantecedentesfamiliares');
	
	Route::post('guardar_escolaridad','MedicoController@guardarEscolaridad')->name('guardarescolaridad');
	
	Route::post('guardar_actividades_extra_escolares','MedicoController@guardarActividadesExtraEscolares')->name('guardaractividadesextraescolares');
	
	Route::post('guardar_pantallas','MedicoController@guardarPantallas')->name('guardarpantallas');
	
	Route::post('guardar_habitos','MedicoController@guardarHabitos')->name('guardarhabitos');
	
	Route::post('guardar_menarca','MedicoController@guardarMenarca')->name('guardarmenarca');
	
	Route::post('guardar_conductas','MedicoController@guardarConductas')->name('guardarconductas');
	Route::post('guardar_observaciones','MedicoController@guardarObservaciones')->name('guardarobservaciones');

	Route::post('guardar_datos_subjetivos','MedicoController@guardarDatosSubjetivos')->name('guardardatossubjetivos');

	Route::post('guardar_datos_objetivos','MedicoController@guardarDatosObjetivos')->name('guardardatosobjetivos');
	
	Route::post('guardar_alimentacion','MedicoController@guardarAlimentacion')->name('guardaralimentacion');
	
	Route::post('guardar_examen_fisico','MedicoController@guardarExamenFisico')->name('guardarexamenfisico');
	
	Route::post('guardar_interconsulta','MedicoController@guardarInterconsulta')->name('guardarinterconsulta');
	
	Route::post('guardar_examenes_complementarios_fotos','MedicoController@guardarExamenesComplementariosFotos')->name('guardarexamenescomplementariosfotos');

	Route::post('guardar_examenes_complementarios','MedicoController@guardarExamenesComplementarios')->name('guardarexamenescomplementarios');
	
	Route::post('cargar_internaciones_ant_sig','MedicoController@cargarInternacionesAntSig')->name('cargarinternacionesantsig');
	
	Route::post('guardar_internaciones_fotos','MedicoController@guardarInternacionesFotos')->name('guardarinternacionesfotos');
	
	Route::post('cargar_internaciones_ant_sig_foto','MedicoController@cargarInternacionesAntSigFoto')->name('cargarinternacionesantsigfoto');

	Route::post('cargar_examenes_complementarios','MedicoController@cargarExamenesComplementarios')->name('cargarexamenescomplementarios');

	Route::post('cargar_examenes_complementarios_ant_sig_foto','MedicoController@cargarExamenesComplementariosAntSigFoto')->name('cargarexamenescomplementariosantsigfoto');
	
	Route::post('get_fotos_internaciones','MedicoController@getFotosInternaciones')->name('getfotosinternaciones');

	Route::post('get_examenes_complementarios_ultimo','MedicoController@getExamenesComplementariosUltimo')->name('getexamenescomplementariosultimo');
	
	Route::post('nuevo_examen_complementario','MedicoController@nuevoExamenComplementario')->name('nuevoexamencomplementario');
	
	Route::post('inicializar_antecedentes_personales','MedicoController@inicializarAntecedentesPersonales')->name('inicializarantecedentespersonales');

	Route::post('cargar_examenes_complementarios_ant_sig','MedicoController@cargarExamenesComplementariosAntSig')->name('cargarexamenescomplementariosantsig');
	
	Route::post('get_fotos_examenes_complementarios','MedicoController@getFotosExamenesComplementarios')->name('getfotosexamenescomplementarios');
	
	Route::post('guardar_nota_antecedentes_neonatales_patologicos','MedicoController@guardarNotaAntecedentesNeonatalesPatologicos')->name('guardarnotaantecedentesneonatalespatologicos');
	
	Route::post('cargar_ant_personales_ant_sig','MedicoController@cargarAntPersonalesAntSig')->name('cargarantpersonalesantsig');

	Route::post('guardar_antecedentes_neonatales','MedicoController@guardarAntecedentesNeonatales')->name('guardarantecedentesneonatales');

	Route::post('cargar_antecedentes_neonatales_ant_sig_foto','MedicoController@cargarAntecedentesNeonatalesAntSigFoto')->name('cargarantecedentesneonatalesantsigfoto');
	
	Route::post('get_fotos_antecedentes_neonantales','MedicoController@getFotosAntecedentesNeonantales')->name('getfotosantecedentesneonantales');
		
	Route::post('ant_personales_nuevo','MedicoController@antPersonalesNuevo')->name('antpersonalesnuevo');
	
	Route::post('guardar_pendientes_cancelar','MedicoController@guardarPendientesCancelar')->name('guardarpendientescancelar');

	Route::post('guardar_vacunas_dos','MedicoController@guardarVacunasDos')->name('guardarvacunasdos');
	
	Route::post('guardar_nota','MedicoController@guardarNota')->name('guardarnota');

	Route::post('guardar_vacuna_paciente','MedicoController@guardarVacunaPaciente')->name('guardarvacunapaciente');

	Route::post('cargar_vacuna_paciente','MedicoController@cargarVacunaPaciente')->name('cargarvacunapaciente');
	
	Route::post('guardar_vacuna_otra_paciente','MedicoController@guardarVacunaOtraPaciente')->name('guardarvacunaotrapaciente');

	Route::post('cargar_vacunas_dos','MedicoController@cargarVacunasDos')->name('cargarvacunasdos');

	Route::post('cargar_nota','MedicoController@cargarNota')->name('cargarnota');
	
	Route::post('get_consulta','MedicoController@getConsulta')->name('getconsulta');

	Route::post('cargar_desarrollo_madurativo_consulta','MedicoController@cargarDesarrolloMadurativoConsulta')->name('cargardesarrollomadurativoconsulta');
	
	Route::post('cargar_motivo_consulta','MedicoController@cargarMotivoConsulta')->name('cargarmotivoconsulta');

	Route::post('guardar_desarrollo_madurativo_edad','MedicoController@guardarDesarrolloMadurativoEdad')->name('guardardesarrollomadurativoedad');
	
	Route::get('consulta','MedicoController@consultarPaciente')->name('consulta');

	Route::get('nueva_consulta_paciente_listado_buscar','MedicoController@nuevaConsultaPacienteListadoBuscar')->name('nuevaconsultapacientelistadobuscar');

		Route::get('nueva_consulta_paciente','MedicoController@nuevaConsultaPaciente')->name('nuevaconsultapaciente');	
	
	Route::get('nueva_consulta_opciones','MedicoController@nuevaConsultaOpciones')->name('nuevaconsultaopciones');
	
	Route::post('crear_nueva_consulta','MedicoController@crearNuevaConsulta')->name('crearnuevaconsulta');	
	
	Route::get('crear_nueva_consulta_foto','MedicoController@crearNuevaConsultaFoto')->name('crearnuevaconsultafoto');	
	
	Route::post('guardar_motivo_consulta','MedicoController@guardarMotivoConsulta')->name('guardarmotivoconsulta');	
	
	Route::post('guardar_nueva_consulta_foto','MedicoController@guardarNuevaConsultaFoto')->name('guardarnuevaconsultafoto');	

	Route::post('get_fotos_nueva_consulta','MedicoController@getFotosNuevaConsulta')->name('getfotosnuevaconsulta');	

	Route::post('cargar_fotos_nueva_consulta_ant_sig_foto','MedicoController@cargarFotosNuevaConsultaAntSigFoto')->name('cargarfotosnuevaconsultaantsigfoto');	
	
	Route::get('paciente_consultas/{paciente_id}','MedicoController@pacienteConsultas')->name('pacienteconsultas');

	Route::get('paciente_consultas_listado','MedicoController@pacienteConsultasListado')->name('pacienteconsultaslistado');
	
	Route::post('navegar_consultas','MedicoController@navegarConsultas')->name('navegarconsultas');
	
	Route::get('navegar_consulta_seleccionada','MedicoController@navegarConsultaSeleccionada')->name('navegarconsultaseleccionada');
	
	Route::post('guardar_consulta_medico_info','MedicoController@guardarConsultaMedicoInfo')->name('guardarconsultamedicoinfo');
	
	Route::post('establecer_activo','MedicoController@establecerActivo')->name('estableceractivo');
	Route::post('desactivar_consulta','MedicoController@desactivarConsulta')->name('desactivarconsulta');

	Route::post('check_consulta_existe','MedicoController@checkConsultaExiste')->name('checkconsultaexiste');

	Route::post('cargar_escolaridad','MedicoController@cargarEscolaridad')->name('cargarescolaridad');
	
	Route::post('cargar_actividades_extra_escolares','MedicoController@cargarActividadesExtraEscolares')->name('cargaractividadesextraescolares');
	
	Route::post('cargar_pantallas','MedicoController@cargarPantallas')->name('cargarpantallas');
	
	Route::post('cargar_habitos','MedicoController@cargarHabitos')->name('cargarhabitos');

	Route::post('cargar_diuresis_catarsis','MedicoController@cargarDiuresisCatarsis')->name('cargardiuresiscatarsis');

	Route::post('guardar_diuresis_catarsis','MedicoController@guardarDiuresisCatarsis')->name('guardardiuresiscatarsis');

	Route::post('cargar_somnia','MedicoController@cargarSomnia')->name('cargarsomnia');

	Route::post('guardar_somnia','MedicoController@guardarSomnia')->name('guardarsomnia');
	
	Route::post('cargar_conductas','MedicoController@cargarConductas')->name('cargarconductas');
	Route::post('cargar_observaciones','MedicoController@cargarObservaciones')->name('cargarobservaciones');
	
	Route::post('cargar_datos_subjetivos','MedicoController@cargarDatosSubjetivos')->name('cargardatossubjetivos');

	Route::post('cargar_datos_objetivos','MedicoController@cargarDatosObjetivos')->name('cargardatosobjetivos');

	Route::post('cargar_examen_fisico','MedicoController@cargarExamenFisico')->name('cargarexamenfisico');
	
	Route::post('cargar_alimentacion','MedicoController@cargarAlimentacion')->name('cargaralimentacion');
	
	Route::post('cargar_menarca','MedicoController@cargarMenarca')->name('cargarmenarca');
	
	Route::post('cargar_antecedentes_perinatales','MedicoController@cargarAntecedentesPerinatales')->name('cargarantecedentesperinatales');
	
	Route::post('cargar_antecedentes_personales','MedicoController@cargarAntecedentesPersonales')->name('cargarantecedentespersonales');
	
	Route::post('cargar_antecedentes_familiares','MedicoController@cargarAntecedentesFamiliares')->name('cargarantecedentesfamiliares');
	
	Route::post('cargar_antecedentes_neonantales_nota','MedicoController@cargarAntecedentesNeonantalesNota')->name('cargarantecedentesneonantalesnota');
	
	Route::post('cargar_numero_interconsulta','MedicoController@cargarNumeroInterconsulta')->name('cargarnumerointerconsulta');
	
	Route::post('cargar_interconsulta','MedicoController@cargarInterconsulta')->name('cargarinterconsulta');
	
	Route::post('guardar_interconsultores','MedicoController@guardarinterconsultores')->name('guardarinterconsultores');
	
	//Route::get('interconsultores_listado_buscar','MedicoController@interconsultoresListadoBuscar')->name('interconsultoreslistadobuscar');
	
	Route::post('guardar_pendientes','MedicoController@guardarPendientes')->name('guardarpendientes');
	
	Route::post('cargar_pendientes','MedicoController@cargarPendientes')->name('cargarpendientes');
	
	Route::post('cargar_resumen_consulta','MedicoController@cargarResumenConsulta')->name('cargarresumenconsulta');

	Route::post('get_desarrollo_madurativo_tabla','MedicoController@getDesarrolloMadurativoTabla')->name('getdesarrollomadurativotabla');
	
	Route::post('guardar_desarrollo_madurativo_check','MedicoController@guardarDesarrolloMadurativoCheck')->name('guardardesarrollomadurativocheck');
	Route::post('cargar_desarrollo_madurativo','MedicoController@cargarDesarrolloMadurativo')->name('cargardesarrollomadurativo');
	
	Route::post('get_desarrollo_madurativo_edad_consulta','MedicoController@getDesarrolloMadurativoEdadConsulta')->name('getdesarrollomadurativoedadconsulta');
	
	Route::post('guardar_desarrollo_madurativo_observacion','MedicoController@guardarDesarrolloMadurativoObservacion')->name('guardardesarrollomadurativoobservacion');
	
	Route::get('historia_clinica_resumen_os','MedicoController@historiaClinicaResumenOs')->name('historiaclinicaresumenos');
	
	Route::get('cargar_fotos','MedicoController@cargarFotos')->name('cargarfotos');

	Route::post('paciente_consultar_tobb','MedicoController@pacienteConsultarTobb')->name('pacienteconsultartobb');		
		
	Route::post('cargar_desarrollo_madurativo_observacion','MedicoController@cargarDesarrolloMadurativoObservacion')->name('cargardesarrollomadurativoobservacion');	
		
	Route::post('get_paciente_seleccionado','MedicoController@getPacienteSeleccionado')->name('getpacienteseleccionado');	
	
	Route::post('nuevo_screening','MedicoController@nuevoScreening')->name('nuevoscreening');	
	
	Route::post('anterior_siguiente_screening','MedicoController@anteriorSiguienteScreening')->name('anteriorsiguientescreening');	
	
	Route::post('filtrar_screening','MedicoController@filtrarScreening')->name('filtrarscreening');	

	Route::post('ver_licencia_expira','MedicoController@verLicenciaExpira')->name('verlicenciaexpira');			
	
	Route::post('agregar_vacuna_antigripal_paciente','MedicoController@agregarVacunaAntigripalPaciente')->name('agregarvacunaantigripalpaciente');		
	
	Route::post('mostrar_vacuna_antigripal_paciente','MedicoController@mostrarVacunaAntigripalPaciente')->name('mostrarvacunaantigripalpaciente');

	Route::post('guardar_familigrama','MedicoController@guardarFamiligrama')->name('guardarfamiligrama');
	
	Route::post('cargar_foto_familigrama','MedicoController@cargarFotoFamiligrama')->name('cargarfotofamiligrama');

	Route::post('guardar_lactancia_embarazo_previo','MedicoController@guardarLactanciaEmbarazoPrevio')->name('guardarlactanciaembarazoprevio');

	Route::post('guardar_familia','MedicoController@guardarFamilia')->name('guardarfamilia');
	
	Route::post('guardar_embarazo_actual','MedicoController@guardarEmbarazoActual')->name('guardarembarazoactual');
	
	Route::post('guardar_antecedentes_obstetricos','MedicoController@guardarAntecedentesObstetricos')->name('guardarantecedentesobstetricos');

	Route::post('cargar_familia','MedicoController@cargarFamilia')->name('cargarfamilia');
	
	Route::post('cargar_embarazo_actual','MedicoController@cargarEmbarazoActual')->name('cargarembarazoactual');
	
	Route::post('cargar_antecedentes_obstetricos','MedicoController@cargarAntecedentesObstetricos')->name('cargarantecedentesobstetricos');
	
	Route::post('cargar_lactancia_embarazo_previo','MedicoController@cargarLactanciaEmbarazoPrevio')->name('cargarlactanciaembarazoprevio');
	
	Route::post('guardar_detalle_consulta_lactancia','MedicoController@guardarDetalleConsultaLactancia')->name('guardardetalleconsultalactancia');
	
	Route::post('cargar_detalle_consulta_lactancia','MedicoController@cargarDetalleConsultaLactancia')->name('cargardetalleconsultalactancia');
 });

// Aca van a ir todas las rutas que tiene acceso una secretaria.
Route::group(['middleware' => ['usuarioSecretaria']], function () {

	Route::get('/secretaria_home', function () {
	    return view('plantillas.plantilla_secretaria');
	})->name('secretaria_home');

	Route::get('nuevo_paciente','SecretariaController@nuevoPaciente')->name('nuevopaciente');	

	Route::get('actualizar_paciente','SecretariaController@actualizarPaciente')->name('actualizarpaciente');	

	Route::get('buscar_paciente','SecretariaController@buscarPaciente')->name('buscarpaciente');	

	Route::post('continuar_navegacion','SecretariaController@continuarNavegacion')->name('continuarnavegacion');

	Route::post('secretaria_actualizar_paciente_buscar','SecretariaController@actualizarPacienteBuscar')->name('secretariaactualizarpacientebuscar');

 });

// Aca van a ir todas las rutas que son publicas

Route::post('alta_paciente_medico_secretaria','PacienteController@altaPacienteMedicoSecretaria')->name('altapacientemedicosecretaria');

Route::post('consultar_paciente','PacienteController@consultarPaciente')->name('consultarpaciente');		

Route::post('actualizar_datos_paciente','PacienteController@actualizarDatosPaciente')->name('actualizardatospaciente');

Route::get('paciente_listado_buscar','PacienteController@pacienteListadoBuscar');						

Route::post('paciente_consultar','PacienteController@pacienteConsultar');			

Route::post('get_paciente_id','PacienteController@getPacienteId');			

Route::get('manipular_imagenes','PacienteController@manipularImagenes');

