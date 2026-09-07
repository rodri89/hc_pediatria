@include('modal.snackbar')
<div id="modal_screening" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body"> 
         <div class="modal-header">       
            <h4 class="modal-title"         
              id="modalTitleScreening">Screening</h4>
          </div> 

          <div class="modal-body">            
            <input hidden id="modal_screening_id"></input>                
            <input hidden id="modal_screening_numero"></input>                
              <div class="row float_right">                
                  <label class="margin_top_10px input_width_60px"><b>Filtrar</b></label>
                  <input id="modal_screening_filtrar" class="form-control input_width_350px margin_left_5px margin_top_5px type="text" onchange="filtrarScreening()"></input>                
              </div>
            
              <div id="seccion_modal_medicamentos_agregar_nuevo">
                
                  <small class="margin_top_5px"><b>Fecha Solicitud</b></small>
                  <input id="modal_screening_fecha_solicitud" class="form-control input_width_350px margin_left_5px margin_top_5px type="text"></input>
                
                
                  <small class="margin_top_5px input_width_80px"><b>Evaluación</b></small>
                  <input id="modal_screening_evaluacion" class="form-control input_width_350px margin_left_5px margin_top_5px" type="text"></input>
                
               
                  <small class="margin_top_5px input_width_80px"><b>Resultado</b></small>
                  <textarea class="form-control width650px_cel margin_left_5px" id="modal_screening_respuesta" name="modal_screening_respuesta" rows="6" cols="80"></textarea>
                
                <br>                      
                    <div class="row contenedor3">
                      <div class="contenido3">
                          <button type="button" onclick="screeningAnteriorSiguiente(0)" class="rodri_button_aceptar_cel margin_left_cel4"><</button>
                          <input id="modal_screening_cantidad" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
                          <button type="button" onclick="screeningAnteriorSiguiente(1)" class="rodri_button_aceptar_cel">></button>
                          <button id="modal_screening_btn_nuevo" type="button" onclick="nuevoScreening()" class="rodri_button margin_left_20px margin_left_50px_solo_cel">NUEVO</button>
                          <button id="modal_screening_btn_guardar" type="button" onclick="guardarScreening()" class="rodri_button margin_left_20px">GUARDAR</button>
                      </div>
                    </div>             
              </div>
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



