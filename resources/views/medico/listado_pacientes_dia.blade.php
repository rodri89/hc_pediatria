
@extends('plantillas/plantilla_medico')

@if($listadoPacientes != null)
  @section('title_header','Listado Pacientes')
@else
  @section('title_header','Home')
@endif
@section('contenedor')

@if($listadoPacientes != null)

@include('modal.snackbar')
<div id="seccion_buscar_paciente">
  <h4 class="editText">Buscar paciente:</h4>            
  <input class="editText" type="text" id="texto_buscar" name="texto_buscar" value="" placeholder="" onchange="validarPacienteExiste()" />     

</div>
<div hidden id="seccion_tabla_buscar_paciente" class="table-responsive margin_top_20px" style="height:200px; overflow-y: scroll;">
      <table class="table table-condensed" id="tabla_pacientes" name="tabla_pacientes">
       <thead class="fondoNav text-white">
          <tr>
            <th class="editText" scope="col">#</th>
          <th class="editText">Apellido</th>
            <th class="editText">Nombre</th>
            <th class="editText">DNI</th>
            <th class="editText">Telefono</th>                      
            <th class="editText">Obra Social</th>
            <th class="editText">N°Afiliado</th>
            <th class="editText">Plan</th>
            <th class="editText">Seleccionar</th>                              
          </tr>
        </thead>
        <tbody id="pacientes-list" name="pacientes-list">
          
      </tbody>
    </table>
</div>

<input hidden id="medico_id" value="{{$medico_id}}">
<input hidden id="user_id" value="{{$medico_id}}">
<br>
<div id="seccion_listado_pacientes" class="table-responsive" style="height:500px; overflow-y: scroll;">
    <table class="table table-condensed tabla_con_borde" id="tabla_pacientes" name="tabla_pacientes">
     <thead>
        <tr>
          <th class="editText rodri_th letra_size_1rem" scope="col">#</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Horario</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Paciente</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">DNI</th> 
          <th class="editText rodri_th letra_size_1rem" scope="col">Telefono</th>                                 
          <th class="editText rodri_th letra_size_1rem" scope="col">Primer Consulta</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Sobreturno</th>                        
          <!--<th class="editText rodri_th letra_size_1rem" scope="col">Ingresar</th>                     -->
        </tr>
      </thead>
      <tbody id="pacientes-list" name="pacientes-list">
        <?php $cont = 1 ?>
        @foreach($listadoPacientes as $lp)
          @if($lp['dni']!=99999)
            <tr>
              <th scope="row" class="editText letra_size_1rem">{{$cont++}}</th>
              <td class="letra_size_1rem">{{$lp['horario']}}</td>
              <td class="letra_size_1rem">{{$lp['nombre']}}</td>          
              <td class="letra_size_1rem">{{$lp['dni']}}</td>
              <td class="letra_size_1rem">{{$lp['telefono']}}</td>
              <td class="letra_size_1rem">{{$lp['primerControl']}}</td>
              @if($lp['sobreturno'] == 0)
                <td class="letra_size_1rem">NO</td>
              @else
                <td class="letra_size_1rem">SI</td>
              @endif
              <!--<td>    	          
    	          	<input type="hidden" id="paciente_id" name="paciente_id" value="{{$lp['dni']}}"/> 
    	          	<button onclick="validarPaciente('{{$lp['dni']}}')" type="submit" class="rodri_button_aceptar_si">></button>    	      	  
          	  </td>-->
          	</tr>
          @endif
      	@endforeach
    </tbody>
  </table>
  </div>
  @endif

  <div hidden id="seccion_paciente_seleccionado" class="margin_left_20px">
  <div class="row">
    <div class="margin_left_20px_solo_cel">
      <label for="text" class="col-sm-0 control-label">DNI</label>      
      <input type="text" class="form-control input_width_250px" id="dni" name="dni" value=""  readonly />
    </div>

    <div class="margin_left_20px">
      <label for="text" class="col-sm-0 control-label">Nombre</label>      
      <input type="text" class="form-control input_width_250px" id="nombre" name="nombre" value="" readonly/>
    </div>

    <div class="margin_left_20px">
      <label for="text" class="col-sm-0 control-label">Apellido</label>      
      <input type="text" class="form-control input_width_250px" id="apellido" name="apellido" readonly />
    </div>

    <div class="margin_left_20px">
    <label for="text" class="col-sm-0 control-label">Fecha Nacimiento</label><br>
    <div class="row">
      <div class="input_width_50px margin_left_20px">
        <input type="text" maxlength="2" class="form-control" id="fecha_nacimiento_dia" name="fecha_nacimiento"  readonly />
      </div>
      <label for="text" class="col-sm-0 control-label margin_left_5px margin_top_5px margin_right_5px">/</label>
      <div class="input_width_50px margin_left_5px">
        <input type="text" maxlength="2" class="form-control" id="fecha_nacimiento_mes" name="fecha_nacimiento" readonly />
      </div>
      <label for="text" class="col-sm-0 control-label margin_left_5px margin_top_5px margin_right_5px">/</label>
      <div class="input_width_110px">   
        <input type="text" maxlength="4" class="form-control" id="fecha_nacimiento_anio" name="fecha_nacimiento" readonly />
      </div>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="margin_left_20px_solo_cel">
      <label for="text" class="col-sm-0 control-label">Telefono</label>      
      <input type="text" class="form-control input_width_250px" id="telefono" name="telefono"  readonly />
    </div>

    <div class="margin_left_20px">
      <label for="text" class="col-sm-0 control-label">Domicilio</label>      
      <input type="text" class="form-control input_width_350px" id="domicilio" name="domicilio" readonly/>
    </div>
    </div>
    <div class="row">
    <div class="margin_left_20px_solo_cel">
      <label for="text" class="col-sm-0 control-label">Obra Social</label>      
      <input type="text" class="form-control input_width_250px" id="obrasocial" name="obra_social" readonly/>
    </div>

    <div class="margin_left_20px">
      <label for="text" class="col-sm-0 control-label">N° Afiliado</label>      
      <input type="text" class="form-control input_width_250px" id="numero_afiliado" name="numero_afiliado" readonly/>
    </div>

    <div class="margin_left_20px">
      <label for="text" class="col-sm-0 control-label">Plan</label>      
      <input type="text" class="form-control input_width_250px" id="plan_obra_social" name="plan_obra_social" readonly/>
    </div>    
  </div>
  <br>
      <div class="row contenedor3 margin_top_20px">            
        <button onclick="volver()" type="button" id="btnContinuar" class="rodri_button contenido3">Volver</button>
      </div>
      <br>
