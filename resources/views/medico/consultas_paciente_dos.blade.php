
@extends('plantillas/plantilla_medico')

@section('title_header','Consultas Paciente')

@section('contenedor')
<input hidden id="paciente_nombre" value="{{$paciente->nombre}}">
<input hidden id="paciente_apellido" value="{{$paciente->apellido}}">
<input hidden id="consulta_paciente_id" value="{{$paciente->id}}">

<div class="row">
  <div class="table-responsive">
    <table class="table table-condensed tabla_con_borde" id="tabla_pacientes" name="tabla_pacientes">
     <thead>
        <tr>
          <th class="editText rodri_th letra_size_1rem" scope="col">#</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Tipo</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Fecha</th>
          <th class="editText rodri_th letra_size_1rem" scope="col">Ver</th>           
        </tr>
      </thead>
      <tbody id="pacientes-list" name="pacientes-list">
        <?php $cont = $listadoConsultas->count(); ?>
        @foreach($listadoConsultas as $lc)
        <tr>
          <th scope="row" class="editText letra_size_1rem">{{$cont--}}</th>
          <td class="letra_size_1rem">{{$lc->tipo_consulta}}</td>
          <td class="letra_size_1rem">{{$lc->created_at}}</td>          
          
          <td class='letra_size_1rem'><button class='rodri_button_aceptar_si' onclick='verResumen("{{$lc->id}}")' data-toggle='modal' data-target='.bd-example-modal-xl'>></button></td>
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>