<script type="text/javascript">
    
  function mostrarScreening(){
    $.ajax({
       type:'POST',
       dataType:'JSON',
       url:'/get_paciente_seleccionado',
       data:{ _token: '{{csrf_token()}}'},
        success:function(data) {                  
          if(data.tope > 0){
            var fechaAux = data.screenings[0].fechaSolicitud.split("-");
            var fechaMostrar = fechaAux[2]+"/"+fechaAux[1]+"/"+fechaAux[0];             
            if(fechaMostrar.localeCompare("01/01/1900") == 0){
              fechaMostrar = "";               
            }
            document.getElementById("modal_screening_fecha_solicitud").value = fechaMostrar;
            document.getElementById("modal_screening_evaluacion").value = data.screenings[0].evaluacion;
            document.getElementById("modal_screening_respuesta").value = data.screenings[0].respuesta;
            document.getElementById("modal_screening_id").value = data.screenings[0].id;
            document.getElementById("modal_screening_numero").value = data.screenings[0].numero;

            var valor = data.tope+"/"+data.tope;
            document.getElementById("modal_screening_cantidad").value = valor;            
          } else {
            var date = new Date();            
            document.getElementById("modal_screening_fecha_solicitud").value = date.getDate() + "/" + (date.getMonth() +1) + "/" + date.getFullYear();
          }
          $('#modal_screening').modal();          
        }
    });
  }

  function screeningAnteriorSiguiente(opcion){
    var texto = document.getElementById("modal_screening_filtrar").value;
    var numero_actual = document.getElementById("modal_screening_numero").value;
    if(opcion == 1){
      var numero = parseInt(numero_actual) + 1;  
    } else{
      var numero = parseInt(numero_actual) - 1;  
    }
    //alert(numero);
    $.ajax({
       type:'POST',
       dataType:'JSON',
       url:'/anterior_siguiente_screening',
       data:{ numero:numero, texto:texto, _token: '{{csrf_token()}}'},
        success:function(data) {

          if(data.screenings != null){
            var fechaAux = data.screenings.fechaSolicitud.split("-");
            var fechaMostrar = fechaAux[2]+"/"+fechaAux[1]+"/"+fechaAux[0];
            if(fechaMostrar.localeCompare("01/01/1900") == 0){
              fechaMostrar = "";               
            }
            document.getElementById("modal_screening_fecha_solicitud").value = fechaMostrar;
            document.getElementById("modal_screening_evaluacion").value = data.screenings.evaluacion;
            document.getElementById("modal_screening_respuesta").value = data.screenings.respuesta;
            document.getElementById("modal_screening_id").value = data.screenings.id;
            var desde = data.numero + 1
            document.getElementById("modal_screening_numero").value = desde;

            var valor = desde+"/"+data.tope;
            document.getElementById("modal_screening_cantidad").value = valor;                       
          }
        }
    });
  }

  function guardarScreening(){
    var id = document.getElementById("modal_screening_id").value;  
    var fechaSolicitud = document.getElementById("modal_screening_fecha_solicitud").value;
    var evaluacion = document.getElementById("modal_screening_evaluacion").value;
    var respuesta = document.getElementById("modal_screening_respuesta").value;  
    $.ajax({
       type:'POST',
       dataType:'JSON',
       url:'/nuevo_screening',
       data:{ id:id, fechaSolicitud:fechaSolicitud, evaluacion:evaluacion, respuesta:respuesta,_token: '{{csrf_token()}}'},
        success:function(data) {                  
          if(data.screening != null){
            if(data.mostrarTopeNuevo == 1){
              var valor = data.tope+"/"+data.tope;
              document.getElementById("modal_screening_cantidad").value = valor; 
              document.getElementById("modal_screening_numero").value = data.tope;
              
            }
            
            mostrarSnackbar("Screening Guardado");           
          }
        }
    });
    
  }

  function filtrarScreening(){
    var texto = document.getElementById("modal_screening_filtrar").value;
      $.ajax({
       type:'POST',
       dataType:'JSON',
       url:'/filtrar_screening',
       data:{ texto:texto, _token: '{{csrf_token()}}'},
        success:function(data) {                  
          if(data.tope > 0){
            var fechaAux = data.screenings[0].fechaSolicitud.split("-");
            var fechaMostrar = fechaAux[2]+"/"+fechaAux[1]+"/"+fechaAux[0];
            if(fechaMostrar.localeCompare("01/01/1900") == 0){
              fechaMostrar = "";               
            }
            document.getElementById("modal_screening_fecha_solicitud").value = fechaMostrar;
            document.getElementById("modal_screening_evaluacion").value = data.screenings[0].evaluacion;
            document.getElementById("modal_screening_respuesta").value = data.screenings[0].respuesta;
            document.getElementById("modal_screening_id").value = data.screenings[0].id;
            document.getElementById("modal_screening_numero").value = data.tope;

            var valor = data.tope+"/"+data.tope;
            document.getElementById("modal_screening_cantidad").value = valor;            
          } else {
            borrarCampos();
          }
        }
    });
  }

  function nuevoScreening() {     
     document.getElementById("modal_screening_id").value = null;
     document.getElementById("modal_screening_evaluacion").value = "";
     document.getElementById("modal_screening_respuesta").value = "";  
     var date = new Date();            
     document.getElementById("modal_screening_fecha_solicitud").value = date.getDate() + "/" + (date.getMonth() +1) + "/" + date.getFullYear();
  }

  function borrarCampos(){
    document.getElementById("modal_screening_fecha_solicitud").value = "";
    document.getElementById("modal_screening_evaluacion").value = "";
    document.getElementById("modal_screening_respuesta").value = "";
    document.getElementById("modal_screening_id").value = "";
    document.getElementById("modal_screening_numero").value = "";
    document.getElementById("modal_screening_cantidad").value = "0/0";            
  }

</script>