<div id="modal_interconsultores" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"         
              id="modalTitleInterconsultores">Interconsultores</h4>
         </div>
          <div class="modal-body"> 
          <div class="row">
             <form class="card background_panel">
               <div class="row margin_left_5px">
                <h4 class="letrasblancas"><b>Nuevo</b></h4> 
               </div>
               <div class="row margin_left_5px">                    
                  
                <div class="row">       
                    <input type="hidden" id="id_interconsultor">
                    <div class="margin_left_20px">
                      <small>Nombre</small>      
                      <input type="text" class="form-control input_width_250px" id="interconsultores_nombre" name="interconsultores_nombre"  placeholder="Nombre" value=""  />
                    </div>

                    <div class="margin_left_20px">
                      <small>Apellido</small>      
                      <input type="text" class="form-control input_width_250px" id="interconsultores_apellido" name="interconsultores_apellido"  placeholder="Apellido"  />
                    </div>

                    <div class="margin_left_20px">
                        <small>Especialidad</small>      
                      <input type="text" class="form-control input_width_350px" id="interconsultores_especialidad" name="interconsultores_especialidad"  placeholder="Especialidad"  />
                   </div>

                </div>

                <div class="row">
                   <div class="margin_left_20px">
                        <small>Direccion</small>      
                      <input type="text" class="form-control input_width_350px" id="interconsultores_direccion" name="interconsultores_direccion"  placeholder="Direccion"  />
                   </div>

                   <div class="margin_left_20px">
                        <small>TEL (Consultorio)</small>      
                      <input type="text" class="form-control input_width_150px" id="interconsultores_telefono_c" name="interconsultores_telefono_c"  placeholder="Tel Consultorio"  />
                   </div>

                   <div class="margin_left_20px">
                        <small>TEL (Particular)</small>      
                      <input type="text" class="form-control input_width_150px" id="interconsultores_telefono_p" name="interconsultores_telefono_p"  placeholder="Tel Particular"  />
                   </div>

                </div>
                <br>
            
              </div>
              <br>
              <div class="row contenedor3">
                <button type="button" id="btn_guardar" onclick="guardarDatosInterconsultores()" class="rodri_button_aceptar contenido3">GUARDAR</button>
                <button hidden id="btn_modificar" type="button" onclick="modificarDatosInterconsultores()" class="rodri_button_aceptar contenido3">MODIFICAR</button>
                <button hidden id="btn_eliminar" type="button" onclick="eliminarDatosInterconsultores()" class="rodri_button_cancelar contenido3">ELIMINAR</button>
            </div>
            </form>
            
          </div><br><br>


          <div class="row"> 

            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}"/> 
            <div id="seccion_buscar_paciente">
              <h4 class="editText">Filtrar</h4>            
              <input class="editText" type="text" id="texto_buscar_interconsultor" name="texto_buscar_interconsultor" value="" placeholder="" onchange="filtrarInterconsultor()" />     
            </div>
            <div id="seccion_tabla_buscar_interconsultores_2" class="table-responsive margin_top_20px" style="height:500px; overflow-y: scroll;">
                <table class="table table-condensed" id="tabla_interconsultores_2" name="tabla_interconsultores_2">
                 <thead class="fondoNav text-white">
                    <tr>            
                      <th class="editText">Especialidad</th>
                      <th class="editText">Apellido</th>
                      <th class="editText">Nombre</th>
                      <th class="editText">Dirección</th>
                      <th class="editText">Tel Consultorio</th>                      
                      <th class="editText">Tel Particular</th>                                  
                      <th class="editText">Seleccionar</th>                              
                    </tr>
                  </thead>
                  <tbody id="interconsultores_list_2" name="interconsultores_list_2">
                     
                     
                        
                       
                      
                </tbody>
              </table>
          </div>
        </div>

        <div class="modal-footer">                      
           <button type="button" 
           class="rodri_button_volver" 
           data-dismiss="modal">Salir</button>             
        </div>
    </div>
    
    </div>
  </div>
</div>


