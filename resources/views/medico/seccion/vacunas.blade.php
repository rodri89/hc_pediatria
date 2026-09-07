@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual" id="seccion_vacunas_nueva_consulta">
      <div class="row margin_left_5px">
            <h4><b><a onclick="clickVacunas()" type="button">Vacunas</a></b></h4>
      </div>      
@else
<form id="seccion_vacunas_nueva_consulta">      
      <div class="row margin_left_5px">
            <p class="rodri_bold font_size_resumen"><a onclick="clickVacunas()" type="button"><u>Vacunas</u></a></>
      </div>
@endif
		
	<div id="seccion_vacunas" hidden>		
		
	<div  class="table-responsive" style="height:800px; overflow-y: scroll;">

    <table class="table table-condensed tabla_con_borde" id="tabla_pacientes" name="tabla_pacientes">
     <thead>
        <tr>
          <th class="editText input_width_50px rodri_th" scope="col"></th>
          <th class="editText input_width_50px rodri_th" scope="col">BCG</th>
          <th class="editText input_width_50px rodri_th" scope="col">Hepatitis B</th>                                 
          <th class="editText input_width_50px rodri_th" scope="col">Neumococo Conjugada</th>
          <th class="editText input_width_50px rodri_th" scope="col">Quintuple Pentavalente DTP-HB-Hib</th>
          <th class="editText input_width_50px rodri_th" scope="col">Polio IPV</th>                        
          <th class="editText input_width_50px rodri_th" scope="col">Polio OPV</th>                     
          <th class="editText input_width_50px rodri_th" scope="col">Rotavirus</th>                     
          <th class="editText input_width_50px rodri_th" scope="col">Meningococo</th>
          <th hidden class="editText input_width_50px rodri_th" scope="col">Gripe</th>
          <th class="editText input_width_50px rodri_th" scope="col">Hepatitis A</th>
          <th class="editText input_width_50px rodri_th" scope="col">Triple Viral</th>
          <th class="editText input_width_50px rodri_th" scope="col">Varicela</th>
          <th class="editText input_width_50px rodri_th" scope="col">Cuadruple o Quintuple Pentavalente DTP-Hib</th>
          <th class="editText input_width_50px rodri_th" scope="col">Triple Bacteriana Celular DTP</th>
          <th class="editText input_width_50px rodri_th" scope="col">Triple Bacteriana Acelular dTpa</th>
          <th class="editText input_width_50px rodri_th" scope="col">Virus Papiloma Humano VPH</th>
          <th class="editText input_width_50px rodri_th" scope="col">Doble Bacteriana dT</th>
          <th class="editText input_width_50px rodri_th" scope="col">Doble Viral SR o Triple Viral SRP</th>
          <th class="editText input_width_50px rodri_th" scope="col">Fiebre Amarilla FA</th>
          <th class="editText input_width_50px rodri_th" scope="col">Fiebre Hemorragica Argentina</th>
        </tr>
      </thead>
      <tbody id="pacientes-list" name="pacientes-list">
            <tr>
                  <td class="rodri_th">Recien Nacido</td>
                  <td>  <!-- BCG -->                                               
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_0', '1', '0')" type="checkbox" class="custom-control-input text-center" id="vacuna_0">
                              <label class="custom-control-label" for="vacuna_0"></label>
                        </div>                        
                  </td>
                  <td>  <!-- HEPATITIS B -->                       
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_1', '2', '0')"  type="checkbox" class="custom-control-input" id="vacuna_1" >
                              <label class="custom-control-label" for="vacuna_1"></label>
                        </div>                       
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">2 meses</td>
      		<td></td>
      		<td></td>
      		<td>      <!-- NEUMOCOCO -->                       
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_2', '3', '2')" type="checkbox" class="custom-control-input text-center" id="vacuna_2">
                              <label class="custom-control-label" for="vacuna_2"></label>
                        </div>
                  </td>
      		<td>      <!-- QUINTUPLE -->                       
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_3', '4', '2')" type="checkbox" class="custom-control-input text-center" id="vacuna_3">
                              <label class="custom-control-label" for="vacuna_3"></label>
                        </div>          
                  </td>
      		<td>      <!-- POLIO IPV -->                       
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_4', '5', '2')" type="checkbox" class="custom-control-input text-center" id="vacuna_4">
                              <label class="custom-control-label" for="vacuna_4"></label>
                        </div>          
                  </td>
      		<td></td>
      		<td>      <!-- ROTAVIRUS -->                       
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_5', '7', '2')" type="checkbox" class="custom-control-input text-center" id="vacuna_5">
                              <label class="custom-control-label" for="vacuna_5"></label>
                        </div>          
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">3 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- MENINGOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_6', '8', '3')" type="checkbox" class="custom-control-input text-center" id="vacuna_6">
                              <label class="custom-control-label" for="vacuna_6"></label>
                        </div>          
                  </td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">4 meses</td>
      		<td></td>
                  <td></td>
                  <td>  <!-- NEUMOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_7', '3', '4')" type="checkbox" class="custom-control-input text-center" id="vacuna_7">
                              <label class="custom-control-label" for="vacuna_7"></label>
                        </div>
                  </td>
                  <td>  <!-- QUINTUPLE -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_8', '4', '4')" type="checkbox" class="custom-control-input text-center" id="vacuna_8">
                              <label class="custom-control-label" for="vacuna_8"></label>
                        </div>          
                  </td>
                  <td>  <!-- POLIO IPV -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_9', '5', '4')" type="checkbox" class="custom-control-input text-center" id="vacuna_9">
                              <label class="custom-control-label" for="vacuna_9"></label>
                        </div>          
                  </td>
                  <td></td>
                  <td>  <!-- ROTAVIRUS -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_10', '7', '4')" type="checkbox" class="custom-control-input text-center" id="vacuna_10">
                              <label class="custom-control-label" for="vacuna_10"></label>
                        </div>          
                  </td>
                  <td></td>
                  <td hidden></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">5 meses</td>
      		<td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>  <!-- MENINGOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_11', '8', '5')" type="checkbox" class="custom-control-input text-center" id="vacuna_11">
                              <label class="custom-control-label" for="vacuna_11"></label>
                        </div>          
                  </td>
                  <td hidden></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">6 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>  <!-- QUINTUPLE -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_12', '4', '6')" type="checkbox" class="custom-control-input text-center" id="vacuna_12">
                              <label class="custom-control-label" for="vacuna_12"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td>      <!-- POLIO OPV -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_13', '6', '6')" type="checkbox" class="custom-control-input text-center" id="vacuna_13">
                              <label class="custom-control-label" for="vacuna_13"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">12 meses</td>
      		<td></td>
      		<td></td>
      		<td>  <!-- NEUMOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_14', '3', '12')" type="checkbox" class="custom-control-input text-center" id="vacuna_14">
                              <label class="custom-control-label" for="vacuna_14"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td>      <!-- HEPATITIS A -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_15', '10', '12')" type="checkbox" class="custom-control-input text-center" id="vacuna_15">
                              <label class="custom-control-label" for="vacuna_15"></label>
                        </div>            
                  </td>
      		<td>      <!-- TRIPLE VIRAL -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_16', '11', '12')" type="checkbox" class="custom-control-input text-center" id="vacuna_16">
                              <label class="custom-control-label" for="vacuna_16"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">15 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- MENINGOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_17', '8', '15')" type="checkbox" class="custom-control-input text-center" id="vacuna_17">
                              <label class="custom-control-label" for="vacuna_17"></label>
                        </div>            
                  </td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- VARICELA -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_18', '12', '15')" type="checkbox" class="custom-control-input text-center" id="vacuna_18">
                              <label class="custom-control-label" for="vacuna_18"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">15-18 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- POLIO OPV -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_19', '6', '16')" type="checkbox" class="custom-control-input text-center" id="vacuna_19">
                              <label class="custom-control-label" for="vacuna_19"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- CUADRUPLE -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_20', '13', '16')" type="checkbox" class="custom-control-input text-center" id="vacuna_20">
                              <label class="custom-control-label" for="vacuna_20"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">18 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- FIEBRE AMARILLA -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_21', '19', '18')" type="checkbox" class="custom-control-input text-center" id="vacuna_21">
                              <label class="custom-control-label" for="vacuna_21"></label>
                        </div>            
                  </td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">24 meses</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">5-6 años</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>  <!-- POLIO OPV -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_22', '6', '60')" type="checkbox" class="custom-control-input text-center" id="vacuna_22">
                              <label class="custom-control-label" for="vacuna_22"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td>      <!-- TRIPLE VIRAL -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_23', '11', '60')" type="checkbox" class="custom-control-input text-center" id="vacuna_23">
                              <label class="custom-control-label" for="vacuna_23"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td>      <!-- TRIPLE BACTERIANA DTP-->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_24', '14', '60')" type="checkbox" class="custom-control-input text-center" id="vacuna_24">
                              <label class="custom-control-label" for="vacuna_24"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">11 años</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- MENINGOCOCO -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_25', '8', '132')" type="checkbox" class="custom-control-input text-center" id="vacuna_25">
                              <label class="custom-control-label" for="vacuna_25"></label>
                        </div>            
                  </td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- TRIPLE BACTERIANA ACELULAR -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_26', '15', '132')" type="checkbox" class="custom-control-input text-center" id="vacuna_26">
                              <label class="custom-control-label" for="vacuna_26"></label>
                        </div>            
                  </td>
      		<td>      <!-- VIRUS PAPILOMA -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_27', '16', '132')" type="checkbox" class="custom-control-input text-center" id="vacuna_27">
                              <label class="custom-control-label" for="vacuna_27"></label>
                        </div>            
                  </td>
      		<td></td>
      		<td></td>
      		<td>      <!-- FIEBRE AMARILLA -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_28', '19', '132')" type="checkbox" class="custom-control-input text-center" id="vacuna_28">
                              <label class="custom-control-label" for="vacuna_28"></label>
                        </div>            
                  </td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">15 años</td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td hidden></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td></td>
      		<td>      <!-- FIEBRE HEMORRAGICA -->
                        <div class="custom-control custom-checkbox">
                              <input onclick="clickVacunaCheck('vacuna_29', '20', '180')" type="checkbox" class="custom-control-input text-center" id="vacuna_29">
                              <label class="custom-control-label" for="vacuna_29"></label>
                        </div>            
                  </td>
      	</tr>
      </tbody>
	</table>
	
	</div>
	<br>
      <div class="row">
      	<div class="row margin_left_5px">		
      		<label><b>Otras:</b></label>
      	</div>
      	<div class="row margin_left_10px">		
      		<textarea class="form-control width450px_cel margin_left_5px" id="vacunas_otro" name="vacunas_otro" rows="5" cols="60"></textarea>		
      	</div>
      <div class="width350px_cel">
            <div class="row margin_left_30px">         
                  <label><b>Antigripal:</b></label>
                  <a id="vacunas_antigripal_utlima" type="button" onclick="verAntigripal()">19/07/2020</a>
            </div>
            <div class="row margin_left_30px">         
                 <button id="vacunaAntigripalMostrarId" type="button" onclick="vacunaAntigripalMostrar()" class="rodri_button_aceptar_si"><i class="fas fa-search"></i></button>
                 <button id="agregarAntigripalModalId" type="button" onclick="agregarAntigripalModal()" class="rodri_button_aceptar_si margin_left_5px">+</button>
            </div>
      </div>
      </div>
		<br>
		<div class="row contenedor3">
	    	<button id="guardarOtrasVacunasId" type="button" onclick="guardarOtrasVacunas('vacunas_otro', '21', '0')" class="rodri_button contenido3">GUARDAR</button>
		</div>
		<br>
	</div>
