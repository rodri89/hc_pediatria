
@extends('plantillas/plantilla_medico')

<!--@section('title_header','Consultas Paciente')-->

@section('contenedor')

@if($response == 0)
  <h1>No hay consultas previas.</h1>
@else
<input hidden id="paciente_nombre" value="{{$paciente->nombre}}">
<input hidden id="paciente_apellido" value="{{$paciente->apellido}}">
<input hidden id="consulta_paciente_id" value="{{$paciente->id}}">
<input hidden id="paciente_id" value="{{$paciente->id}}">
<input hidden id="es_nueva_consulta" value="{{$nueva_consulta}}">
<input hidden id="modulo_vacunas" value="{{$moduloVacunas}}">

<input hidden id="consulta_id" value="{{$consulta->id}}">

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

<div class="row">
  <div class="table-responsive">
    <table class="table table-condensed tabla_con_borde" id="tabla_pacientes" name="tabla_pacientes">
     <thead>
        <tr>
          <th class="editText rodri_th letra_size_1rem input_width_30px" scope="col">N°Consulta</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Tipo</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Fecha</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Ver</th>           
        </tr>
      </thead>
      <tbody id="pacientes-list" name="pacientes-list">
        <?php $cont = $listadoConsultas->count(); ?>
        @foreach($listadoConsultas as $lc)
        <tr>
          <th scope="row" class="editText letra_size_1rem input_width_30px">{{$cont--}}</th>
          @if($lc->tipo_consulta == 1)          
            <td><img class="small" src="/img/iconos/control_salud.png"></td>
          @endif

          @if($lc->tipo_consulta == 2)          
            <td><img class="small" src="/img/iconos/enfermedad.png"></td>
          @endif

          @if($lc->tipo_consulta == 3)          
            <td><img class="small" src="/img/iconos/consulta_foto.png"></td>
          @endif

          @if($lc->tipo_consulta == 4)          
            <td><img class="small" src="/img/iconos/telemedicina.png"></td>
          @endif

          @if($lc->tipo_consulta == 5)          
            <td><img class="small" src="/img/iconos/consulta_prenatal.png"></td>
          @endif

          @if($lc->tipo_consulta == 6)          
            <td><img class="small" src="/img/iconos/lactancia.png"></td>
          @endif

          <!-- 2020-07-24 13:24:21  -->
          <?php $fecha_mostrar_aux = explode(' ', $lc->created_at);
                $fecha_mostrar = explode('-', $fecha_mostrar_aux[0]);
                $f_mostrar = $fecha_mostrar[2].'/'.$fecha_mostrar[1].'/'.$fecha_mostrar[0];?>
          <td class="letra_size_1rem">{{$f_mostrar}}</td>          
          
          <td class='letra_size_1rem'><button class='rodri_button_aceptar_si' onclick='verResumen("{{$lc->id}}", "{{$lc->edad_paciente}}", "{{$lc->tipo_consulta}}","{{$lc->edad_mostrar}}", "{{$f_mostrar}}")' data-toggle='modal' data-target='.bd-example-modal-xl'>></button></td>
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>

</div>

@endif

