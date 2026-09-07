
@extends('plantillas/plantilla_medico')

@section('title_header','Consultas Paciente')

@section('contenedor')

<input hidden id="es_nueva_consulta" value="{{$nueva_consulta}}">
<input hidden id="paciente_id" value="{{$paciente->id}}">
<input hidden id="consulta_id" value="{{$consulta->id}}">

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
          <th scope="row" class="editText letra_size_1rem input_width_30px">{{$cont}}</th>
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
                $fecha_mostrar = explode('-', $fecha_mostrar_aux[0]);?>
          <td class="letra_size_1rem">{{$fecha_mostrar[2].'/'.$fecha_mostrar[1].'/'.$fecha_mostrar[0]}}</td>          
          
          <td class='letra_size_1rem'><button class='rodri_button_aceptar_si' onclick='mostrarPanelCargarFoto("{{$lc->id}}", "{{$lc->tipo_consulta}}", "{{$cont--}}")' data-toggle='modal' data-target='.bd-example-modal-xl'>></button></td>
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>

</div>

<div id="modal_cargar_fotos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body"> 
        <div class="modal-header">       
          <h4 class="modal-title"         
            id="modalTitleMensaje">Cargar Fotos</h4><p id="consulta_numero" class="margin_right_5px cel_size_input"></p>
        </div> 
        <div id="seccion_resumen margin_left_20px">
           <div class="row margin_left_5px">
              <p class="rodri_bold font_size_resumen">Familigrama</p>
            </div>
           <form method="post" action="{{ route('guardarfamiligrama') }}" enctype="multipart/form-data">
            <input type="hidden" id="es_nueva_consulta_cargar_fotos_fg" name="es_nueva_consulta_cargar_fotos_fg" value="4" />
            @csrf  
            <div class="col-md-6">    
              <label id="familigrama_agregar_foto_text">Agregar Foto:</label><br>
              <div id="seccion_familigrama_fotos">
                <input type="hidden" id="paciente_id_fm" name="paciente_id_fm" />                
                <input type="hidden" id="foto_familigrama_id" name="foto_familigrama_id" />
                <input type="hidden" id="familigrama_cantidad_fotos" name="familigrama_cantidad_fotos" />
                <input type="file" id="familigrama_foto_1" name="familigrama_foto_1" /> 
              </div>
              <br>            
              <button id="guardarFamiligramaButtonId" type="submit" class="rodri_button_aceptar">Guardar</button>       
              <input hidden id="familigrama_numero_foto" name="familigrama_numero_foto" />
                <div  id="seccion_familigrama_ver_fotos" class="margin_top_12px">
                  <a type="button" id="familigrama_foto_1" class="card-img-top img_little botonImage" alt="">
                    <img  id="familigrama_foto" src="img/iconos/sin_imagen.jpg" class="card-img-top img_little botonImage">
                  </a>                  
                </div>
            </div>            
           </form>
           <br>
           <hr class="sidebar-divider d-none d-md-block">
           <br>
           @include('medico.seccion.antecedentes_neonatales_patologicos')
           <br>
           <hr class="sidebar-divider d-none d-md-block">
           <br>
           @include('medico.seccion.antecedentes_personales')
           <br> 
           <hr class="sidebar-divider d-none d-md-block">
           <br>
          @include('medico.seccion.examenes_complementarios')                                  
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

    function mostrarPanelCargarFoto(consulta_id, tipo_consulta, consulta_nro){
      document.getElementById("consulta_id").value = consulta_id;  
      document.getElementById("antecedentes_neonatales_consulta_id").value = consulta_id;  
      document.getElementById("internaciones_consulta_id").value = consulta_id;  
      document.getElementById("consulta_numero").innerHTML = 'Consulta: '+consulta_nro;
      vaciarCampos();
      cargarExamenesComplementariosConsultaCargarFoto();
      inicializarAntecedentesNeonatales();
      cargarAntecedentesNeonatales();
      antecedentesNeonatalesReadOnly(true);
      inicializarAntecedentesPersonalesCargarFoto();
      clickFamiligrama();

      $('#internaciones_actual_cantidad').attr('class', 'input_width_50px sinBackground margin_left_20px');
      $('#internaciones_actual_cantidad_fotos').attr('class', 'input_width_50px sinBackground margin_left_20px');
      $('#ant_neonatales_actual_cantidad_fotos').attr('class', 'input_width_50px sinBackground margin_left_20px');
      $('#modal_cargar_fotos').modal('show');  
    }

    function vaciarCampos(){
      document.getElementById("examenes_complementarios_actual_cantidad").value = "0/0";
      document.getElementById("examenes_complementarios_solicito").value = "";                             
      document.getElementById("examenes_complementarios_respuesta").value = "";
      $("#examenes_complementarios_foto").attr("src", "img/iconos/sin_imagen.jpg");                                 
      document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = "0/0";    
    }

    function clickFamiligrama() {   
    var paciente = document.getElementById("paciente_id").value;
    //var ex_compl_id = document.getElementById("examenes_complementarios_id").value;         
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_foto_familigrama',
           data:{paciente:paciente, _token: '{{csrf_token()}}'},
            success:function(data) {                              
              if(data.response == 1) {                                                                        
                if(data.familigrama !=null && data.familigrama.foto.localeCompare('') != 0) {                               
                  var imagen = document.getElementById("familigrama_foto");
                     $("#familigrama_foto").attr("src", "img/"+data.familigrama.foto);
            
                    var img = document.getElementById("familigrama_foto");                    
                    $("#familigrama_foto").attr("src", "img/"+data.familigrama.foto);
                    img.onclick = function() {                                                  
                      onClickVerMI(data.familigrama.foto, 1); 
                      document.getElementById("panelAvanzarMI").hidden = true;
                      document.getElementById("panelCantidadMI").hidden = true;
                      
                      $('#modal_familigrama_cargar_fotos').modal('hide');                     
                    }                         
                  } else {                                                      
                    $("#familigrama_foto").attr("src", "img/iconos/sin_imagen.jpg");                                    
                  }
              }             
            }
        });
        var es_nueva_consulta = document.getElementById("es_nueva_consulta").value;
        if(es_nueva_consulta == 0){
          document.getElementById("familigrama_agregar_foto_text").hidden = true;
          document.getElementById("familigrama_foto_1").hidden = true;
          document.getElementById("guardarFamiligramaButtonId").hidden = true;          
          document.getElementById("modalTitleFamiligrama").innerHTML = "Ver Foto Familigrama";
        }
    var paciente_id = document.getElementById("paciente_id").value;
    $('#paciente_id_fm').val(paciente_id);  
    $('#modal_familigrama_cargar_fotos').modal('show');  
  }

    window.onload=function() {
      mostrarPanelPaciente(true);
      mostrarPanelHistoriaClinica(false);    
    }

</script>

@endsection
