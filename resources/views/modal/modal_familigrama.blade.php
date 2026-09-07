<div id="modal_familigrama_cargar_fotos" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-m">
    <div class="modal-content">
      <div class="modal-body"> 
        <div class="modal-header">       
          <h4 class="modal-title"         
            id="modalTitleFamiligrama">Cargar Fotos</h4><p id="consulta_numero" class="margin_right_5px cel_size_input"></p>
        </div> 
        <div id="seccion_resumen margin_left_20px">
          <form method="post" action="{{ route('guardarfamiligrama') }}" enctype="multipart/form-data">
            <input type="hidden" id="es_nueva_consulta_cargar_fotos_fg" name="es_nueva_consulta_cargar_fotos_fg" value="3" />
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
            <br>
          </form>
        </div>
        <div class="modal-footer">
            <button type="button" 
             class="rodri_button_cancelar"             
             data-dismiss="modal">Cancelar</button>             
        </div>
      </div>
    </div>
  </div>
</div>

