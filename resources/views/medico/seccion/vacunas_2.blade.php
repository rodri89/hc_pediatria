<form class="card background_panel_consulta_actual">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickVacunas()" type="button">Vacunas</a></b></h4>
	</div>	
	<div id="seccion_vacunas" hidden>		
		
	<div class="table-responsive" style="height:800px; overflow-y: scroll;">

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
          <th class="editText input_width_50px rodri_th" scope="col">Gripe</th>
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
      		<td>  
      			<div class="custom-control custom-checkbox">
                	<input type="checkbox" class="custom-control-input text-center" id="recien_nacido_bcg">
                	<label class="custom-control-label" for="recien_nacido_bcg"></label>
              	</div>
          	</td>
      		<td>  <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="recien_nacido_hepatitis_b" >
                <label class="custom-control-label" for="recien_nacido_hepatitis_b"></label>
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
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">4 meses</td>
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
      		<td></td>
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">6 meses</td>
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
      		<td></td>
      	</tr>
      	<tr>
      		<td class="rodri_th">5-6 años</td>
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
      		<td class="rodri_th">11 años</td>
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
      		<td class="rodri_th">15 años</td>
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
      		<td class="rodri_th">Adultos</td>
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
      		<td class="rodri_th">Embarazadas</td>
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
      		<td class="rodri_th">Puerperio</td>
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
      		<td class="rodri_th">Personal Salud</td>
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
      </tbody>
	</table>
	
	</div>
	<br>
	<div class="row margin_left_5px">		
		<label><b>Otras:</b></label>
	</div>
	<div class="row margin_left_5px">		
		<textarea class="width200px" id="vacunas_otro" name="vacunas_otro" rows="5" cols="60"></textarea>		
	</div>
		<br>
		<div class="row contenedor3">
	    	<button onclick="guardarDatos()" class="rodri_button contenido3">GUARDAR</button>
		</div>
		<br>
	</div>

</form>

<script type="text/javascript">
	
	function clickVacunas(){
		seccionPrincipal = document.getElementById("seccion_vacunas");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}
</script>