</form>

 <div class="modal fade" id="agregarVacunaAntigripalModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">          
        <h4 class="modal-title"         
        id="modalTitleMensaje">Agregar Vacuna Antigripal</h4>
      </div>      
      
      <div class="modal-body ">   
            <div class="modal-body">
                  <div class="row margin_left_3px">                        
                        <div class="row">
                        <label class="margin_top_5px margin_left_10px"><b>Fecha</b></label>
                             <div class="col-md-2">
                              <input type="text" maxlength="2" class="form-control" id="vacuna_antigripal_dia" name="vacuna_antigripal_dia"  placeholder="dd" required />
                            </div>
                             <label for="text" class="col-sm-0 control-label">/</label>
                            <div class="col-md-2">
                              <input type="text" maxlength="2" class="form-control" id="vacuna_antigripal_mes" name="vacuna_antigripal_mes"  placeholder="mm" required />
                            </div>
                            <label for="text" class="col-sm-0 control-label">/</label>
                            <div class="col-md-3">   
                              <input type="text" maxlength="4" class="form-control" id="vacuna_antigripal_anio" name="vacuna_antigripal_dia"  placeholder="YYYY" required />
                            </div>
                         </div>
                  </div>
                  <div class="row margin_left_3px margin_top_10px">
                        <label class="margin_top_5px"><b>Dosis</b></label>
                        <input id="vacuna_antigripal_dosis" class="form-control input_width_150px margin_left_5px" type="numeric"></input>
                        <small>En caso de que sea la primer aplicacion que lleva dos dosis.</small>
                  </div>
                  
            </div>
      <div class="modal-footer">
        <button id="modal_vacuna_antigripal_cancelar" type="button" 
           class="rodri_button_cancelar"            
           data-dismiss="modal">Cancelar</button>           
        <button id="modal_vacuna_antigripal_agregar" type="button" 
           class="rodri_button_aceptar" 
           onclick="vacunaAntigripalAgregar()" 
           data-dismiss="modal">Agregar</button>           
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="mostrarVacunaAntigripalModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">          
        <h4 class="modal-title"         
        id="modalTitleMensaje">Vacuna Antigripal</h4>
      </div>      
      
      <div class="modal-body ">   
            <div class="modal-body">
                  <div id="seccion_listado_vacuna_antigripal" class="table-responsive" style="height:300px;">
                      <table class="table table-condensed tabla_con_borde" id="tabla_vacuna_antigripal" name="tabla_vacuna_antigripal">
                       <thead>
                          <tr>
                            <th class="editText rodri_th letra_size_1rem" scope="col">Fecha</th>
                            <th class="editText rodri_th letra_size_1rem" scope="col">Dosis</th>                            
                          </tr>
                        </thead>
                        <tbody id="vacunas-list" name="vacunas-list">                          
                        </tbody>
                    </table>
                    </div>  
            </div>
      <div class="modal-footer">
        <button id="modal_vacuna_antigripal_volver" type="button" 
           class="rodri_button_volver"            
           data-dismiss="modal">Cerrar</button>                       
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
	
      function guardarOtrasVacunas(vacunaViewId, vacuna_id, edadMeses){
            var consulta = document.getElementById("consulta_id").value;
            var paciente = document.getElementById("paciente_id").value;            
            var otra = document.getElementById(vacunaViewId).value;                         
            //alert(estado);
            $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/guardar_vacuna_otra_paciente',
                 data:{consulta:consulta, paciente:paciente, vacuna_id:vacuna_id, edadMeses:edadMeses, otra:otra, vacunaViewId:vacunaViewId, _token: '{{csrf_token()}}'},
                  success:function(data) {  
                        mostrarSnackbar("GUARDADO");   
                  /*alert(data.request);             
                        if(data.response == 1){                              
                              alert("Guardado con exito vacuna");
                        } else {
                              alert("Fallo al guardar");
                        }*/
                  }
              });
      }

	function clickVacunas() {
		seccionPrincipal = document.getElementById("seccion_vacunas");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
                  cargarVacunas();
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

      function clickVacunaCheck(vacunaViewId, vacuna_id, edadMeses) {
            var consulta = document.getElementById("consulta_id").value;
            var paciente = document.getElementById("paciente_id").value;            
            var id = document.getElementById(vacunaViewId).checked;             
            if(id)
                  estado = 1;      
            else
                  estado = 0;
            //alert(estado);
            $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/guardar_vacuna_paciente',
                 data:{consulta:consulta, paciente:paciente, vacuna_id:vacuna_id, edadMeses:edadMeses, estado:estado, vacunaViewId:vacunaViewId, _token: '{{csrf_token()}}'},
                  success:function(data) {     
                        if(data.response == 1){
                              //alert("Guardado con exito actividades extra escolares");
                        } else {
                              mostrarSnackbar("VACUNAS FALLO");
                              //alert("Fallo al guardar");
                        }
                  }
              });
      }

      function cargarVacunas(){
            var paciente = document.getElementById("paciente_id").value;       
            $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/cargar_vacuna_paciente',
                 data:{paciente:paciente, _token: '{{csrf_token()}}'},
                  success:function(data) {                                    
                        for(i = 0; i<data.vacunaPaciente.length; i++) {                              
                              var check = document.getElementById(data.vacunaPaciente[i].vacuna_view_id);
                              if(check != null){
                                    if(data.vacunaPaciente[i].vacuna_id == 21){
                                          document.getElementById(data.vacunaPaciente[i].vacuna_view_id).value = data.vacunaPaciente[i].nombre;
                                    } else {
                                          if(data.vacunaPaciente[i].estado == 1)
                                                check.checked = true;
                                          else
                                                check.checked = false;
                                    }
                              }
                        }
                  }
              });
      }

      function agregarAntigripalModal(){
            $('#vacuna_antigripal_dia').val("");  
            $('#vacuna_antigripal_mes').val("");  
            $('#vacuna_antigripal_anio').val("");  
            $('#vacuna_antigripal_dosis').val("");              
            $('#agregarVacunaAntigripalModal').modal();  
      }

      function vacunaAntigripalAgregar(){
            var paciente = document.getElementById("paciente_id").value;
            var consulta = document.getElementById("consulta_id").value;       
            var fecha_dia = document.getElementById("vacuna_antigripal_dia").value;
            var fecha_mes = document.getElementById("vacuna_antigripal_mes").value;
            var fecha_anio = document.getElementById("vacuna_antigripal_anio").value;
            var _dosis = document.getElementById("vacuna_antigripal_dosis");
            var dosis = 0;
            if(_dosis != null && _dosis.value != 0)
                  dosis = _dosis.value;
            var fecha = fecha_anio+"-"+fecha_mes+"-"+fecha_dia;
            $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/agregar_vacuna_antigripal_paciente',
                 data:{paciente:paciente,fecha:fecha, dosis:dosis, consulta:consulta, _token: '{{csrf_token()}}'},
                  success:function(data) {                                    
                        mostrarSnackbar("Vacuna Agregada");
                  }
              });
      }

      function vacunaAntigripalMostrar(){
            var paciente = document.getElementById("paciente_id").value;                       
            $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url:'/mostrar_vacuna_antigripal_paciente',
                 data:{paciente:paciente, _token: '{{csrf_token()}}'},
                  success:function(data) { 
                        $("#tabla_vacuna_antigripal").find("tr:gt(0)").remove();                                  
                        for (i = 0; i < data.response_data.length; i++){
                              var fecha_aux = data.response_data[i].fecha.split("-");
                              var fecha = fecha_aux[2]+"/"+fecha_aux[1]+"/"+fecha_aux[0];
                              var dosis = data.response_data[i].dosis;
                              
                              if(dosis==0){
                                    var vacuna = "<tr><td class='editText'>"+fecha+"</td><td></td></tr>";
                              } else {
                                    var vacuna = "<tr><td class='editText'>"+fecha+"</td><td>"+dosis+"</td></tr>";
                              }

                              $('#vacunas-list').append(vacuna); 
                        }
                        $('#mostrarVacunaAntigripalModal').modal(); 
                  }
              });
      }   

      function cargarVacunaConsulta(tipo_consulta){
            if(tipo_consulta == 1){
                  document.getElementById("seccion_vacunas_nueva_consulta").hidden = false;
                  document.getElementById("agregarAntigripalModalId").hidden = true;
                  document.getElementById("guardarOtrasVacunasId").hidden = true;
                  for(i = 0; i<30; i++){
                        var vacuna = "vacuna_"+i;
                        document.getElementById(vacuna).disabled = true;
                  }
            } else {
                  document.getElementById("seccion_vacunas_nueva_consulta").hidden = true;
            }
      }

</script>