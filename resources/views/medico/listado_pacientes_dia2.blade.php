
@extends('plantillas/plantilla_medico')

@if($listadoPacientes != null)
  @section('title_header','Listado Pacientes')
@else
  @section('title_header','Home')
@endif
@section('contenedor')

@if($listadoPacientes != null)
<input hidden id="medico_id" value="{{$medico_id}}">
<input hidden id="user_id" value="{{$medico_id}}">
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
          <th class="editText rodri_th letra_size_1rem" scope="col">Ingresar</th>                     
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
              <td>    	          
    	          	<!--<input type="hidden" id="paciente_id" name="paciente_id" value="{{$lp['dni']}}"/> -->
    	          	<button onclick="validarPaciente('{{$lp['dni']}}')" type="submit" class="rodri_button_aceptar_si">></button>    	      	  
          	  </td>
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

<div id="modal_validar_dni" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-m">
    <div class="modal-content">
      <div class="modal-body"> 
         <div class="modal-header">       
            <h4 class="modal-title"         
              id="modalTitleMensaje">Alta Paciente</h4>
          </div> 
          <div>
            <br>                                
            <label for="text" class="col-sm-0 control-label">DNI</label>      
            <input type="text" class="form-control editText" id="modal_dni" placeholder="DNI"/>          

            <label for="text" class="col-sm-0 control-label margin_top_10px">* Nombre</label>      
            <input type="text" class="form-control editText" id="modal_nombre"  placeholder="Nombre Paciente" />

            <label for="text" class="col-sm-0 control-label margin_top_10px">* Apellido</label>      
            <input type="text" class="form-control editText" id="modal_apellido"  placeholder="Apellido Paciente"  />

            <label for="text" class="col-sm-0 control-label editText margin_top_10px">* Fecha Nacimiento</label><br>
            <div class="row">
               <div class="fechaNacEditText">
                <input type="text" maxlength="2" class="form-control editText" id="modal_fecha_nacimiento_dia" name="fecha_nacimiento"  placeholder="dd" />
              </div>
               <label for="text" class="col-sm-0 control-label editText margin5">/</label>
              <div class="fechaNacEditText">
                <input type="text" maxlength="2" class="form-control editText" id="modal_fecha_nacimiento_mes" name="fecha_nacimiento"  placeholder="mm" />
              </div>
              <label for="text" class="col-sm-0 control-label margin5">/</label>
              <div class="fechaNacAnioEditText">   
                <input type="text" maxlength="4" class="form-control editText" id="modal_fecha_nacimiento_anio" name="fecha_nacimiento"  placeholder="YYYY" />
              </div>
              <input type="hidden" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"  />
           </div>        

           <label for="text" class="col-sm-0 control-label margin_top_10px">* Sexo</label>   
           <div class="margin_left_5px">
              <div class="row margin_left_5px">
                <div class="custom-control custom-radio">
                  <input type="radio" id="sexo_m" name="sexo" class="custom-control-input">
                  <label class="custom-control-label" for="sexo_m">M</label>
                </div>
                <div class="custom-control custom-radio">
                  <input type="radio" id="sexo_f" name="sexo" class="custom-control-input">
                  <label class="custom-control-label margin_left_5px" for="sexo_f">F</label>
                </div>
              </div>        
            </div>

            <label for="text" class="col-sm-0 control-label margin_top_10px">Hermanos</label>
            <div>          
                <select class="form-control input_width_60px margin_left_5px" id="modal_cantidad_hermanos" name="cantidad_hermanos">            
                  <option>0</option>
                  <option>1</option><option>2</option><option>3</option>            
                  <option>4</option><option>5</option><option>6</option>        
                  <option>7</option><option>8</option><option>9</option>        
              </select>
            </div>               

            <label for="text" class="col-sm-0 control-label margin_top_10px">Localidad</label>      
            <input type="text" class="form-control input_width_350px" id="modal_localidad" name="modal_localidad"  placeholder="Localidad"  />

            <label for="text" class="col-sm-0 control-label margin_top_10px">Domicilio</label>      
            <input type="text" class="form-control editText" id="modal_domicilio"  placeholder="Domicilio" />

            <label for="text" class="col-sm-0 control-label margin_top_10px">* Telefono</label>      
            <input type="text" class="form-control editText" id="modal_telefono"  placeholder="Telefono solo numeros (EJ: 2915050050)" />          

            <label for="text" class="col-sm-0 control-label margin_top_10px">Mail</label>      
            <input type="text" class="form-control editText" id="modal_mail" placeholder="Mail"  />

            <label for="text" class="col-sm-0 control-label margin_top_10px">Nombre Madre</label>      
            <input type="text" class="form-control input_width_250px" id="modal_nombre_madre" name="modal_nombre_madre" placeholder="Nombre Madre"  />

            <label for="text" class="col-sm-0 control-label margin_top_10px">Nombre Padre</label>      
            <input type="text" class="form-control input_width_250px" id="modal_nombre_padre" name="modal_nombre_padre" placeholder="Nombre Padre"  />            
            <label for="text" class="col-sm-0 control-label margin_top_10px">Obra Social</label>      
            <input type="text" class="form-control editText" id="modal_obra_social" placeholder="Obra Social"  />

            <label for="text" class="col-sm-0 control-label margin_top_10px">Numero Afiliado</label>      
            <input type="text" class="form-control editText" id="modal_numero_afiliado" placeholder="Numero Afiliado"  />

            <label for="text" class="col-sm-0 control-label margin_top_10px">Plan</label>      
            <input type="text" class="form-control editText" id="modal_plan_obra_social" placeholder="Plan Obra Social"  />
          </div> 
          <br>
          <p hidden id="modal_msj_error" class="letrasrojo"></p>
          <br>
        <div class="modal-footer">
           <button type="button"             
             class="rodri_button_cancelar" 
           data-dismiss="modal">Cancelar</button>             
           <button type="button" 
           onclick="registrarPaciente()" 
            class="rodri_button_aceptar">Registrar</button>             
      </div>
    </div>
  </div>
