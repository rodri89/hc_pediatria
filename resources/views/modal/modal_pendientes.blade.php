<div class="modal fade" id="modalPendientes" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">      	
        <h4 class="modal-title"         
        id="modalTitleMensaje">Pendientes</h4>
      </div>      
      
      <div class="modal-body">   
      <p>Ingrese información pendiente:</p>    	
        <textarea  id="nueva_consulta_pendiente" name="nueva_consulta_pendiente" rows="10" cols="50"></textarea>
        <input hidden id="nueva_consulta_pendiente_opcion">
        <br>
        <small>Esta información será recordada al abrir una nueva consulta.</small>
      </div>
      
      <div class="modal-footer">
        <button hidden id="modal_pendiente_aceptar" type="button" 
           class="rodri_button_aceptar" 
           data-dismiss="modal">Aceptar</button>        
        <button id="modal_pendiente_cancelar" type="button" 
           class="rodri_button_cancelar"
           onclick="guardarPendienteCancelar()" 
           data-dismiss="modal">Cancelar</button>        
        <button id="modal_pendiente_guardar" type="button"
           onclick="guardarPendiente(1)"
           class="rodri_button_aceptar" 
           data-dismiss="modal">Guardar</button>        
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalPendientesCargar" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">        
        <h4 class="modal-title"         
        id="modalTitleMensaje">Pendientes</h4>
      </div>      
       
      <div class="modal-body">                
        <textarea  id="nueva_consulta_pendiente_cargar" name="nueva_consulta_pendiente_cargar" rows="10" cols="50"></textarea>
        <br>
      </div>
      
      <div class="modal-footer">
        <button type="button" 
           class="rodri_button_aceptar" 
           data-dismiss="modal">Aceptar</button>              
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalPendientesCargarGuardar" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">        
        <h4 class="modal-title"         
        id="modalTitleMensaje">Pendientes</h4>
      </div>      
       
      <div class="modal-body">    
        <p>Tiene pendientes ¿Deseas guardarlos?</p>            
        <textarea  id="nueva_consulta_pendiente_cargar_guardar" name="nueva_consulta_pendiente_cargar_guardar" rows="10" cols="50"></textarea>
        <br>
      </div>
      
      <div class="modal-footer">          
        <button type="button" 
           onclick="guardarPendienteVacio()"
           class="rodri_button_cancelar" 
           data-dismiss="modal">No</button>        
        <button type="button"
           onclick="guardarPendiente(2)"
           class="rodri_button_aceptar" 
           data-dismiss="modal">Si</button>        
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  
  function guardarPendientesModal(opcion) { 
      var consulta_id = null;//document.getElementById("consulta_id").value;
      var paciente_id = document.getElementById("paciente_id").value;       
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_pendientes',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, opcion:opcion, _token: '{{csrf_token()}}'},
         success:function(data) { 
            if(data.response_data != null && data.response_data.pendientes.localeCompare('')!= 0) {
                $('#nueva_consulta_pendiente').val(data.response_data.pendientes);                         
            } 
            $('#nueva_consulta_pendiente_opcion').val(data.opcion);
            $('#modalPendientes').modal(); 
          }
      });    
  }

  function guardarPendienteVacio(){
    document.getElementById("nueva_consulta_pendiente").value = '';
    guardarPendiente();
    //sleep( 3000 );
    establecerActivo(); 
  }

  function guardarPendiente(opcion) {
    var consulta_id = document.getElementById("consulta_id").value;
    var paciente_id = document.getElementById("paciente_id").value; 
    if(opcion == 1)
      var pendiente = document.getElementById("nueva_consulta_pendiente").value;
    if(opcion == 2)
      var pendiente = document.getElementById("nueva_consulta_pendiente_cargar_guardar").value;
    $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/guardar_pendientes',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, pendiente:pendiente, opcion:opcion,_token: '{{csrf_token()}}'},
         success:function(data) { 
          if(data.response_data != null && data.response_data.pendientes.localeCompare('')!= 0){
              mostrarSnackbar("PENDIENTES GUARDADOS");       
              var opc = document.getElementById("nueva_consulta_pendiente_opcion").value;
              document.getElementById("consulta_nuevo_pendiente").value = opc;
            }
          if(data.opcion != null && data.opcion == 2)
            establecerActivo(); 
          var cargoPendientes = document.getElementById("consulta_nuevo_pendiente").value;          
          if(data.opcion != null && data.opcion == 1 && cargoPendientes == 2)
            establecerActivo(); 
          }
      }); 
  }

  function guardarPendienteCancelar(){
      var consulta_id = document.getElementById("consulta_id").value;
      var paciente_id = document.getElementById("paciente_id").value; 
      var cargoPendientes = document.getElementById("consulta_nuevo_pendiente").value;
      if(cargoPendientes == 2){
        $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/guardar_pendientes_cancelar',
             data:{paciente_id:paciente_id, consulta_id:consulta_id,_token: '{{csrf_token()}}'},
             success:function(data) { 
                establecerActivo();               
              }
          }); 
      }
  }

  function checkPendientes() {
      var consulta_id = null;//document.getElementById("consulta_id").value;
      var paciente_id = document.getElementById("paciente_id").value;       
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_pendientes',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, _token: '{{csrf_token()}}'},
         success:function(data) { 
            if(data.response_data != null && data.response_data.pendientes.localeCompare('')!= 0) {            
              document.getElementById("tienePendientesAlerta").hidden = false;
              $('#nueva_consulta_pendiente_cargar').val(data.response_data.pendientes);         
              $('#modalPendientesCargar').modal();
            } else {
              document.getElementById("tienePendientesAlerta").hidden = true;
            } 
          }
      }); 
  }

  function guardarDatosVerificarPendiente(){
      var consulta_id = null;//document.getElementById("consulta_id").value;
      var paciente_id = document.getElementById("paciente_id").value;       
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_pendientes',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, _token: '{{csrf_token()}}'},
         success:function(data) { 
            if(data.response_data != null && data.response_data.pendientes.localeCompare('')!= 0) {
                $('#nueva_consulta_pendiente_cargar_guardar').val(data.response_data.pendientes);         
                $('#modalPendientesCargarGuardar').modal();
            } else {
                document.getElementById("nueva_consulta_pendiente").value = '';
                guardarPendiente();
              //  sleep( 3000 );
                establecerActivo(); 
            }
          }
      }); 
  }

  function cargarPendientes(){
    var consulta = document.getElementById("consulta_id").value;
    var paciente = document.getElementById("paciente_id").value;        
    borrarSeccion("seccion_pendiente_consulta");    
    $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_pendientes',
           data:{consulta_id:consulta, paciente_id:paciente,_token: '{{csrf_token()}}'},
            success:function(data){                                           
              if(data.response_data != null && data.response == 1 && data.response_data.pendientes.localeCompare('')!= 0){                            
                document.getElementById("seccion_pendiente_consulta").hidden = false;
                var seccion_resumen = document.getElementById("seccion_pendiente_consulta");
                addTituloDescripcion("Pendientes", data.response_data.pendientes, seccion_resumen);                                
              } else {                
                document.getElementById("seccion_pendiente_consulta").hidden = true;
              }
            }
        });
  }

</script>