<script type="text/javascript">


  function mostrarInterconsultores(){
    filtrarInterconsultor();    
    $('#modal_interconsultores').modal();     
  }

  function filtrarInterconsultor(){    
      var texto = document.getElementById("texto_buscar_interconsultor").value;      
      var esNumero = 1;
      if(isNaN(texto)){
        esNumero = 0;
      }         
      $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/filtrar_interconsultores',
           data:{texto :texto, esNumero:esNumero, _token: '{{csrf_token()}}'},
           success:function(data) {                                          
              if(data.interconsultores != null) {    
                cargarTablaInt(data);                
              } else {
                
              }
           }
        });
  }
  
  function guardarDatosInterconsultores() {
    var nombre = document.getElementById("interconsultores_nombre").value;
    var apellido = document.getElementById("interconsultores_apellido").value;
    var especialidad = document.getElementById("interconsultores_especialidad").value;
    var direccion = document.getElementById("interconsultores_direccion").value;
    var telefono_p = document.getElementById("interconsultores_telefono_p").value;
    var telefono_c = document.getElementById("interconsultores_telefono_c").value;
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_interconsultores',
           data:{nombre:nombre, apellido:apellido, especialidad:especialidad, direccion:direccion, telefono_p:telefono_p, telefono_c:telefono_c,_token: '{{csrf_token()}}'},
            success:function(data){                             
              if(data.response_data != null && data.response == 1){                 
                mostrarSnackbar("GUARDADO");
                cargarTablaInt(data);        
                borrarCamposInterconsultor();       
              } else {
                mostrarSnackbar("interconsultores FALLO");                
              }
            }
        });   
  }

  function cargarTablaInt(data){
      var contador = 0;
      $("#tabla_interconsultores_2").find("tr:gt(0)").remove();    
      var count = Object.keys(data.interconsultores).length;
      
      for (i = 0; i < count; i++){       

          var interconsultor = '<tr><td class="editText">'+data.interconsultores[i].especialidad+'</td><td class="editText">'+data.interconsultores[i].apellido+'</td><td class="editText">'+data.interconsultores[i].nombre+'</td><td class="editText">'+data.interconsultores[i].direccion+'</td><td class="editText">'+data.interconsultores[i].telefono_consultorio+'</td><td class="editText">'+data.interconsultores[i].telefono_particular+'</td><td class="editText"><button onClick=seleccionarInterconsultor("'+data.interconsultores[i].id+'") class="rodri_button_aceptar_si editText">></button></td></tr>';

          $('#interconsultores_list_2').append(interconsultor);  
      }
  }

  function modificarDatosInterconsultores(){
    var id = document.getElementById("id_interconsultor").value;
    var nombre = document.getElementById("interconsultores_nombre").value;
    var apellido = document.getElementById("interconsultores_apellido").value;
    var especialidad = document.getElementById("interconsultores_especialidad").value;
    var direccion = document.getElementById("interconsultores_direccion").value;
    var telefono_p = document.getElementById("interconsultores_telefono_p").value;
    var telefono_c = document.getElementById("interconsultores_telefono_c").value;
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/modificar_interconsultor',
           data:{id:id,nombre:nombre, apellido:apellido, especialidad:especialidad, direccion:direccion, telefono_p:telefono_p, telefono_c:telefono_c,_token: '{{csrf_token()}}'},
            success:function(data){                             
              if(data.interconsultores != null && data.response == 1){                 
                mostrarSnackbar("CAMBIOS GUARDADOS");
                cargarTablaInt(data);        
                borrarCamposInterconsultor();
                document.getElementById("btn_guardar").hidden = false;
                document.getElementById("btn_modificar").hidden = true;
                document.getElementById("btn_eliminar").hidden = true;      
              } else {
                mostrarSnackbar("interconsultores FALLO");                
              }
            }
        });   
  }

  function eliminarDatosInterconsultores(){
    var id = document.getElementById("id_interconsultor").value;   
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/eliminar_interconsultor',
           data:{id:id, _token: '{{csrf_token()}}'},
            success:function(data){                             
              if(data.interconsultores != null && data.response == 1){                 
                mostrarSnackbar("PROFESIONAL ELIMINADO");
                cargarTablaInt(data);        
                borrarCamposInterconsultor();
                document.getElementById("btn_guardar").hidden = false;
                document.getElementById("btn_modificar").hidden = true;
                document.getElementById("btn_eliminar").hidden = true;      
              } else {
                mostrarSnackbar("interconsultores FALLO");                
              }
            }
        });   
  }

  function seleccionarInterconsultor(id){
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_interconsultor',
           data:{id:id, _token: '{{csrf_token()}}'},
            success:function(data){                             
              if(data.response_data != null && data.response == 1){                                       
                  borrarCamposInterconsultor();       
                  document.getElementById("id_interconsultor").value = data.response_data.id;
                  document.getElementById("interconsultores_nombre").value = data.response_data.nombre;
                  document.getElementById("interconsultores_apellido").value = data.response_data.apellido;
                  document.getElementById("interconsultores_especialidad").value = data.response_data.especialidad;
                  document.getElementById("interconsultores_direccion").value = data.response_data.direccion;
                  document.getElementById("interconsultores_telefono_p").value = data.response_data.telefono_particular;
                  document.getElementById("interconsultores_telefono_c").value = data.response_data.telefono_consultorio;
                  mostrarSnackbar("PROFESIONAL SELECCIONADO"); 
                  document.getElementById("btn_guardar").hidden = true;
                  document.getElementById("btn_modificar").hidden = false;
                  document.getElementById("btn_eliminar").hidden = false;
              } else {
                mostrarSnackbar("Interconsultores FALLO");               
              }
            }
        });   
  }

  function borrarCamposInterconsultor(){
    document.getElementById("id_interconsultor").value = "";
    document.getElementById("interconsultores_nombre").value = "";
    document.getElementById("interconsultores_apellido").value = "";
    document.getElementById("interconsultores_especialidad").value = "";
    document.getElementById("interconsultores_direccion").value = "";
    document.getElementById("interconsultores_telefono_c").value = "";
    document.getElementById("interconsultores_telefono_p").value = "";
  }

</script>