<div id="modal_resumen_consulta_paciente" class="modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body"> 
        <div class="modal-header">       
          <h4 class="modal-title"         
            id="modalTitleMensaje">Resumen Consulta</h4><p id="edad_mostrar" class="margin_right_5px cel_size_input"></p>
        </div> 
        <div id="seccion_resumen margin_left_20px">
          <div id="seccion_motivo_consulta_consultar_paciente">
          @include('medico.seccion.motivo_consulta')
          </div>
          @if($moduloVacunas == 1)
            @include('medico.seccion.vacunas')
          @else
            @include('medico.seccion.vacunas_dos')
          @endif
          @include('medico.seccion.alimentacion')
          @include('medico.seccion.diuresis_catarsis')
          @include('medico.seccion.somnia')
          @include('medico.seccion.escolaridad')
          @include('medico.seccion.actividades_extra_escolares')          
          @include('medico.seccion.pantallas')
          @include('medico.seccion.habitos')
          @include('medico.seccion.menarca')
          @include('medico.seccion.desarrollo_madurativo')
          @include('medico.seccion.examen_fisico')
          @include('medico.seccion.datos_subjetivos')
          @include('medico.seccion.datos_objetivos')

          @include('medico.consulta_prenatal.info_familia')                    
          @include('medico.consulta_prenatal.embarazo_actual')                    
          @include('medico.consulta_prenatal.antecedentes_obstetricos')                    
          @include('medico.consulta_prenatal.lactancia_embarazo_previo') 

          <div id="seccion_examenes_complementarios_interconsultas">
            @include('medico.seccion.examenes_complementarios')
            @include('medico.seccion.interconsulta')          
          </div>
          @include('medico.seccion.conductas')
          @include('medico.seccion.observaciones')
          @include('medico.seccion.nota') 

          @include('medico.lactancia.detalle_consulta') 

                             
          
          <div hidden id="seccion_foto_consulta" class="col-md-6">    
            <p class="rodri_bold font_size_resumen">Consulta Digitalizada</p><br>
            <div id="seccion_nueva_consulta_fotos">
                <input type="hidden" id="foto_ant_neonantales_id2" name="foto_ant_neonantales_id2" />         
                <input type="hidden" id="nueva_consulta_fotos_cantidad_fotos" name="nueva_consulta_fotos_cantidad_fotos" value="1" />                
            </div>                    
            <input hidden id="nueva_consulta_foto_numero_foto" name="nueva_consulta_foto_numero_foto" />
              <div id="seccion_examen_complementario_ver_fotos" class="margin_top_12px">
                <a type="button" id="antecedentes_neonatales_foto_1" class="card-img-top img_little botonImage" alt="">
                  <img id="nueva_consulta_foto" src="img/iconos/sin_imagen.jpg" class="card-img-top img_little botonImage">
                </a>
                <div class="row margin_top_12px margin_left_60px">          
                  <button type="button" onclick="nuevaConsultaFotoAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
                  <input id="nueva_consulta_foto_actual_cantidad_fotos" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
                  <button type="button" onclick="nuevaConsultaFotoAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>
                </div>    
              </div>
              <br>
          </div>

          <div id="seccion_pendiente_consulta" hidden>     
          </div>
          @include('modal.modal_pendientes')           
        </div>
        <div class="modal-footer">
            <button type="button" 
             class="rodri_button_aceptar"             
             data-dismiss="modal">Aceptar</button>             
        </div>
      </div>
    </div>
  </div>
</div>



