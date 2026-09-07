
@extends('plantillas/plantilla_medico')

<!--@if($consulta != null && $consulta->activo == 2)
  @section('title_header','Nueva Consulta')
@else
  @section('title_header','Consulta')
@endif
-->

@section('contenedor')

@include('modal.snackbar')
@include('utils.manipular_imagenes')


@if(Auth::user()->perfil == 2)
<label class="label_text_size_1rem">Fecha Consulta</label>
<div class="row">
       <div class="input_width_60px">
        <input type="text" maxlength="2" class="form-control" id="fecha_consulta_dia" name="fecha_consulta_dia"  placeholder="dd" required />
      </div>
       <label for="text" class="col-sm-0 control-label margin_left_5px">/</label>
      <div class="input_width_60px margin_left_5px">
        <input type="text" maxlength="2" class="form-control" id="fecha_consulta_mes" name="fecha_consulta_mes"  placeholder="mm" required />
      </div>
      <label for="text" class="col-sm-0 control-label margin_left_5px">/</label>
      <div class="input_width_80px margin_left_5px">   
        <input onchange="calcularEdadFechaConsulta()" type="text" maxlength="4" class="form-control" id="fecha_consulta_anio" name="fecha_consulta_anio"  placeholder="YYYY" required />
      </div>
   </div>
@endif

@if($consulta!=null)
  <input hidden class="form-control" type="text" name="consulta_id" id="consulta_id" value="{{$consulta->id}}" />   
@else
  <input hidden class="form-control" type="text" name="consulta_id" id="consulta_id" value="-1" />   
@endif

@if($paciente!=null)
  <input hidden class="form-control" type="text" name="paciente_id" id="paciente_id" value="{{$paciente->id}}" />   
@else
  <input hidden class="form-control" type="text" name="paciente_id" id="paciente_id" value="-1" />   
@endif
<input hidden class="form-control" type="text" name="es_nueva_consulta" id="es_nueva_consulta" value="{{$nueva_consulta}}" />   

<input hidden class="form-control" type="text" name="consulta_nuevo_pendiente" id="consulta_nuevo_pendiente" value="2" />   

<input hidden class="form-control" type="text" name="modulo_vacunas" id="modulo_vacunas" value="{{$moduloVacunas}}" />   

<input hidden class="form-control" type="text" name="tipo_consulta" id="tipo_consulta" value="{{$consulta->tipo_consulta}}" />   
<!--
<div class="row">
  <label class="label_text_size_1rem">DNI del Paciente:</label>
  @if($paciente != null )
    <input class="form-control input_width_150px" type="text" name="dni_paciente" id="dni_paciente" value="{{$paciente->dni}}" />   
  @else
	  <input class="form-control input_width_150px" type="text" name="dni_paciente" value="" /> 
  @endif
	<button onclick=""><img class="card-img-top img_icono_button" src="/img/iconos/buscar.png"/></button>      			    			 	  
</div> -->
<br>
<ul class="nav nav-tabs letrasblancas">
  <li class="nav-item">
    <a id="datos_a" onclick="getDatos()" type="button" class="nav-link nav_seleccionado">Datos</a>
  </li>
  <li class="nav-item">
    <a id="antecedentes_perinatales_a" onclick="getAntecedentesPerinatales()" type="button" class="nav-link nav_no_seleccionado">Antec. Perinatales</a>
  </li>
  <li class="nav-item">
    <a id="antecedentes_neonatales_patologicos_a" onclick="getAntecedentesNeonatales()" type="button" class="nav-link nav_no_seleccionado">Antec. Neonatales</a>
  </li>
  <li class="nav-item">
    <a id="antecedentes_personales_a" onclick="getAntecedentesPersonales()" type="button" class="nav-link nav_no_seleccionado">Antec. Personales</a>
  </li>
  <li class="nav-item">
    <a id="antecedentes_familiares_a" onclick="getAntecedentesFamiliares()" type="button" class="nav-link nav_no_seleccionado">Antec. Familiares</a>
  </li>
</ul>
<div id="seccion_datos_paciente">
  @include('medico.seccion.info_paciente')
</div>

<div id="seccion_antecedentes_perinatales" hidden>
  @include('medico.seccion.antecedentes_perinatales')
</div>

<div id="seccion_antecedentes_neonatales" hidden>
  @include('medico.seccion.antecedentes_neonatales_patologicos')
</div>

<div id="seccion_antecedentes_personales" hidden>
  @include('medico.seccion.antecedentes_personales')
</div>

<div id="seccion_antecedentes_familiares" hidden>
  @include('medico.seccion.antecedentes_familiares')
</div>
<br>
<div class="row contenedor3">
    <button id="guardarDatosButtonId" onclick="guardarDatos()" class="rodri_button contenido3">GUARDAR</button>
