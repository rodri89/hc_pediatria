
@extends('plantillas/plantilla_medico')

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

<input hidden class="form-control" type="text" name="consulta_nuevo_pendiente" id="consulta_nuevo_pendiente" value="0" />   

<input hidden class="form-control" type="text" name="tipo_consulta" id="tipo_consulta" value="{{$consulta->tipo_consulta}}" />   

<br>
<ul class="nav nav-tabs letrasblancas">
  <li class="nav-item">
    <a id="datos_a" onclick="getDatos()" type="button" class="nav-link nav_seleccionado">Datos</a>
  </li>

  <li class="nav-item">
    <a id="antecedentes_familiares_a" onclick="getAntecedentesFamiliares()" type="button" class="nav-link nav_no_seleccionado">Antec. Familiares</a>
  </li>
</ul>
<div id="seccion_datos_paciente">
  @include('medico.seccion.info_paciente')
</div>

<div id="seccion_antecedentes_familiares" hidden>
  @include('medico.seccion.antecedentes_familiares')
</div>
<br>
<div class="row contenedor3">
    <button id="guardarDatosButtonId" onclick="guardarDatos()" class="rodri_button contenido3">GUARDAR</button>
</div>
<br>
@include('medico.consulta_prenatal.info_familia')
<br>
@include('medico.consulta_prenatal.embarazo_actual')
<br>
@include('medico.consulta_prenatal.antecedentes_obstetricos')
<br>
@include('medico.consulta_prenatal.lactancia_embarazo_previo')
<br>

<div class="row contenedor3">
  <div class="contenido3">
    <button id="guardarDatosDosButtonId" onclick="guardarDatosDos()" class="rodri_button">GUARDAR</button>        
  </div>
</div>
<br>

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
    
    document.getElementById("seccion_antecedentes_familiares").hidden = true;
    var antecedentes_familiares_a = document.getElementById("antecedentes_familiares_a");
    antecedentes_familiares_a.setAttribute('class', 'nav-link nav_no_seleccionado');    

    document.getElementById("guardarDatosButtonId").hidden = true;
  }

  function guardarDatos(){
   // guardarAntecedentesPerinatales();
    //guardarAntecedentesPersonales();
    guardarAntecedentesFamiliares();   
  //  guardarNotaAntecedentesNeonantales(); 
    ocultarPaneles();
    mostrarSnackbar("DATOS GUARDADOS");       
  }

  function guardarDatosDos(){
    guardarLactanciaEmbarazoPrevio();
    guardarFamilia();
    guardarEmbarazoActual();
    guardarAntecedentesObstetricos();
    establecerActivo();   
  }

  function guardarDatosDosFotos(){
    /*guardarConductas();    
    guardarDatosSubjetivos();
    guardarDatosObjetivos();    */
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
    $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/establecer_activo',
             data:{paciente_id:paciente_id, consulta_id:consulta_id,fecha_consulta:fechaConsulta,edad:edad_mostrar, _token: '{{csrf_token()}}'},
             success:function(data) { 
                mostrarSnackbar("CONSULTA GUARDADA");   
                deshabilitarBotones();    
                window.location.href = "/medico_home"; 
              }
          });     
  }

  // esto es para cuando cargamos una consulta.
  function deshabilitarBotones(){
    
      var guardarDatosDosButtonId = document.getElementById("guardarDatosDosButtonId");
      guardarDatosDosButtonId.disabled = true;
      guardarDatosDosButtonId.setAttribute('class', 'rodri_button_disabled');
      
      var guardarDatosButtonId = document.getElementById("guardarDatosButtonId");
      guardarDatosButtonId.disabled = true;
      guardarDatosButtonId.setAttribute('class', 'rodri_button_disabled contenido3');                  
            
  }

  function verResumen(){
    var consulta_id = document.getElementById("consulta_id").value;
    var paciente_id = document.getElementById("paciente_id").value;
    cargarResumen(consulta_id, paciente_id);
  }

  function inicializarPanelSuperior(){    
    var esNuevaConsulta =  document.getElementById("es_nueva_consulta").value;
    cargarAntecedentesFamiliares();      
  }

  function cargarTitleHeader(){
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    document.getElementById("static_header").hidden = true;
    document.getElementById("static_header_2").innerHTML = "Nueva Consulta Prenatal";
    document.getElementById("static_header_nombre").innerHTML = apellido+", "+nombre;    
  }

  window.onload=function() {
    mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
    
    calcularEdad();          
    var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
    inicializarPanelSuperior();
    cargarTitleHeader();    
  }

</script>

@endsection