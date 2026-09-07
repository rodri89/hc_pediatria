<div class="modal fade" id="modalResumen" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">      	
        <h4 class="modal-title"         
        id="modalTitleMensaje">Resumen</h4>
      </div>      
      
      <div class="modal-body">   
        <p>Paciente: </p>    	   
        
        <div id="resumen_seccion_alimentacion">      
          <label>Alimentacion</label>
          
        </div>

        <div id="resumen_seccion_escolaridad">      
          <label>Escolaridad</label>
          <textarea id="resumen_escolaridad" name="resumen_escolaridad" rows="5" cols="50"></textarea>
        </div>

        <div id="resumen_seccion_actividades_extra_escolares">      
          <label>Actividades Extra Escolares</label>
          <textarea id="resumen_actividades_extra_escolares" name="resumen_actividades_extra_escolares" rows="5" cols="50"></textarea>
        </div>

         <div id="resumen_seccion_pantallas">      
          <label>Pantallas</label>
          <textarea id="resumen_pantallas" name="resumen_pantallas" rows="5" cols="50"></textarea>
        </div>

        <div id="resumen_seccion_habitos">      
          <label>Habitos</label>
          <textarea id="resumen_habitos" name="resumen_habitos" rows="5" cols="50"></textarea>
        </div>

        <div id="resumen_seccion_menarca">      
          <label>Menarca</label>
          <input id="resumen_menarca" name="resumen_menarca"></input>
        </div>

        <div id="resumen_seccion_desarrollo_madurativo">      
          <label>Desarrollo Madurativo</label>
          
        </div>

        <div id="resumen_seccion_examen_fisico">      
          <label>Examen Fisico</label>
          
        </div>

        <div id="resumen_seccion_examenes_complementarios">      
          <label>Examenes Complementarios</label>
          
        </div>

        <div id="resumen_seccion_interconsulta">      
          <label>Interconsulta</label>
          
        </div>

        <div id="resumen_seccion_conductas">      
          <label>Conductas</label>
          <textarea id="resumen_conductas" name="resumen_conductas" rows="5" cols="50"></textarea>
        </div>

        <div id="resumen_seccion_observaciones">      
          <label>Observaciones</label>
          <textarea id="resumen_observaciones" name="resumen_observaciones" rows="5" cols="50"></textarea>
        </div>
      
      </div>
      
      <div class="modal-footer">
        <button type="button" 
           class="rodri_button_aceptar" 
           data-dismiss="modal">Aceptar</button>             
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  
  function cargarResumen(consulta_id, paciente_id){
    
    $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_resumen_consulta',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, _token: '{{csrf_token()}}'},
         success:function(data) { 
            if(data.response_conductas != null && data.response_conductas.descripcion.localeCompare('')!=0){
              $('#resumen_conductas').val(data.response_conductas.descripcion);
            } else {
              document.getElementById('resumen_seccion_conductas').hidden = true; 
            }

            if(data.response_observaciones != null && data.response_observaciones.descripcion.localeCompare('')!=0){
              $('#resumen_observaciones').val(data.response_observaciones.descripcion);
            } else {
              document.getElementById('resumen_seccion_observaciones').hidden = true; 
            }

            if(data.response_escolaridad != null && data.response_escolaridad.descripcion.localeCompare('')!=0){
              $('#resumen_escolaridad').val(data.response_conductas.descripcion); 
            } else {
              document.getElementById('resumen_seccion_escolaridad').hidden = true; 
            }

            if(data.response_actividades_extra_escolares != null && data.response_actividades_extra_escolares.descripcion.localeCompare('')!=0){
              $('#resumen_actividades_extra_escolares').val(data.response_actividades_extra_escolares.descripcion); 
            } else {
              document.getElementById('resumen_seccion_actividades_extra_escolares').hidden = true; 
            }

            if(data.response_pantallas != null && data.response_pantallas.descripcion.localeCompare('')!=0){
              $('#resumen_pantallas').val(data.response_pantallas.descripcion);
            } else {
              document.getElementById('resumen_seccion_pantallas').hidden = true;     
            }

            if(data.response_habitos != null && data.response_habitos.descripcion.localeCompare('')!=0){
              $('#resumen_habitos').val(data.response_habitos.descripcion);      
            } else {
              document.getElementById('resumen_seccion_habitos').hidden = true;              
            }

            if(data.response_menarca != null && data.response_menarca.descripcion.localeCompare('')!=0){
              $('#resumen_menarca').val(data.response_menarca.descripcion);   
            } else {
              document.getElementById('resumen_seccion_menarca').hidden = true;              
            }
            
            $('#modalResumen').modal();                         
          }

      }); 
  }

</script>