</div>

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body"> 
         <div class="modal-header">       
          <h4 class="modal-title"         
            id="modalTitleMensaje">Resumen</h4>
          </div> 

          <div id="seccion_resumen"> 
           
          </div>

          <div id="seccion_examenes_complementarios" class="row margin_left_5px">
            <div class="col-md-6">    
              <p class="rodri_bold font_size_resumen">Examenes Complementarios</p>
              <div class="row margin_left_5px margin_top_5px">      
                <label class="margin_top_5px input_width_80px" for="examenes_complementarios_solicito">Solicito:</label>
                 <input type="text" class="form-control input_width_350px" id="examenes_complementarios_solicito" name="examenes_complementarios_solicito"  placeholder=""/>
              </div>
              <div class="row margin_left_5px">
               <label>Resultado:</label>     
              </div>
              <textarea class="width200px margin_left_5px" id="examenes_complementarios_respuesta" name="examenes_complementarios_respuesta" rows="4" cols="60"></textarea>     
            </div>  
            <div class="col-md-6">              
              <div id="seccion_examen_complementario_fotos">
                <input type="hidden" id="foto_ex_complementario_id" name="foto_ex_complementario_id" />
                <input type="hidden" id="ex_comp_cantidad_fotos" name="ex_comp_cantidad_fotos" />              
               </div>
               <br>
               <div id="seccion_examen_complementario_ver_fotos" class="margin_top_12px">
                <a type="button" id="examenes_complementarios_foto_1" class="card-img-top img_little botonImage" alt="">
                  <img id="examenes_complementarios_foto" src="" class="card-img-top img_little botonImage">
                </a>
                <div class="row margin_top_12px margin_left_60px">          
                  <button type="button" onclick="examenesComplementariosAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
                  <input id="examenes_complementarios_actual_cantidad_fotos" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
                  <button type="button" onclick="examenesComplementariosAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>
                </div>    
              </div>
            </div>  
          </div>  
            <br>
            <div class="row contenedor3">
              <div class="contenido3">
                <button type="button" onclick="examenesComplementariosAnteriorSiguiente(0)" class="rodri_button_aceptar"><</button>
                  <input id="examenes_complementarios_actual_cantidad" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
                <button type="button" onclick="examenesComplementariosAnteriorSiguiente(1)" class="rodri_button_aceptar">></button>
              </div>
            </div>    
            <br>
          </div>  

          <div id="seccion_interconsulta" class="row margin_left_20px">
            <input hidden id="interconsulta_numero" name="interconsulta_numero"/>
            <div class="margin_left_5px">
            <p class="rodri_bold font_size_resumen">Interconsultas</p>       
              <div class="row margin_left_5px margin_top_5px">      
                <label class="margin_top_5px input_width_110px" for="interconsulta_especialista">Especialista:</label>
                <input type="text" class="form-control input_width_350px" id="interconsulta_especialista" name="interconsulta_especialista"  placeholder=""/>
              </div>
              <div class="row margin_left_5px margin_top_5px">
                <label class="margin_top_5px input_width_110px" for="interconsulta_solicito">Solicito:</label>
                <input type="text" class="form-control input_width_350px" id="interconsulta_solicito" name="interconsulta_solicito"  placeholder=""/>
              </div>
              <div class="row margin_left_5px margin_top_5px">
                <label class="margin_top_5px input_width_110px" for="interconsulta_fecha_solicito">Fecha Solicitud:</label>
                <input type="text" class="form-control input_width_350px" id="interconsulta_fecha_solicito" name="interconsulta_fecha_solicito"  placeholder=""/>
              </div>
              <div class="row margin_left_5px">
                <label>Respuesta:</label>     
              </div>
              <textarea class="form-control width650px margin_left_5px" id="interconsulta_respuesta" name="interconsulta_respuesta" rows="4" cols="60"></textarea>      
              
            <br>
            <div class="row contenedor3">
              <div class="contenido3">
                <button type="button" onclick="interconsultaAnteriorSiguiente(0)" class="rodri_button_aceptar"><</button>
                  <input id="interconsulta_actual_cantidad" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
                <button type="button" onclick="interconsultaAnteriorSiguiente(1)" class="rodri_button_aceptar">></button>                    
              </div>
            </div>
            <br>
          </div>
          </div>

          <div id="seccion_resumen_dos"> 
           
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

    function verResumen(consulta_id){
      var paciente_id = document.getElementById("consulta_paciente_id").value;    
      cargarResumen(consulta_id, paciente_id);
    }

    function cargarResumen(consulta_id, paciente_id){            
    $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_resumen_consulta',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, _token: '{{csrf_token()}}'},
         success:function(data) {                       
            var seccion_resumen = document.getElementById('seccion_resumen');
            var br = document.createElement("BR");
            var myNode = document.getElementById("seccion_resumen");
            while (myNode.firstChild) {
                   myNode.removeChild(myNode.firstChild);
            }            
            if(data.modulo_vacunas == 2) {                            
              if(data.vacunas_response != null && data.vacunas_response.descripcion.localeCompare('')!=0){                              
                  var br1 = document.createElement("BR");
                  seccion_resumen.appendChild(br1);           
                  addTituloDescripcion("Vacunas", data.vacunas_response.descripcion, seccion_resumen);                
              }
            }

            if(data.response_alimentacion != null && data.alimentacion_mostrar == 1) {
                var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Alimentación";
                seccion_resumen.appendChild(title);
                
                if(data.response_alimentacion.pecho == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Pecho", data.response_alimentacion.pecho_detalle, seccion_resumen);                                  
                }
                if(data.response_alimentacion.leche_maternizada == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Lecha Maternizada", data.response_alimentacion.leche_maternizada_detalle, seccion_resumen);                                  
                }
                if(data.response_alimentacion.leche_vaca == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Lecha Vaca", data.response_alimentacion.leche_vaca_detalle, seccion_resumen);                  
                }
                if(data.response_alimentacion.dieta_tipo.localeCompare('')!=0 || data.response_alimentacion.dieta_comidas.localeCompare('')!=0){
                    var ptitle_a4 = document.createElement("P");
                    ptitle_a4.setAttribute('class', 'rodri_bold margin_left_10px');                                   
                    ptitle_a4.innerHTML = "Dieta: ";
                    seccion_resumen.appendChild(ptitle_a4);
                    seccion_resumen.appendChild(br);                                  
                  if(data.response_alimentacion.dieta_tipo.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Tipo", data.response_alimentacion.dieta_tipo, seccion_resumen);                    
                  }
                   if(data.response_alimentacion.dieta_comidas.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Comidas", data.response_alimentacion.dieta_comidas, seccion_resumen);                    
                  }
                  if(data.response_alimentacion.hierro == 1){
                    bajarRenglon(seccion_resumen);            
                    addText("Hierro", data.response_alimentacion.hierro_dosis, seccion_resumen);                    
                  }                  
                  if(data.response_alimentacion.vitamina == 1){
                    bajarRenglon(seccion_resumen);            
                    addText("Vitaminas", data.response_alimentacion.vitamina_dosis, seccion_resumen);
                  }                  
                  if(data.response_alimentacion.catarsis.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Catarsis", data.response_alimentacion.catarsis, seccion_resumen);
                  }
                  if(data.response_alimentacion.somnia.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Somnia", data.response_alimentacion.somnia, seccion_resumen);
                  }                  
                }
                seccion_resumen.appendChild(br);        
            }

            if(data.response_escolaridad != null && data.response_escolaridad.descripcion.localeCompare('')!=0){                              
                var br1 = document.createElement("BR");
                seccion_resumen.appendChild(br1);           
                addTituloDescripcion("Escolaridad", data.response_escolaridad.descripcion, seccion_resumen);                
            }
            
            if(data.response_actividades_extra_escolares != null && data.response_actividades_extra_escolares.descripcion.localeCompare('')!=0){
                addTituloDescripcion("Actividades Extra Escolares", data.response_actividades_extra_escolares.descripcion, seccion_resumen);
            } 

            if(data.response_pantallas != null && data.response_pantallas.descripcion.localeCompare('')!=0){
                addTituloDescripcion("Pantallas", data.response_pantallas.descripcion, seccion_resumen);                      
            } 

            if(data.response_habitos != null && data.response_habitos.descripcion.localeCompare('')!=0){
                addTituloDescripcion("Hábitos", data.response_habitos.descripcion, seccion_resumen);                    
            } 

            if(data.response_menarca != null && data.response_menarca.descripcion.localeCompare('')!=0){
                addTituloDescripcion("Menarca", data.response_menarca.descripcion, seccion_resumen);                 
            } 

            if(data.response_desarrollo_madurativo != null) {              
                var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Desarrollo Madurativo";                
                seccion_resumen.appendChild(title);
                var observacion = '';
                for(i=0; i<data.response_desarrollo_madurativo.length; i++){
                  if(data.response_desarrollo_madurativo[i].checked == 1){
                    addText(data.response_desarrollo_madurativo[i].descripcion, "SI", seccion_resumen);    
                  }
                }
                
                if(data.response_desarrollo_madurativo_observacion != null){
                  bajarRenglon(seccion_resumen);
                  addText("Observacion", data.response_desarrollo_madurativo_observacion.observacion, seccion_resumen);
                }
                bajarRenglon(seccion_resumen);                
                var brds = document.createElement("BR");
                seccion_resumen.appendChild(brds);
            } else {
                bajarRenglon(seccion_resumen);
                var brds = document.createElement("BR");
                seccion_resumen.appendChild(brds);
            }            

            if(data.response_examen_fisicos != null) {              
                var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Exámen Físico";                
                seccion_resumen.appendChild(title);                               
                
                if(data.response_examen_fisicos.peso != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("Peso", data.response_examen_fisicos.peso, seccion_resumen);                                  
                  if(data.response_examen_fisicos.peso_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.peso_percentil, seccion_resumen);
                  }
                }

                if(data.response_examen_fisicos.talla != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("Talla", data.response_examen_fisicos.talla, seccion_resumen);                                  
                  if(data.response_examen_fisicos.talla_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.talla_percentil, seccion_resumen);
                  }
                }

                if(data.response_examen_fisicos.pc != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("PC", data.response_examen_fisicos.pc, seccion_resumen);                                  
                  if(data.response_examen_fisicos.pc_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.pc_percentil, seccion_resumen);                                                      
                  }
                }            

                if(data.response_examen_fisicos.ipd != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("IPD", data.response_examen_fisicos.ipd, seccion_resumen);                                                    
                }

                if(data.response_examen_fisicos.ta != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("TA", data.response_examen_fisicos.ta, seccion_resumen);                                                    
                }

                if(data.response_examen_fisicos.imc != null){      
                  bajarRenglon(seccion_resumen);            
                  addText("IMC", data.response_examen_fisicos.imc, seccion_resumen);                                  
                  if(data.response_examen_fisicos.imc_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.imc_percentil, seccion_resumen);                                                      
                  }
                }

                if(data.response_examen_fisicos.nota != null){      
                  bajarRenglon(seccion_resumen);            
                  addText2("Nota", data.response_examen_fisicos.nota, seccion_resumen);                                                    
                }
                var bref = document.createElement("BR");
                seccion_resumen.appendChild(bref);
            } else {
              var bref = document.createElement("BR");
                seccion_resumen.appendChild(bref);
            }    

            /*
            if(data.response_examenes_complementarios != null && data.response_examenes_complementarios.length > 0) {
              var title = document.createElement("P");                
              title.setAttribute('class', 'rodri_bold font_size_resumen');
              title.innerHTML = "Examenes Complementarios";                
              seccion_resumen.appendChild(title);                               

              var tope = data.response_examenes_complementarios.length; 
              for(i=0; i<tope; i++) {
                addText("Solicito", data.response_examenes_complementarios[i].solicito, seccion_resumen);
                bajarRenglon(seccion_resumen);
                addText("Respuesta", data.response_examenes_complementarios[i].respuesta, seccion_resumen);
                //bajarRenglon(seccion_resumen);
                var newBr = document.createElement("BR");          
                seccion_resumen.appendChild(newBr);          
              }            

            } else {
                var br2 = document.createElement("BR");
                seccion_resumen.appendChild(br2);
            }                    
            */

            if(data.response_interconsultas != null && data.response_interconsultas.length > 0) {
              var title = document.createElement("P");                
              title.setAttribute('class', 'rodri_bold font_size_resumen');
              title.innerHTML = "Interconsultas";                
              seccion_resumen.appendChild(title);                               

              var tope = data.response_interconsultas.length; 
              for(i=0; i<tope; i++) {
                addText("Especialista", data.response_interconsultas[i].especialista, seccion_resumen);
                bajarRenglon(seccion_resumen);
                addText("Solicito", data.response_interconsultas[i].solicito, seccion_resumen);
                bajarRenglon(seccion_resumen);
                addText("Respuesta", data.response_interconsultas[i].respuesta, seccion_resumen);
                bajarRenglon(seccion_resumen);
                var newBr = document.createElement("BR");          
                seccion_resumen.appendChild(newBr);          
              }                
            } else {
                var br2 = document.createElement("BR");
                seccion_resumen.appendChild(br2);
            }

            if(data.response_conductas != null && data.response_conductas.descripcion.localeCompare('')!=0) {                
                addTituloDescripcion("Conductas", data.response_conductas.descripcion, seccion_resumen);              
            }

            if(data.response_observaciones != null && data.response_observaciones.descripcion.localeCompare('')!=0) {                
                addTituloDescripcion("Observaciones", data.response_observaciones.descripcion, seccion_resumen);              
            }       

            if(data.response_notas != null && data.response_notas.descripcion.localeCompare('')!=0) {                
                addTituloDescripcion("Notas", data.response_notas.descripcion, seccion_resumen);              
            }         
            //$('#modalResumen').modal();                         
          }

      }); 
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

</script>

@endsection