</div>

  <script type="text/javascript">
    
    
  function validarPacienteExiste() {
    var texto = document.getElementById("texto_buscar").value;
    var medico_id = document.getElementById("medico_id").value;
    var esNumero = 1;
    if(isNaN(texto)){
      esNumero = 0;
    }   
    
    $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/paciente_consultar',
         data:{texto :texto, esNumero:esNumero, medico_id:medico_id, _token: '{{csrf_token()}}'},
         success:function(data) {                            
            if(data.cantPacientes > 0) {    
              cargarTabla(data);
              document.getElementById("seccion_tabla_buscar_paciente").hidden = false;              
            } else {              
              document.getElementById("seccion_tabla_buscar_paciente").hidden = true;    
              mostrarSnackbar("El paciente no existe");              
            }
         }
      });
  }

   function cargarTabla(data){
      var contador = 0;
      $("#tabla_pacientes").find("tr:gt(0)").remove();
      for (i = 0; i < data.pacientes.length; i++){
          contador = contador + 1;                                  
          var paciente = "<tr><td class='editText'>"+contador+"</td><td class='editText'>"+data.pacientes[i].apellido+"</td><td class='editText'>"+data.pacientes[i].nombre+"</td><td class='editText'>"+data.pacientes[i].dni+"</td><td class='editText'>"+data.pacientes[i].telefono+"</td><td class='editText'>"+data.pacientes[i].obra_social+"</td><td class='editText'>"+data.pacientes[i].numero_afiliado+"</td><td class='editText'>"+data.pacientes[i].obra_social_plan+"</td><td><button class='rodri_button_aceptar_si' onclick='seleccionarPaciente("+data.pacientes[i].id+")'>></button></td></tr>";                    
          $('#pacientes-list').append(paciente); 
      }
    }

    function seleccionarPaciente(id){
      var medico_id = document.getElementById("medico_id").value;
      document.getElementById("paciente_seleccionado_id").value = id;
      document.getElementById("seccion_tabla_buscar_paciente").hidden = true;
      document.getElementById("seccion_buscar_paciente").hidden = true;
      
      mostrarPanelPaciente(true);
      mostrarPanelHistoriaClinica(false);
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/get_paciente_id',
         data:{paciente_id :id, medico_id:medico_id,_token: '{{csrf_token()}}'},
         success:function(data) {               
            if(data.paciente != null) {
              document.getElementById("seccion_listado_pacientes").hidden = true;                  
              document.getElementById("seccion_paciente_seleccionado").hidden = false;                  
              document.getElementById("dni").value = data.paciente.dni;
              document.getElementById("nombre").value = data.paciente.nombre;
              document.getElementById("apellido").value = data.paciente.apellido;
              if(data.paciente.fecha_nacimiento != null){
                var fecha_nacimiento_array = data.paciente.fecha_nacimiento.split("-");             
                document.getElementById("fecha_nacimiento_dia").value = fecha_nacimiento_array[2];
                document.getElementById("fecha_nacimiento_mes").value = fecha_nacimiento_array[1];
                document.getElementById("fecha_nacimiento_anio").value = fecha_nacimiento_array[0];
              }
              document.getElementById("telefono").value = data.paciente.telefono;
              document.getElementById("domicilio").value = data.paciente.domicilio;
              document.getElementById("obrasocial").value = data.paciente.obra_social;
              document.getElementById("numero_afiliado").value = data.paciente.numero_afiliado;
              document.getElementById("plan_obra_social").value = data.paciente.obra_social_plan;
            } 
         }
      });
    }

    function vaciarCampos(){    
    document.getElementById("texto_buscar").value = '';
    document.getElementById("dni").value = '';
    document.getElementById("nombre").value = '';
    document.getElementById("apellido").value = '';
    document.getElementById("fecha_nacimiento_dia").value = '';
    document.getElementById("fecha_nacimiento_mes").value = '';
    document.getElementById("fecha_nacimiento_anio").value = '';
    document.getElementById("telefono").value = '';
    document.getElementById("domicilio").value = '';
    document.getElementById("obrasocial").value = '';
    document.getElementById("numero_afiliado").value = '';
    document.getElementById("plan_obra_social").value = '';
    }

    function volver(){
      document.getElementById("seccion_buscar_paciente").hidden = false;
      document.getElementById("paciente_seleccionado_id").value = 0;
      document.getElementById("seccion_paciente_seleccionado").hidden = true;
      vaciarCampos();
      mostrarPanelPaciente(false);
      mostrarPanelHistoriaClinica(true);
      document.getElementById("seccion_listado_pacientes").hidden = false; 
    }
  </script>
  
@endsection
