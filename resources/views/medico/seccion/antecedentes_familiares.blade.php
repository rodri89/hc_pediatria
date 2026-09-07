<form class="card background_panel">
<div class="row">
	 <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('HTA')" type="checkbox" class="custom-control-input" id="ant_familiares_hta">
	    <label class="custom-control-label" for="ant_familiares_hta">HTA</label>
	  </div>

	   <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('DBT')" type="checkbox" class="custom-control-input" id="ant_familiares_dbt">
	    <label class="custom-control-label" for="ant_familiares_dbt">DBT</label>
	  </div>

	   <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ASMA')" type="checkbox" class="custom-control-input" id="ant_familiares_asma">
	    <label class="custom-control-label" for="ant_familiares_asma">ASMA</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ALERGIA')" type="checkbox" class="custom-control-input" id="ant_familiares_alergia">
	    <label class="custom-control-label" for="ant_familiares_alergia">ALERGIA</label>
	  </div>

	   <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF C-V')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_cv">
	    <label class="custom-control-label" for="ant_familiares_enf_cv">ENF C-V</label>
	  </div>

	</div>

	<div class="row">

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('MUERTE SUBITA')" type="checkbox" class="custom-control-input" id="ant_familiares_muerte_subita">
	    <label class="custom-control-label" for="ant_familiares_muerte_subita">MUERTE SUBITA</label>	   
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF CELIACA')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_celiaca">
	    <label class="custom-control-label" for="ant_familiares_enf_celiaca">ENF CELÍACA</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF TIROIDEAS')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_tiroideas">
	    <label class="custom-control-label" for="ant_familiares_enf_tiroideas">ENF TIROIDEAS</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF NEUROLOGICAS')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_neurologicas">
	    <label class="custom-control-label" for="ant_familiares_enf_neurologicas">ENF NEUROLÓGICAS</label>
	  </div>

	</div>

	<div class="row">

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('CONVULSION FEBRIL')" type="checkbox" class="custom-control-input" id="ant_familiares_convulsion_febril">
	    <label class="custom-control-label" for="ant_familiares_convulsion_febril">CONVULSION FEBRIL</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF PSIQUIATRICA')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_psiquiatrica">
	    <label class="custom-control-label" for="ant_familiares_enf_psiquiatrica">ENF PSIQUIÁTRICA</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('ENF O-H')" type="checkbox" class="custom-control-input" id="ant_familiares_enf_oh">
	    <label class="custom-control-label" for="ant_familiares_enf_oh">ENF O-H</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('TABAQUISMO')" type="checkbox" class="custom-control-input" id="ant_familiares_tabaquismo">
	    <label class="custom-control-label" for="ant_familiares_tabaquismo">TABAQUISMO</label>
	  </div>

	  <div class="custom-control custom-checkbox margin_left_20px">
	    <input onclick="agregarDetalle('OTRO')" type="checkbox" class="custom-control-input" id="ant_familiares_otro">
	    <label class="custom-control-label" for="ant_familiares_otro">OTRO</label>
	  </div>

	</div>
	<div class="row margin_left_5px">
		<div id="seccion_antecedentes_familiares_detalles">
		</div>
	</div>
	<br>
	<div class="row margin_left_5px">		
		<label>Nota:</label>
	</div>
	<div class="row margin_left_5px">		
		<textarea class="width450px_cel form-control" id="antecedentes_familiares_nota" name="antecedentes_familiares_nota" rows="5" cols="60"></textarea>		
	</div>
</form>