</div>
<br>
@if($moduloVacunas == 1)
  @include('medico.seccion.vacunas')
@else
  @include('medico.seccion.vacunas_dos')
@endif
<br>
@include('medico.seccion.alimentacion')
<br>
@include('medico.seccion.diuresis_catarsis')
<br>
@include('medico.seccion.somnia')
<br>
@include('medico.seccion.escolaridad')
<br>
@include('medico.seccion.actividades_extra_escolares')
<br>
@include('medico.seccion.pantallas')
<br>
@include('medico.seccion.habitos')
<br>

@if($paciente != null && $paciente->sexo == "F")
  @include('medico.seccion.menarca')
  <br>
@endif

@include('medico.seccion.desarrollo_madurativo')
<br>
@include('medico.seccion.examen_fisico')
<br>
@include('medico.seccion.examenes_complementarios')
<br>
@include('medico.seccion.interconsulta')
<br>
@include('medico.seccion.conductas')
<br>
@include('medico.seccion.observaciones')
<br>
@include('medico.seccion.nota')
<br>

<div class="row contenedor3">
  <div class="contenido3">
    <button id="guardarDatosDosButtonId" onclick="guardarDatosDos(1)" class="rodri_button">GUARDAR</button>    
    <!--<button id="guardarPendientesButtonId" type="button" onclick="guardarPendientesModal(1)" class="rodri_button margin_left_60px">PENDIENTES</button>-->
  </div>
</div>
<br>