</div>




  <script type="text/javascript">
    function validarPaciente(dni){        
        var medico_id = document.getElementById("medico_id").value;            
        $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/paciente_consultar_tobb',
             data:{dni:dni, medico_id:medico_id, _token: '{{csrf_token()}}'},
             success:function(data) {                            
                  //alert(data.response);
                  // 1 quiere decir que el paciente ya existe en el sistema. Solo debo seleccionarlo.
                  if(data.response == 1) {
                      document.getElementById("seccion_listado_pacientes").hidden = true;
                      document.getElementById("seccion_paciente_seleccionado").hidden = false;                  
                      document.getElementById("dni").value = data.paciente.dni;
                      document.getElementById("nombre").value = data.paciente.nombre;
                      document.getElementById("apellido").value = data.paciente.apellido;
                      if(data.paciente.fecha_nacimiento != null) {
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
                  } else {
                    // si response es 2 quiere decir que el paciente no existe en el sistema y vamos a usar los datos de TOBB para crearlo.
                    // el medico debera validar el DNI (en caso de que sea del padre) e ingresar los datos restantes.                    
                    borrarCamposModal();
                    $('#modal_dni').val(data.paciente.dni);
                    $('#modal_nombre').val(data.paciente.nombre);
                    $('#modal_apellido').val(data.paciente.apellido);
                    //2020-01-24
                    var fecha_nacimiento_aux = data.paciente.fecha_nacimiento.split("-");                    
                    $('#modal_fecha_nacimiento_dia').val(fecha_nacimiento_aux[2]);
                    $('#modal_fecha_nacimiento_mes').val(fecha_nacimiento_aux[1]);
                    $('#modal_fecha_nacimiento_anio').val(fecha_nacimiento_aux[0]);
                    $('#modal_telefono').val(data.paciente.telefono);
                    $('#modal_domicilio').val(data.paciente.domicilio);
                    $('#modal_mail').val(data.paciente.mail);
                    $('#modal_obra_social').val(data.paciente.obra_social);
                    $('#modal_numero_afiliado').val(data.paciente.numero_afiliado);
                    $('#modal_plan_obra_social').val(data.paciente.obra_social_plan);
                    
                    $('#modal_validar_dni').modal();
                  }                                  
             }
          });
    }

    /*function registrarPaciente() {
        var sexo_m = document.getElementById("sexo_m").checked;
        var sexo_f = document.getElementById("sexo_f").checked;
        var sexo = 0;
        if(sexo_m)
          sexo = 1;
        if(sexo_f)
          sexo = 2
        if(sexo == 0){
          var error = document.getElementById("modal_msj_error");
          error.hidden = false;
          error.innerHTML = "Debe seleccionar sexo del paciente."
        } else {
          $('#modal_validar_dni').modal("hide");        
          altaPaciente();          
        }
    }

    function altaPaciente(){
      var medico_id = document.getElementById("medico_id").value;    
      var user_id = document.getElementById("user_id").value;
      var nombre = document.getElementById("modal_nombre").value;
      var apellido = document.getElementById("modal_apellido").value;
      var dni = document.getElementById("modal_dni").value;    
      var telefono = document.getElementById("modal_telefono").value;
      
      var hermanos = document.getElementById("modal_cantidad_hermanos").value;
      var localidad = document.getElementById("modal_localidad").value;
      var nombre_padre = document.getElementById("modal_nombre_padre").value;
      var nombre_madre = document.getElementById("modal_nombre_madre").value;
      var sexo = 'F';
      if(document.getElementById("sexo_m").checked){
        sexo = 'M';
      } 

      var domicilio = document.getElementById("modal_domicilio").value;
      var mail = document.getElementById("modal_mail").value;
      var obrasocial = document.getElementById("modal_obra_social").value; 
      var numero_afiliado = document.getElementById("modal_numero_afiliado").value; 
      var plan = document.getElementById("modal_plan_obra_social").value;         
      var fecha_nacimiento_dia = document.getElementById("modal_fecha_nacimiento_dia").value;     
      var fecha_nacimiento_mes = document.getElementById("modal_fecha_nacimiento_mes").value;     
      var fecha_nacimiento_anio = document.getElementById("modal_fecha_nacimiento_anio").value;
      var fecha_nacimiento = null;
      if((fecha_nacimiento_dia!=null)&&(fecha_nacimiento_dia.localeCompare('')!=0)){
        var fecha_nacimiento = fecha_nacimiento_anio+"/"+fecha_nacimiento_mes+"/"+fecha_nacimiento_dia;
      }    
     $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/alta_paciente_medico_secretaria',
           data:{medico_id:medico_id,user_id:user_id, dni :dni,nombre:nombre,apellido:apellido,fecha_nacimiento:fecha_nacimiento,telefono:telefono,mail:mail,obra_social:obrasocial,numero_afiliado:numero_afiliado,plan:plan, domicilio:domicilio, localidad:localidad,
            nombre_madre:nombre_madre, nombre_padre:nombre_padre, sexo:sexo, hermanos:hermanos, _token: '{{csrf_token()}}'},
           success:function(data){              
            if(data.paciente!=null){
                borrarCamposModal();

           }
         }
        });
  }*/


    function borrarCamposModal(){
      $('#modal_dni').val("");
      $('#modal_nombre').val("");
      $('#modal_apellido').val("");          
      $('#modal_fecha_nacimiento_dia').val("");
      $('#modal_fecha_nacimiento_mes').val("");
      $('#modal_fecha_nacimiento_anio').val("");
      $('#modal_telefono').val("");
      $('#modal_domicilio').val("");
      $('#modal_mail').val("");
      $('#modal_obra_social').val("");
      $('#modal_numero_afiliado').val("");
      $('#modal_plan_obra_social').val("");
      $('#modal_cantidad_hermanos').val(0);
      $('#modal_localidad').val("");
      $('#modal_nombre_madre').val("");
      $('#modal_nombre_padre').val("");
      document.getElementById("sexo_m").checked = false;
      document.getElementById("sexo_f").checked = false;
      document.getElementById("modal_msj_error").hidden = true;
    }

    function volver(){
       document.getElementById("seccion_listado_pacientes").hidden = false;
       document.getElementById("seccion_paciente_seleccionado").hidden = true;
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
  </script>
  
@endsection