<script type="text/javascript">	

	function agregarDetalle(opcionNombre){		
		if(document.getElementById("label-"+opcionNombre)){
			document.getElementById("label-"+opcionNombre).remove();
			document.getElementById("detalle-"+opcionNombre).remove();
		} else {
			var seccion = document.getElementById("seccion_antecedentes_familiares_detalles");
			
			var label = document.createElement("LABEL");		
	      	label.id = 'label-'+opcionNombre;
	      	label.name = 'label-'+opcionNombre;
	      	label.innerHTML = opcionNombre+":";
	      	label.setAttribute('class', 'label_rodri');                

			var input = document.createElement("TEXTAREA");
			input.type = 'text';
	      	input.id = 'detalle-'+opcionNombre;
	      	input.name = 'detalle-'+opcionNombre;
	      	input.cols = "60";
			input.rows = "5";
			input.setAttribute('class', 'form-control width450px_cel margin_left_5px');                      
	      	
	      	seccion.appendChild(label);
	      	seccion.appendChild(input);	      	     	      
		}
	}

	function guardarAntecedentesFamiliares(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var hta_check = document.getElementById("ant_familiares_hta").checked;		
		var hta = 2;
		var hta_detalle = "";
		if(hta_check){
			hta = 1;
			var hta_detalle_input = document.getElementById("detalle-HTA");
			if(hta_detalle_input != null) {				
				hta_detalle = hta_detalle_input.value;		
			}
		} else {
			hta = 0;
		}

		var dbt_check = document.getElementById("ant_familiares_dbt").checked;		
		var dbt = 2;
		var dbt_detalle = "";
		if(dbt_check){
			dbt = 1;
			var dbt_detalle_input = document.getElementById("detalle-DBT");
			if(dbt_detalle_input != null) {				
				dbt_detalle = dbt_detalle_input.value;		
			}
		} else {
			dbt = 0;
		}			

		var asma_check = document.getElementById("ant_familiares_asma").checked;		
		var asma = 2;
		var asma_detalle = "";
		if(asma_check){
			asma = 1;
			var asma_detalle_input = document.getElementById("detalle-ASMA");
			if(asma_detalle_input != null) {				
				asma_detalle = asma_detalle_input.value;		
			}
		} else {
			asma = 0;
		}			

		var alergia_check = document.getElementById("ant_familiares_alergia").checked;		
		var alergia = 2;
		var alergia_detalle = "";
		if(alergia_check){
			alergia = 1;
			var alergia_detalle_input = document.getElementById("detalle-ALERGIA");
			if(alergia_detalle_input != null) {				
				alergia_detalle = alergia_detalle_input.value;		
			}
		} else {
			alergia = 0;
		}	

		var enf_cv_check = document.getElementById("ant_familiares_enf_cv").checked;		
		var enf_cv = 2;
		var enf_cv_detalle = "";
		if(enf_cv_check){
			enf_cv = 1;
			var enf_cv_detalle_input = document.getElementById("detalle-ENF C-V");
			if(enf_cv_detalle_input != null) {				
				enf_cv_detalle = enf_cv_detalle_input.value;		
			}
		} else {
			enf_cv = 0;
		}		

		var muerte_subita_check = document.getElementById("ant_familiares_muerte_subita").checked;		
		var muerte_subita = 2;
		var muerte_subita_detalle = "";
		if(muerte_subita_check){
			muerte_subita = 1;
			var muerte_subita_detalle_input = document.getElementById("detalle-MUERTE SUBITA");
			if(muerte_subita_detalle_input != null) {				
				muerte_subita_detalle = muerte_subita_detalle_input.value;		
			}
		} else {
			muerte_subita = 0;
		}			

		var enf_celiaca_check = document.getElementById("ant_familiares_enf_celiaca").checked;		
		var enf_celiaca = 2;
		var enf_celiaca_detalle = "";
		if(enf_celiaca_check){
			enf_celiaca = 1;
			var enf_celiaca_detalle_input = document.getElementById("detalle-ENF CELIACA");
			if(enf_celiaca_detalle_input != null) {				
				enf_celiaca_detalle = enf_celiaca_detalle_input.value;		
			}
		} else {
			enf_celiaca = 0;
		}			

		var enf_tiroideas_check = document.getElementById("ant_familiares_enf_tiroideas").checked;		
		var enf_tiroideas = 2;
		var enf_tiroideas_detalle = "";
		if(enf_tiroideas_check){
			enf_tiroideas = 1;
			var enf_tiroideas_detalle_input = document.getElementById("detalle-ENF TIROIDEAS");
			if(enf_tiroideas_detalle_input != null) {				
				enf_tiroideas_detalle = enf_tiroideas_detalle_input.value;		
			}
		} else {
			enf_tiroideas = 0;
		}		

		var enf_neurologicas_check = document.getElementById("ant_familiares_enf_neurologicas").checked;		
		var enf_neurologicas = 2;
		var enf_neurologicas_detalle = "";
		if(enf_neurologicas_check){
			enf_neurologicas = 1;
			var enf_neurologicas_detalle_input = document.getElementById("detalle-ENF NEUROLOGICAS");
			if(enf_neurologicas_detalle_input != null) {				
				enf_neurologicas_detalle = enf_neurologicas_detalle_input.value;		
			}
		} else {
			enf_neurologicas = 0;
		}			

		var convulsion_febril_check = document.getElementById("ant_familiares_convulsion_febril").checked;		
		var convulsion_febril = 2;
		var convulsion_febril_detalle = "";
		if(convulsion_febril_check){
			convulsion_febril = 1;
			var convulsion_febril_detalle_input = document.getElementById("detalle-CONVULSION FEBRIL");
			if(convulsion_febril_detalle_input != null) {				
				convulsion_febril_detalle = convulsion_febril_detalle_input.value;		
			}
		} else {
			convulsion_febril = 0;
		}			

		var enf_psiquiatrica_check = document.getElementById("ant_familiares_enf_psiquiatrica").checked;		
		var enf_psiquiatrica = 2;
		var enf_psiquiatrica_detalle = "";
		if(enf_psiquiatrica_check){
			enf_psiquiatrica = 1;
			var enf_psiquiatrica_detalle_input = document.getElementById("detalle-ENF PSIQUIATRICA");
			if(enf_psiquiatrica_detalle_input != null) {				
				enf_psiquiatrica_detalle = enf_psiquiatrica_detalle_input.value;		
			}
		} else {
			enf_psiquiatrica = 0;
		}			

		var enf_oh_check = document.getElementById("ant_familiares_enf_oh").checked;		
		var enf_oh = 2;
		var enf_oh_detalle = "";
		if(enf_oh_check){
			enf_oh = 1;
			var enf_oh_detalle_input = document.getElementById("detalle-ENF O-H");
			if(enf_oh_detalle_input != null) {				
				enf_oh_detalle = enf_oh_detalle_input.value;		
			}
		} else {
			enf_oh = 0;
		}			

		var tabaquismo_check = document.getElementById("ant_familiares_tabaquismo").checked;		
		var tabaquismo = 2;
		var tabaquismo_detalle = "";
		if(tabaquismo_check){
			tabaquismo = 1;
			var tabaquismo_detalle_input = document.getElementById("detalle-TABAQUISMO");
			if(tabaquismo_detalle_input != null) {				
				tabaquismo_detalle = tabaquismo_detalle_input.value;		
			}
		} else {
			tabaquismo = 0;
		}

		var otro_check = document.getElementById("ant_familiares_otro").checked;		
		var otro = 2;
		var otro_detalle = "";
		if(otro_check){
			otro = 1;
			var otro_detalle_input = document.getElementById("detalle-OTRO");
			if(otro_detalle_input != null) {				
				otro_detalle = otro_detalle_input.value;		
			}
		} else {
			otro = 0;
		}	

		var nota = document.getElementById("antecedentes_familiares_nota").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_antecedentes_familiares',
           data:{consulta:consulta, paciente:paciente, hta:hta, hta_detalle:hta_detalle, dbt:dbt, dbt_detalle:dbt_detalle, asma:asma, asma_detalle:asma_detalle, alergia:alergia, alergia_detalle:alergia_detalle, enf_cv:enf_cv, enf_cv_detalle:enf_cv_detalle, muerte_subita:muerte_subita, muerte_subita_detalle:muerte_subita_detalle, enf_celiaca:enf_celiaca, enf_celiaca_detalle:enf_celiaca_detalle, enf_tiroideas:enf_tiroideas, enf_tiroideas_detalle:enf_tiroideas_detalle, enf_neurologicas:enf_neurologicas, enf_neurologicas_detalle:enf_neurologicas_detalle, convulsion_febril:convulsion_febril, convulsion_febril_detalle:convulsion_febril_detalle, enf_psiquiatrica:enf_psiquiatrica, enf_psiquiatrica_detalle:enf_psiquiatrica_detalle, enf_oh:enf_oh, enf_oh_detalle:enf_oh_detalle, tabaquismo:tabaquismo, tabaquismo_detalle:tabaquismo_detalle, otro:otro, otro_detalle:otro_detalle, nota:nota ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ANTECEDENTES FAMILIARES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });	
	}

	function cargarAntecedentesFamiliares(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_familiares',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){                  			         			
					if(data.response_data.hta == 1){
						document.getElementById("ant_familiares_hta").checked = true;
						agregarDetalle("HTA");
						document.getElementById("detalle-HTA").value = data.response_data.hta_detalle;						
					}					

					if(data.response_data.dbt == 1){
						document.getElementById("ant_familiares_dbt").checked = true;
						agregarDetalle("DBT");
						document.getElementById("detalle-DBT").value = data.response_data.dbt_detalle;						
					}

					if(data.response_data.asma == 1){
						document.getElementById("ant_familiares_asma").checked = true;
						agregarDetalle("ASMA");
						document.getElementById("detalle-ASMA").value = data.response_data.asma_detalle;						
					}

					if(data.response_data.alergia == 1){
						document.getElementById("ant_familiares_alergia").checked = true;
						agregarDetalle("ALERGIA");
						document.getElementById("detalle-ALERGIA").value = data.response_data.alergia_detalle;						
					}

					if(data.response_data.enf_cv == 1){
						document.getElementById("ant_familiares_enf_cv").checked = true;
						agregarDetalle("ENF C-V");
						document.getElementById("detalle-ENF C-V").value = data.response_data.enf_cv_detalle;						
					}
					
					if(data.response_data.muerte_subita == 1){
						document.getElementById("ant_familiares_muerte_subita").checked = true;
						agregarDetalle("MUERTE SUBITA");
						document.getElementById("detalle-MUERTE SUBITA").value = data.response_data.muerte_subita_detalle;			
					}

					if(data.response_data.enf_celiaca == 1){
						document.getElementById("ant_familiares_enf_celiaca").checked = true;
						agregarDetalle("ENF CELIACA");
						document.getElementById("detalle-ENF CELIACA").value = data.response_data.enf_celiaca_detalle;			
					}

					if(data.response_data.enf_tiroideas == 1){
						document.getElementById("ant_familiares_enf_tiroideas").checked = true;
						agregarDetalle("ENF TIROIDEAS");
						document.getElementById("detalle-ENF TIROIDEAS").value = data.response_data.enf_tiroideas_detalle;			
					}

					if(data.response_data.enf_neurologicas == 1){
						document.getElementById("ant_familiares_enf_neurologicas").checked = true;
						agregarDetalle("ENF NEUROLOGICAS");
						document.getElementById("detalle-ENF NEUROLOGICAS").value = data.response_data.enf_neurologicas_detalle;	
					}

					if(data.response_data.convulsion_febril == 1){
						document.getElementById("ant_familiares_convulsion_febril").checked = true;
						agregarDetalle("CONVULSION FEBRIL");
						document.getElementById("detalle-CONVULSION FEBRIL").value = data.response_data.convulsion_febril_detalle;	
					}

					if(data.response_data.enf_psiquiatrica == 1){
						document.getElementById("ant_familiares_enf_psiquiatrica").checked = true;
						agregarDetalle("ENF PSIQUIATRICA");
						document.getElementById("detalle-ENF PSIQUIATRICA").value = data.response_data.enf_psiquiatrica_detalle;	
					}

					if(data.response_data.enf_oh == 1){
						document.getElementById("ant_familiares_enf_oh").checked = true;
						agregarDetalle("ENF O-H");
						document.getElementById("detalle-ENF O-H").value = data.response_data.enf_oh_detalle;	
					}

					if(data.response_data.tabaquismo == 1){
						document.getElementById("ant_familiares_tabaquismo").checked = true;
						agregarDetalle("TABAQUISMO");
						document.getElementById("detalle-TABAQUISMO").value = data.response_data.tabaquismo_detalle;	
					}

					if(data.response_data.otro == 1){
						document.getElementById("ant_familiares_otro").checked = true;
						agregarDetalle("OTRO");
						document.getElementById("detalle-OTRO").value = data.response_data.otro_detalle;	
					}

					document.getElementById("antecedentes_familiares_nota").value = data.response_data.nota;	

            	} else {
            		//mostrarSanckbar("ANTECEDENTES FAMILIARES FALLO");            		
            	}
            }
        });	
	}

</script>