@include('modal.modal_pendientes')
@include('modal.modal_resumen')
<script type="text/javascript">
  
  function getDatos(){
    ocultarPaneles();    
    document.getElementById("seccion_datos_paciente").hidden = false;  
    var a = document.getElementById("datos_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');
    document.getElementById("guardarDatosButtonId").hidden = false;
  }

  function getAntecedentesPerinatales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_perinatales").hidden = false;
    var a = document.getElementById("antecedentes_perinatales_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');
    document.getElementById("guardarDatosButtonId").hidden = false;
  }

  function getAntecedentesNeonatales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_neonatales").hidden = false;
    var a = document.getElementById("antecedentes_neonatales_patologicos_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');        
    document.getElementById("guardarDatosButtonId").hidden = false;
  }

  function getAntecedentesPersonales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_personales").hidden = false;
    var a = document.getElementById("antecedentes_personales_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');     
    document.getElementById("guardarDatosButtonId").hidden = false;
  }

  function getAntecedentesFamiliares(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_familiares").hidden = false;
    var a = document.getElementById("antecedentes_familiares_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');    
    document.getElementById("guardarDatosButtonId").hidden = false;
  }

  function ocultarPaneles(){
    document.getElementById("seccion_datos_paciente").hidden = true;
    var datos_a = document.getElementById("datos_a");
    datos_a.setAttribute('class', 'nav-link nav_no_seleccionado');

    document.getElementById("seccion_antecedentes_perinatales").hidden = true;
    var antecedentes_perinatales_a = document.getElementById("antecedentes_perinatales_a");
    antecedentes_perinatales_a.setAttribute('class', 'nav-link nav_no_seleccionado');        

    document.getElementById("seccion_antecedentes_neonatales").hidden = true;
    var antecedentes_neonatales_patologicos_a = document.getElementById("antecedentes_neonatales_patologicos_a");
    antecedentes_neonatales_patologicos_a.setAttribute('class', 'nav-link nav_no_seleccionado');    

    document.getElementById("seccion_antecedentes_personales").hidden = true;
    var antecedentes_personales_a = document.getElementById("antecedentes_personales_a");
    antecedentes_personales_a.setAttribute('class', 'nav-link nav_no_seleccionado');    

    document.getElementById("seccion_antecedentes_familiares").hidden = true;
    var antecedentes_familiares_a = document.getElementById("antecedentes_familiares_a");
    antecedentes_familiares_a.setAttribute('class', 'nav-link nav_no_seleccionado');    

    document.getElementById("guardarDatosButtonId").hidden = true;
  }

  function guardarDatos(){
    guardarAntecedentesPerinatales();
   // guardarAntecedentesPersonales();
    guardarAntecedentesFamiliares();   
    guardarNotaAntecedentesNeonantales(); 
    ocultarPaneles();
    mostrarSnackbar("DATOS GUARDADOS");       
  }

  // opcion va a ser 1 cuando sea para guardar la consulta terminada.
  // opcion va a ser 2 cuando sea que le di guardar desde una foto. antes, no va mas esto
  function guardarDatosDos(opcion){
    var cargoPendientes = document.getElementById("consulta_nuevo_pendiente").value;
    
    if(opcion == 1){
      if(cargoPendientes == 2){
         guardarPendientesModal(2);
       } else {
        if(cargoPendientes == 0) {
          guardarDatosVerificarPendiente();
        } else {
          establecerActivo(); 
        }      
      }    
    }
  }

  function guardarDatosDosFotos(){
    guardarEscolaridad();
    guardarActividadesExtraEscolares();
    guardarPantallas();
    guardarHabitos();
    var moduloVacunas = document.getElementById("modulo_vacunas").value;
    if(moduloVacunas == 2)
      guardarVacunasDos();
    var sexo = document.getElementById("sexo_paciente").value;
    if(sexo.localeCompare("F") == 0)
      guardarMenarca();
    guardarConductas();
    guardarObservaciones();
    guardarAlimentacion();
    guardarDiuresisCatarsis();
    guardarSomnia();
    guardarExamenFisico();
    desarrolloMadurativoGuardarObservacion();
    guardarNotas();  
  }

  function establecerActivo(){    
    var consulta_id = document.getElementById("consulta_id").value;
    var paciente_id = document.getElementById("paciente_id").value;  
    var edad_mostrar = document.getElementById("edad").value;        
    var fechaConsulta = null;    
    var fechaConsultaDia = document.getElementById("fecha_consulta_dia");
    if(fechaConsultaDia!=null && fechaConsultaDia.value.localeCompare("") != 0){
      var fechaConsulta = document.getElementById("fecha_consulta_anio").value +"-"+document.getElementById("fecha_consulta_mes").value+"-"+document.getElementById("fecha_consulta_dia").value;
    }
    mostrarSnackbar("GUARDADANDO...");       
    guardarDatosDosFotos();
    
    $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/establecer_activo',
             data:{paciente_id:paciente_id, consulta_id:consulta_id, fecha_consulta:fechaConsulta, edad:edad_mostrar,_token: '{{csrf_token()}}'},
             success:function(data) { 
                mostrarSnackbar("CONSULTA GUARDADA");   
                deshabilitarBotones();   
                window.location.href = "/medico_home"; 
              }
          });     
  }

  function mostrarConsulta(){
    var esNuevaConsulta =  document.getElementById("es_nueva_consulta").value;
    if(document.getElementById("modulo_vacunas").value == 1){
     // cargarVacunasDos();
    } else {
      cargarVacunasDosNuevaConsulta();
    }
    cargarNota();
    cargarEscolaridad();
    cargarActividadesExtraEscolares();
    cargarPantallas();
    cargarHabitos();
    cargarConductas();
    cargarObservaciones();
    cargarExamenFisico();
    cargarAlimentacion();
    cargarCatarsisDiuresis();
    cargarSomnia();
    var sexo = document.getElementById("sexo_paciente").value;
    if(sexo.localeCompare("F") == 0)
      cargarMenarca();    
    cargarInterconsulta();
    cargarExamenesComplementarios();
    clickDesarrolloMadurativo();
    if(esNuevaConsulta == 0)
      mostrarSnackbar("CONSULTA CARGADA");     
  }

  // esto es para cuando cargamos una consulta.
  function deshabilitarBotones(){
    
      var guardarDatosDosButtonId = document.getElementById("guardarDatosDosButtonId");
      guardarDatosDosButtonId.disabled = true;
      guardarDatosDosButtonId.setAttribute('class', 'rodri_button_disabled');

      var guardarPendientesButtonId = document.getElementById("guardarPendientesButtonId");
      if(guardarPendientesButtonId!=null){
        guardarPendientesButtonId.disabled = true;
        guardarPendientesButtonId.setAttribute('class', 'rodri_button_disabled');
      }

      var guardarDatosButtonId = document.getElementById("guardarDatosButtonId");
      guardarDatosButtonId.disabled = true;
      guardarDatosButtonId.setAttribute('class', 'rodri_button_disabled contenido3');
      
      if(document.getElementById("modulo_vacunas").value == 1){
        var guardarOtrasVacunasButtonId = document.getElementById("guardarOtrasVacunasButtonId");
        if(guardarOtrasVacunasButtonId!= null){
          guardarOtrasVacunasButtonId.disabled = true;
          guardarOtrasVacunasButtonId.setAttribute('class', 'rodri_button_disabled contenido3');
        }
      }

      var agregarBotonNuevaFotoExCompId = document.getElementById("agregarBotonNuevaFotoExCompId");
      agregarBotonNuevaFotoExCompId.disabled = true;
      agregarBotonNuevaFotoExCompId.setAttribute('class', 'rodri_button_volver');
      
      var botonGuardarFotoExCompId = document.getElementById("botonGuardarFotoExCompId");
      botonGuardarFotoExCompId.disabled = true;
      botonGuardarFotoExCompId.setAttribute('class', 'rodri_button_volver margin_left_20px');
      
      var nuevoExamenComplementarioButtonId = document.getElementById("nuevoExamenComplementarioButtonId");
      nuevoExamenComplementarioButtonId.disabled = true;
      nuevoExamenComplementarioButtonId.setAttribute('class', 'rodri_button_disabled margin_left_20px');
      
      var guardarExamenComplementarioButtonId = document.getElementById("guardarExamenComplementarioButtonId");
      guardarExamenComplementarioButtonId.disabled = true;
      guardarExamenComplementarioButtonId.setAttribute('class', 'rodri_button_disabled margin_left_20px');
      
      var nuevaInterconsultaButtonId = document.getElementById("nuevaInterconsultaButtonId");
      nuevaInterconsultaButtonId.disabled = true;
      nuevaInterconsultaButtonId.setAttribute('class', 'rodri_button_disabled margin_left_20px');
      
      var guardarDatosInterconsultaButtonId = document.getElementById("guardarDatosInterconsultaButtonId");
      guardarDatosInterconsultaButtonId.disabled = true;
      guardarDatosInterconsultaButtonId.setAttribute('class', 'rodri_button_disabled margin_left_20px');
      
      var agregarBotonNuevaFotoAntNeoId = document.getElementById("agregarBotonNuevaFotoAntNeoId");
      agregarBotonNuevaFotoAntNeoId.disabled = true;
      agregarBotonNuevaFotoAntNeoId.setAttribute('class', 'rodri_button_volver');
      
      var guardarAntNeonatalesButtonId = document.getElementById("guardarAntNeonatalesButtonId");
      guardarAntNeonatalesButtonId.disabled = true;
      guardarAntNeonatalesButtonId.setAttribute('class', 'rodri_button_volver');
    
  }

  function calcularEdadFechaConsulta() {
/*
    //calcularEdadFechas(document.getElementById("fecha_nacimiento").value);
    var edad_aux_array = calcularEdad2(document.getElementById("fecha_nacimiento").value);
    //alert(edad_aux_array);
    var edad_aux = edad_aux_array.split("-");
    var edad = '';
    if(edad_aux[0] != 0){
      edad = edad + edad_aux[0]+" años,   ";
    }
    if(edad_aux[1] != 0){
      edad = edad + edad_aux[1]+" meses y ";
    }
    edad = edad + edad_aux[2]+" dias "; 
    document.getElementById("edad").value = edad;*/
  }

  function verPendientes() {
    checkPendientes();
  }

  function verResumen(){
    var consulta_id = document.getElementById("consulta_id").value;
    var paciente_id = document.getElementById("paciente_id").value;
    cargarResumen(consulta_id, paciente_id);
  }

  function inicializarPanelSuperior(){    
    var esNuevaConsulta =  document.getElementById("es_nueva_consulta").value;
    cargarAntecedentesFamiliares();
    //if(esNuevaConsulta == 0 || esNuevaConsulta == 3){            
      cargarAntecedentesPersonales();
    //}
    cargarAntecedentesNeonatales();
    cargarAntecedentesPerinatales();
  }

  function cargarTitleHeader(){
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    document.getElementById("static_header").hidden = true;
    document.getElementById("static_header_2").innerHTML = "Nueva Consulta";
    document.getElementById("static_header_nombre").innerHTML = apellido+", "+nombre;    
  }

  window.onload=function() {
    mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
    cargarTitleHeader();
    calcularEdad();          
    var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;    
    inicializarPanelSuperior();
    //alert(esNuevaConsulta)
    if(esNuevaConsulta == 1) {
      //initAntecedentesPersonales();
      getExamanesComplementariosId();
      inicializarAntecedentesNeonatales();
      cargarInterconsulta();   
      cargarExamenesComplementarios();   
      checkPendientes();
    } else {    
        getDesarrolloMadurativoEdadConsulta();  
        var consulta = document.getElementById("consulta_id");           
        if(esNuevaConsulta == 0)
          deshabilitarBotones();
        if(consulta != null && consulta.value != null){
          var consulta_id = consulta.value;          
          $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/check_consulta_existe',
                 data:{consulta_id:consulta_id, _token: '{{csrf_token()}}'},
                 success:function(data) {    
                    if(data.response == 1){                                              
                      mostrarConsulta();
                      inicializarAntecedentesNeonatales();
                    } else {                       
                      if(data.response == 0) {                        
                        getExamanesComplementariosId();
                        inicializarAntecedentesNeonatales();              
                          
                    } else {                          
                      mostrarSnackbar("ERROR");    
                    }                    
                 }
                 if(document.getElementById("es_nueva_consulta").value == 3){                  
                    mostrarConsulta();                                        
                 }
               }
              });     
          }        
    }
  }

</script>

@endsection