@include('utils.manipular_imagenes')
<script type="text/javascript">

    function verResumen(consulta_id, edad_paciente, tipo_consulta, edad_mostrar, fecha_consulta){
      $('#modal_screening').modal('hide');  
      var paciente_id = document.getElementById("consulta_paciente_id").value;    
      document.getElementById("consulta_id").value = consulta_id;      
      document.getElementById("modalTitleMensaje").innerHTML = "Resumen Consulta "+fecha_consulta;
      document.getElementById("dm_cantidad_meses_cargar").value = edad_paciente;      
      document.getElementById("desarrollo_madurativo_nueva_consulta").hidden = true;            
      
      if(tipo_consulta == 3){
        document.getElementById("seccion_foto_consulta").hidden = false;        
        cargarFotosNuevaConsulta();
        //document.getElementById('modal_resumen_consulta_paciente').hidden = true;
      } else {
        if(edad_mostrar.localeCompare('')!=0 && tipo_consulta != 5){
          document.getElementById("edad_mostrar").innerHTML = 'Edad: '+edad_mostrar;
        } else {
          document.getElementById("edad_mostrar").innerHTML = 'N/A';
        }
        document.getElementById("seccion_foto_consulta").hidden = true;
        //document.getElementById('modal_resumen_consulta_paciente').hidden = false;
      }
      var moduloVacunas = document.getElementById("modulo_vacunas").value;
      if(moduloVacunas == 2){
        cargarVacunasDos();
      } else {
        cargarVacunaConsulta(tipo_consulta);
      }
      if(tipo_consulta == 2){
        document.getElementById("seccion_motivo_consulta_consultar_paciente").hidden = false;        
        cargarMotivoConsulta();
      } else {
        document.getElementById("seccion_motivo_consulta_consultar_paciente").hidden = true;        
      }
      cargarAlimentacionConsulta();
      cargarDiuresisCatarsisConsulta();
      cargarSomniaConsulta();
      cargarEscolaridadConsulta();
      cargarActividadesExtraEscolaresConsulta();
      cargarPantallasConsulta();
      cargarHabitosConsulta();
      cargarMenarcaConsulta();
      if(tipo_consulta == 1){
        cargarDesarolloMadurativoConsulta();
      }       
      
      cargarDatosSubjetivosConsulta();
      cargarDatosObjetivosConsulta();
 
      cargarExamenFisicoConsulta(tipo_consulta);
      cargarExamenesComplementariosConsulta();
      cargarInterconsultaConsulta();
      cargarConductasConsulta();
      cargarObservacionesConsulta();      
      cargarNotaConsulta();      
      cargarPendientes();

      if(tipo_consulta == 5){                
        document.getElementById("seccion_examenes_complementarios_interconsultas").hidden = true;
      } else {
        document.getElementById("seccion_examenes_complementarios_interconsultas").hidden = false;        
      }
      document.getElementById("seccion_lactancia_embarazo_previo_nueva_consulta").hidden = true;
      cargarFamiliaConsulta();  
      cargarEmbarazoActualConsulta();
      cargarAntecedentesObstetricosConsulta();      

      cargarDetalleConsultaConsulta();

      mostrarModal();
    }     

    function mostrarModal(){
      $('#modal_resumen_consulta_paciente').modal('show');          
    }   

  function bajarRenglon(seccion){
      var ptitle_aux = document.createElement("P");
      ptitle_aux.setAttribute('class', 'rodri_bold');                 
      ptitle_aux.innerHTML = "";
      seccion.appendChild(ptitle_aux);
  }

    // text1: text2  + bajada de renlgon
  function addText(text1, text2, seccion_resumen){                    
      var ptitle_a1 = document.createElement("P");
      ptitle_a1.setAttribute('class', 'rodri_bold rodri_inline_margin');                 
      ptitle_a1.innerHTML = text1+": "; 
      seccion_resumen.appendChild(ptitle_a1);                                  
      var p_a1 = document.createElement("P");
      p_a1.innerHTML = text2;
      p_a1.setAttribute('class', 'rodri_resumen_p rodri_inline');                                   
      seccion_resumen.appendChild(p_a1);
  }

  // text1: 
  // text2  
  function addText2(text1, text2, seccion_resumen){                    
      var ptitle_a1 = document.createElement("P");
      var br = document.createElement("BR");
      ptitle_a1.setAttribute('class', 'rodri_bold margin_left_10px');                 
      ptitle_a1.innerHTML = text1+": "; 
      seccion_resumen.appendChild(ptitle_a1);                                                                          
      var p_a1 = document.createElement("P");
      p_a1.innerHTML = text2;
      p_a1.setAttribute('class', 'rodri_resumen_p margin_left_10px');                                   
      seccion_resumen.appendChild(p_a1);      
  }

  function addTituloDescripcion(text1, text2, seccion_resumen){                    
    var title = document.createElement("P");
    var br = document.createElement("BR");
    var p = document.createElement("P");
    title.setAttribute('class', 'rodri_bold font_size_resumen');
    title.innerHTML = text1;
    p.innerHTML = text2;
    p.setAttribute('class', 'rodri_resumen_p margin_left_10px'); 
    seccion_resumen.appendChild(title);
    seccion_resumen.appendChild(p);       
    seccion_resumen.appendChild(br);       
  }

  function addDescripcion(text2, seccion_resumen){
    var p = document.createElement("P");
    p.innerHTML = text2;
    p.setAttribute('class', 'rodri_resumen_p margin_left_10px margin_top_5px');     
    seccion_resumen.appendChild(p);       
  }

  function borrarSeccion(seccion){
    var myNode = document.getElementById(seccion);
            while (myNode.firstChild) {
                   myNode.removeChild(myNode.firstChild);
            }
  }


  function getDatos(){
    ocultarPaneles();    
    document.getElementById("seccion_datos_paciente").hidden = false;  
    var a = document.getElementById("datos_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');
  }

  function getAntecedentesPerinatales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_perinatales").hidden = false;
    var a = document.getElementById("antecedentes_perinatales_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');
  }

  function getAntecedentesNeonatales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_neonatales").hidden = false;
    var a = document.getElementById("antecedentes_neonatales_patologicos_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');        
  }

  function getAntecedentesPersonales(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_personales").hidden = false;
    var a = document.getElementById("antecedentes_personales_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');     
  }

  function getAntecedentesFamiliares(){
    ocultarPaneles();    
    document.getElementById("seccion_antecedentes_familiares").hidden = false;
    var a = document.getElementById("antecedentes_familiares_a");
    a.setAttribute('class', 'nav-link nav_seleccionado');    
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
  }

  function inicializarPanelSuperior(){    
    var esNuevaConsulta =  document.getElementById("es_nueva_consulta").value;
    cargarAntecedentesFamiliares();
    cargarAntecedentesPersonalesConsulta();    
    cargarAntecedentesNeonatalesConsulta();
    cargarAntecedentesPerinatales();
  }

  function agregarBotonNuevaFotoNCF(){
      var cantidadFotos = document.getElementById("nueva_consulta_fotos_cantidad_fotos").value;  //1      
      var viejoValor = parseInt(cantidadFotos); // 1
      var nuevoValor = parseInt(cantidadFotos) + 1;      
    //  var btn = document.getElementById("foto-"+viejoValor);
      var ultimoFile = document.getElementById("nueva_consulta_foto_"+viejoValor);
      var f = ultimoFile.value;
      if(f.localeCompare('') != 0){
        $('#nueva_consulta_fotos_cantidad_fotos').val(nuevoValor);
        var seccion = document.getElementById("seccion_nueva_consulta_fotos");                  
        var input = document.createElement("INPUT");
        input.type = 'file';
        input.id = 'nueva_consulta_foto_'+nuevoValor;
        input.name = 'nueva_consulta_foto_'+nuevoValor;
        seccion.appendChild(input);
      }
    }

    function cargarFotosNuevaConsulta(){
      var consulta = document.getElementById("consulta_id").value;
      var paciente = document.getElementById("paciente_id").value;
      //var ex_compl_id = document.getElementById("examenes_complementarios_id").value;         
     $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_fotos_nueva_consulta',
           data:{consulta:consulta, paciente:paciente, _token: '{{csrf_token()}}'},
            success:function(data) {                              
              if(data.response == 1) {                                                        
                // medico1/examenes_complementarios/7JddMlunI1SPXoVSZcv3sloguzyRpkgAWsbFk66c.jpeg                 
                if(data.consultaFotos !=null && data.consultaFotos.foto.localeCompare('') != 0) {                                   
                    var img = document.getElementById("nueva_consulta_foto");                   
                    $("#nueva_consulta_foto").attr("src", "img/"+data.consultaFotos.foto);
                    img.onclick = function() {                                                  
                      onClickVerMI(data.consultaFotos.foto, 3);
                    } 

                    document.getElementById("nueva_consulta_foto_numero_foto").value = data.consultaFotos.numero;
                    var valorFoto = data.consultaFotos.numero+"/"+data.consultaFotos.numero;
                    document.getElementById("cantidad_fotos_modal_ncf").value = valorFoto;
                    document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valorFoto;                                             
                  } else {                                                            
                    $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
                    document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = "0/0";
                    document.getElementById("cantidad_fotos_modal_ncf").value = "0/0";
                  }
              }             
            }
        });   
    }

    function nuevaConsultaFotoAnteriorSiguienteFoto(opcion){
    var paciente = document.getElementById("paciente_id").value;    
    var consulta = document.getElementById("consulta_id").value;    
    var numero_actual = document.getElementById("nueva_consulta_foto_numero_foto").value;
    if(opcion == 1) // avanzo
      var numero = parseInt(numero_actual) + 1;
    else
      var numero = parseInt(numero_actual) - 1;
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_fotos_nueva_consulta_ant_sig_foto',
           data:{paciente:paciente, consulta:consulta ,numero:numero ,_token: '{{csrf_token()}}'},
            success:function(data){              
              if(data.response == 1){                                 
                if(data.response_data.numero != 0){                   
                  document.getElementById("nueva_consulta_foto_numero_foto").value = data.response_data.numero;
                                                        
            var valor = data.response_data.numero+"/"+data.cantidadFotos;
                  document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valor;           
                }
                if(data.response_data.foto.localeCompare('') != 0) {                                    
                    var img = document.getElementById("nueva_consulta_foto");                   
                    $("#nueva_consulta_foto").attr("src", "img/"+data.response_data.foto);
                    img.addEventListener("dblclick", function(e){
              getFullscreen(this);
            },false);
                    /*img.onclick = function() {                                                  
                      onClickVer(data.response_data.foto);
                    } */                                    
                  } else {                    
                    $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
                  }
              }
            }
        });
  }

  function nuevaConsultaFotoAnteriorSiguienteFotoModal(opcion){
    var paciente = document.getElementById("paciente_id").value;    
    var consulta = document.getElementById("consulta_id").value;    
    var numero_actual = document.getElementById("nueva_consulta_foto_numero_foto").value;
    if(opcion == 1) // avanzo
      var numero = parseInt(numero_actual) + 1;
    else
      var numero = parseInt(numero_actual) - 1;
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_fotos_nueva_consulta_ant_sig_foto',
           data:{paciente:paciente, consulta:consulta ,numero:numero ,_token: '{{csrf_token()}}'},
            success:function(data){              
              if(data.response == 1){                                 
                if(data.response_data.numero != 0){                   
                  document.getElementById("nueva_consulta_foto_numero_foto").value = data.response_data.numero;
                                                        
            var valor = data.response_data.numero+"/"+data.cantidadFotos;
                  document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valor;           
                  document.getElementById("cantidad_fotos_modal_ncf").value = valor;
                }
                if(data.response_data.foto.localeCompare('') != 0) {                                    
                    var img = document.getElementById("nueva_consulta_foto");                   
                    $("#nueva_consulta_foto").attr("src", "img/"+data.response_data.foto);
                    
                    img.onclick = function() {                                                  
                      onClickVerMI(data.response_data.foto, 3);
                    }
                    $("#img_src").attr("src", "img/"+data.response_data.foto);  
                  } else {  
                    $("#img_src").attr("src", "img/iconos/sin_imagen.jpg");                                       
                    $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
                  }
              }
            }
        });
  }

  /*function getFullscreen(element){
    if(element.requestFullscreen) {
        element.requestFullscreen();
      } else if(element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
      } else if(element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
      } else if(element.msRequestFullscreen) {
        element.msRequestFullscreen();
      }
  }*/

  function cargarTitleHeader(){
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    document.getElementById("static_header").innerHTML = "Consultas Paciente";
   // document.getElementById("static_header_nombre").innerHTML = "- "+apellido+","+nombre;    
  }


  window.onload=function() {
    mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
    inicializarPanelSuperior();
    calcularEdad();
    cargarTitleHeader();
  }

</script>

@endsection