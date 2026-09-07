@if($nueva_consulta == 1)
<form class="card background_panel_consulta_actual" id="seccion_embarazo_actual_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickEmbarazoActual()" type="button">Embarazo Actual</a></b></h4>
	</div>	
@else
	<form id="seccion_embarazo_actual_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b>Embarazo Actual</b></h4>
	</div>	
@endif
	<div id="seccion_embarazo_actual" hidden>		

		<div class="row margin_left_5px">
			<label id="embarazo_actual_obstetra_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Obstetra:</label>
	        <input type="text" class="form-control input_width_250px margin_left_5px" id="embarazo_actual_obstetra" name="embarazo_actual_obstetra"  />					
		</div>

		<div class="row margin_left_5px margin_top_12px">
			<label id="embarazo_actual_eg_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">EG:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="embarazo_actual_eg" name="embarazo_actual_eg"  placeholder="0"/>		
			<label id="embarazo_actual_semanas_label" class="margin_left_5px margin_top_5px input_width_appx_cel" for="talla">Semanas.</label>	

			<label id="embarazo_actual_n_controles_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Nº Controles:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="embarazo_actual_n_controles" name="embarazo_actual_n_controles"  placeholder="0"/>		
		</div>

		
			<label id="embarazo_actual_serologia_label" class="margin_top_12px">Serología:</label>   	
			<br><label id="embarazo_actual_serologia_1_label" class="margin_left_60px">1° Trim</label>
			<div id="embarazo_actual_serologia_1_seccion" class="row margin_left_60px">
			  	<div class="custom-control custom-radio margin_left_5px">
			  		<input onclick="clickEmbarazoActualSerologiaSi(1)" type="radio" id="embarazo_actual_serologia1_si" name="embarazo_actual_serologia1_group" class="custom-control-input">
			  		<label class="custom-control-label" for="embarazo_actual_serologia1_si">+</label>
				</div>
				<div class="custom-control custom-radio">
					 <input onclick="clickEmbarazoActualSerologiaNo(1)" type="radio" id="embarazo_actual_serologia1_no" name="embarazo_actual_serologia1_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="embarazo_actual_serologia1_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="embarazo_actual_serologia1_si_detalle" name="embarazo_actual_serologia1_si_detalle"></textarea>								
			</div>	
			<br><label id="embarazo_actual_serologia_2_label" class="margin_left_60px">2° Trim</label>
			<div id="embarazo_actual_serologia_2_seccion" class="row margin_left_60px">							  	
			  	<div class="custom-control custom-radio margin_left_5px ">			  		
			  		<input onclick="clickEmbarazoActualSerologiaSi(2)" type="radio" id="embarazo_actual_serologia2_si" name="embarazo_actual_serologia2_group" class="custom-control-input">
			  		<label class="custom-control-label" for="embarazo_actual_serologia2_si">+</label>
				</div>				 
				<div class="custom-control custom-radio ">					 
					 <input onclick="clickEmbarazoActualSerologiaNo(2)" type="radio" id="embarazo_actual_serologia2_no" name="embarazo_actual_serologia2_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="embarazo_actual_serologia2_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="embarazo_actual_serologia2_si_detalle" name="embarazo_actual_serologia2_si_detalle"></textarea>								
			</div>	

			<label id="embarazo_actual_hisopsbha_label" class="margin_left_5px margin_top_12px">Hisop SBHB:</label>      
			<div id="embarazo_actual_hisopsbha_seccion" class="row margin_left_60px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickEmbarazoActualHisopSBHAsi()" type="radio" id="embarazo_actual_hisopsbha_si" name="embarazo_actual_hisopsbha_group" class="custom-control-input">
			  		<label class="custom-control-label" for="embarazo_actual_hisopsbha_si">+</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickEmbarazoActualHisopSBHAno()" type="radio" id="embarazo_actual_hisopsbha_no" name="embarazo_actual_hisopsbha_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="embarazo_actual_hisopsbha_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="embarazo_actual_hisopsbha_si_detalle" name="embarazo_actual_hisopsbha_si_detalle"></textarea>								
			</div>

			<label id="embarazo_actual_ptog_label" class="margin_left_5px margin_top_12px">PTOG:</label>      
			<div id="embarazo_actual_ptog_seccion" class="row margin_left_60px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickEmbarazoActualPTOG(1)" type="radio" id="embarazo_actual_ptog_n" name="embarazo_actual_ptog_group" class="custom-control-input">
			  		<label class="custom-control-label" for="embarazo_actual_ptog_n">N</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickEmbarazoActualPTOG(2)" type="radio" id="embarazo_actual_ptog_p" name="embarazo_actual_ptog_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="embarazo_actual_ptog_p">P</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="embarazo_actual_ptog_detalle" name="embarazo_actual_ptog_detalle"></textarea>								
			</div>
			
			<label id="embarazo_actual_vacunas_label" class="margin_left_5px margin_top_12px">Vacunas:</label>
			<div class="row margin_left_5px">		
				<textarea class="form-control width650px_cel" id="embarazo_actual_vacunas" name="embarazo_actual_vacunas" rows="2" cols="80"></textarea>	
			</div>

			<div id="embarazo_actual_parto_seccion" class="row margin_left_5px margin_top_12px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickEmbarazoActualParto(1)" type="radio" id="embarazo_actual_parto" name="embarazo_actual_parto_group" class="custom-control-input">
			  		<label class="custom-control-label" for="embarazo_actual_parto">Parto</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickEmbarazoActualParto(2)" type="radio" id="embarazo_actual_cesarea" name="embarazo_actual_parto_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="embarazo_actual_cesarea">Cesárea</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="embarazo_actual_cesarea_detalle" name="embarazo_actual_cesarea_detalle"></textarea>								
			</div>

			<label id="embarazo_actual_ecografia_label" class="margin_left_5px margin_top_12px">Ecografia:</label>
			<div class="row margin_left_5px">		
				<textarea class="form-control width650px_cel" id="embarazo_actual_ecografias" name="embarazo_actual_ecografias" rows="4" cols="80"></textarea>	
			</div>

			<label id="embarazo_actual_observaciones_label" class="margin_left_5px margin_top_12px">Observaciones:</label>
			<div class="row margin_left_5px">		
				<textarea class="form-control width650px_cel" id="embarazo_actual_observaciones" name="embarazo_actual_observaciones" rows="4" cols="80"></textarea>	
			</div>
		<br>		
	</div>
</form>

<script type="text/javascript">
	
	function clickEmbarazoActual(){
		seccionPrincipal = document.getElementById("seccion_embarazo_actual");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function clickEmbarazoActualSerologiaSi(trimestre){
		if(trimestre == 1){
			document.getElementById("embarazo_actual_serologia1_si_detalle").hidden = false;
		} else {
			document.getElementById("embarazo_actual_serologia2_si_detalle").hidden = false;
		}
	}

	function clickEmbarazoActualHisopSBHAsi(){
		document.getElementById("embarazo_actual_hisopsbha_si_detalle").hidden = false;
	}

	function clickEmbarazoActualHisopSBHAno(){
		document.getElementById("embarazo_actual_hisopsbha_si_detalle").hidden = true; 	
		document.getElementById("embarazo_actual_hisopsbha_si_detalle").value = ''; 
	}

	function clickEmbarazoActualPTOG(opcion){
		if(opcion == 1){
			document.getElementById("embarazo_actual_ptog_detalle").hidden = true; 	
			document.getElementById("embarazo_actual_ptog_detalle").value = '';
		} else {
			document.getElementById("embarazo_actual_ptog_detalle").hidden = false; 			
		}
	}

	function clickEmbarazoActualParto(opcion){
		if(opcion == 1){
			document.getElementById("embarazo_actual_cesarea_detalle").hidden = true; 	
			document.getElementById("embarazo_actual_cesarea_detalle").value = '';
		} else {
			document.getElementById("embarazo_actual_cesarea_detalle").hidden = false; 			
		}	
	}

	function clickEmbarazoActualSerologiaNo(trimestre) {
		if(trimestre == 1){
			document.getElementById("embarazo_actual_serologia1_si_detalle").hidden = true; 	
			document.getElementById("embarazo_actual_serologia1_si_detalle").value = ''; 
		} else {
			document.getElementById("embarazo_actual_serologia2_si_detalle").hidden = true; 	
			document.getElementById("embarazo_actual_serologia2_si_detalle").value = ''; 
		}
	}

	function guardarEmbarazoActual(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var embarazo_actual_obstetra = document.getElementById("embarazo_actual_obstetra").value;
		var embarazo_actual_eg = document.getElementById("embarazo_actual_eg").value;
		var embarazo_actual_n_controles = document.getElementById("embarazo_actual_n_controles").value;
		var embarazo_actual_vacunas = document.getElementById("embarazo_actual_vacunas").value;
		var embarazo_actual_observaciones = document.getElementById("embarazo_actual_observaciones").value;
		var embarazo_actual_ecografias = document.getElementById("embarazo_actual_ecografias").value;

		var serologia_1 = 0;
		var serologia_1_detalle = "";
		if(document.getElementById("embarazo_actual_serologia1_si").checked){
			serologia_1 = 1;
			serologia_1_detalle = document.getElementById("embarazo_actual_serologia1_si_detalle").value;
		}
		if(document.getElementById("embarazo_actual_serologia1_no").checked){
			serologia_1 = 2;			
		}

		var serologia_2 = 0;
		var serologia_2_detalle = "";
		if(document.getElementById("embarazo_actual_serologia2_si").checked){
			serologia_2 = 1;
			serologia_2_detalle = document.getElementById("embarazo_actual_serologia2_si_detalle").value;
		}
		if(document.getElementById("embarazo_actual_serologia2_no").checked){
			serologia_2 = 2;			
		}

		var hisopsbha = 0;
		var hisopsbha_detalle = "";
		if(document.getElementById("embarazo_actual_hisopsbha_si").checked){
			hisopsbha = 1;
			hisopsbha_detalle = document.getElementById("embarazo_actual_hisopsbha_si_detalle").value;
		}
		if(document.getElementById("embarazo_actual_hisopsbha_no").checked){
			hisopsbha = 2;			
		}

		var ptog = 0;
		var ptog_detalle = "";
		if(document.getElementById("embarazo_actual_ptog_n").checked){
			ptog = 1;			
		}
		if(document.getElementById("embarazo_actual_ptog_p").checked){
			ptog = 2;
			ptog_detalle = document.getElementById("embarazo_actual_ptog_detalle").value;			
		}

		var parto = 0;
		var cesarea_detalle = "";
		if(document.getElementById("embarazo_actual_parto").checked){
			parto = 1;			
		}
		if(document.getElementById("embarazo_actual_cesarea").checked){
			parto = 2;
			cesarea_detalle = document.getElementById("embarazo_actual_cesarea_detalle").value;			
		}

		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_embarazo_actual',
           data:{consulta:consulta, paciente:paciente, embarazo_actual_obstetra:embarazo_actual_obstetra, embarazo_actual_eg:embarazo_actual_eg, embarazo_actual_n_controles:embarazo_actual_n_controles, embarazo_actual_vacunas:embarazo_actual_vacunas, embarazo_actual_observaciones:embarazo_actual_observaciones, embarazo_actual_ecografias:embarazo_actual_ecografias, serologia_1:serologia_1, serologia_1_detalle:serologia_1_detalle, serologia_2:serologia_2, serologia_2_detalle:serologia_2_detalle, hisopsbha:hisopsbha, hisopsbha_detalle:hisopsbha_detalle, ptog:ptog, ptog_detalle:ptog_detalle, parto:parto, cesarea_detalle:cesarea_detalle, _token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
           			mostrarSnackbar("EMBARAZO ACTUAL GUARDADO");
            	} else {
            		mostrarSnackbar("EMBARAZO ACTUAL FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarEmbarazoActualConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_embarazo_actual',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		ocultarCamposEmbarazoActual(); 
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_embarazo_actual_nueva_consulta").hidden = false;
           			document.getElementById("seccion_embarazo_actual").hidden = false;

           			if(data.response_data.obstetra.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_obstetra_label").hidden = false;
						document.getElementById("embarazo_actual_obstetra").hidden = false;
						document.getElementById("embarazo_actual_obstetra").value = data.response_data.obstetra;
           			}
           			if(data.response_data.eg.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_eg_label").hidden = false;
						document.getElementById("embarazo_actual_eg").hidden = false;
						document.getElementById("embarazo_actual_eg").value = data.response_data.eg;
           			}
           			if(data.response_data.n_controles.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_n_controles_label").hidden = false;
						document.getElementById("embarazo_actual_n_controles").hidden = false;
						document.getElementById("embarazo_actual_n_controles").value = data.response_data.n_controles;
           			}
           			if(data.response_data.serologia_1 != 0) {
           				document.getElementById("embarazo_actual_serologia_label").hidden = false;
           				document.getElementById("embarazo_actual_serologia_1_label").hidden = false;
						document.getElementById("embarazo_actual_serologia_1_seccion").hidden = false;						
           				if(data.response_data.serologia_1 == 1){
           					document.getElementById("embarazo_actual_serologia1_si").checked = true;
           					if(data.response_data.serologia_1_detalle.localeCompare("")!= 0){
           						document.getElementById("embarazo_actual_serologia1_si_detalle").hidden = false;
           						document.getElementById("embarazo_actual_serologia1_si_detalle").value = data.response_data.serologia_1_detalle;
           					}
           				} else {
           					document.getElementById("embarazo_actual_serologia1_no").checked = true;
           				}           				
           			}

           			if(data.response_data.serologia_2 != 0) {
           				document.getElementById("embarazo_actual_serologia_label").hidden = false;
           				document.getElementById("embarazo_actual_serologia_2_label").hidden = false;
						document.getElementById("embarazo_actual_serologia_2_seccion").hidden = false;						
           				if(data.response_data.serologia_2 == 1){
           					document.getElementById("embarazo_actual_serologia2_si").checked = true;
           					if(data.response_data.serologia_2_detalle.localeCompare("")!= 0){
           						document.getElementById("embarazo_actual_serologia2_si_detalle").hidden = false;
           						document.getElementById("embarazo_actual_serologia2_si_detalle").value = data.response_data.serologia_2_detalle;
           					}
           				} else {
           					document.getElementById("embarazo_actual_serologia2_no").checked = true;
           				}           				
           			}

           			if(data.response_data.hisop_sbhb != 0) {
           				document.getElementById("embarazo_actual_hisopsbha_label").hidden = false;           				
						document.getElementById("embarazo_actual_hisopsbha_seccion").hidden = false;						
           				if(data.response_data.hisop_sbhb == 1){
           					document.getElementById("embarazo_actual_hisopsbha_si").checked = true;
           					if(data.response_data.hisop_sbhb_detalle.localeCompare("")!= 0){
           						document.getElementById("embarazo_actual_hisopsbha_si_detalle").hidden = false;
           						document.getElementById("embarazo_actual_hisopsbha_si_detalle").value = data.response_data.hisop_sbhb_detalle;
           					}
           				} else {
           					document.getElementById("embarazo_actual_hisopsbha_no").checked = true;
           				}           				
           			}

           			if(data.response_data.ptog != 0) {
           				document.getElementById("embarazo_actual_ptog_label").hidden = false;           				
						document.getElementById("embarazo_actual_ptog_seccion").hidden = false;						
           				if(data.response_data.ptog == 1){
           					document.getElementById("embarazo_actual_ptog_n").checked = true;           					
           				} else {
           					document.getElementById("embarazo_actual_ptog_p").checked = true;
           					if(data.response_data.ptog_detalle.localeCompare("")!= 0){
           						document.getElementById("embarazo_actual_ptog_detalle").hidden = false;
           						document.getElementById("embarazo_actual_ptog_detalle").value = data.response_data.ptog_detalle;
           					}
           				}           				
           			}

           			if(data.response_data.parto != 0) {           				
						document.getElementById("embarazo_actual_parto_seccion").hidden = false;						
           				if(data.response_data.parto == 2){
           					document.getElementById("embarazo_actual_cesarea").checked = true;
           					if(data.response_data.cesarea_detalle.localeCompare("")!= 0){
           						document.getElementById("embarazo_actual_cesarea_detalle").hidden = false;
           						document.getElementById("embarazo_actual_cesarea_detalle").value = data.response_data.cesarea_detalle;
           					}
           				} else {
           					document.getElementById("embarazo_actual_parto").checked = true;
           				}           				
           			}

           			if(data.response_data.ecografia.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_ecografia_label").hidden = false;
						document.getElementById("embarazo_actual_ecografias").hidden = false;
						document.getElementById("embarazo_actual_ecografias").value = data.response_data.ecografia;
           			}

           			if(data.response_data.vacunas.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_vacunas_label").hidden = false;
						document.getElementById("embarazo_actual_vacunas").hidden = false;
						document.getElementById("embarazo_actual_vacunas").value = data.response_data.vacunas;
           			}

           			if(data.response_data.observaciones.localeCompare("") != 0){
           				document.getElementById("embarazo_actual_observaciones_label").hidden = false;
						document.getElementById("embarazo_actual_observaciones").hidden = false;
						document.getElementById("embarazo_actual_observaciones").value = data.response_data.observaciones;
           			}

            	} else {
            		document.getElementById("seccion_embarazo_actual_nueva_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarEmbarazoActual(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("embarazo_actual_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}

	function ocultarCamposEmbarazoActual(){
		document.getElementById("embarazo_actual_obstetra_label").hidden = true;
		document.getElementById("embarazo_actual_obstetra").hidden = true;
		document.getElementById("embarazo_actual_obstetra").value = "";
		document.getElementById("embarazo_actual_eg_label").hidden = true;
		document.getElementById("embarazo_actual_eg").hidden = true;
		document.getElementById("embarazo_actual_eg").value = "";
		document.getElementById("embarazo_actual_semanas_label").hidden = true;
		document.getElementById("embarazo_actual_n_controles_label").hidden = true;
		document.getElementById("embarazo_actual_n_controles").hidden = true;
		document.getElementById("embarazo_actual_n_controles").value = "";
		document.getElementById("embarazo_actual_serologia_label").hidden = true;
		document.getElementById("embarazo_actual_serologia_1_label").hidden = true;
		document.getElementById("embarazo_actual_serologia_1_seccion").hidden = true;
		document.getElementById("embarazo_actual_serologia1_si").checked = false;
		document.getElementById("embarazo_actual_serologia1_no").checked = false;
		document.getElementById("embarazo_actual_serologia1_si_detalle").hidden = true;
		document.getElementById("embarazo_actual_serologia1_si_detalle").value = "";

		document.getElementById("embarazo_actual_serologia_2_label").hidden = true;
		document.getElementById("embarazo_actual_serologia_2_seccion").hidden = true;
		document.getElementById("embarazo_actual_serologia2_si").checked = false;
		document.getElementById("embarazo_actual_serologia2_no").checked = false;
		document.getElementById("embarazo_actual_serologia2_si_detalle").hidden = true;
		document.getElementById("embarazo_actual_serologia2_si_detalle").value = "";

		document.getElementById("embarazo_actual_hisopsbha_label").hidden = true;
		document.getElementById("embarazo_actual_hisopsbha_seccion").hidden = true;
		document.getElementById("embarazo_actual_hisopsbha_si").checked = false;
		document.getElementById("embarazo_actual_hisopsbha_no").checked = false;
		document.getElementById("embarazo_actual_hisopsbha_si_detalle").hidden = true;
		document.getElementById("embarazo_actual_hisopsbha_si_detalle").value = "";

		document.getElementById("embarazo_actual_ptog_label").hidden = true;
		document.getElementById("embarazo_actual_ptog_seccion").hidden = true;
		document.getElementById("embarazo_actual_ptog_n").checked = false;
		document.getElementById("embarazo_actual_ptog_p").checked = false;
		document.getElementById("embarazo_actual_ptog_detalle").hidden = true;
		document.getElementById("embarazo_actual_ptog_detalle").value = "";

		document.getElementById("embarazo_actual_vacunas_label").hidden = true;
		document.getElementById("embarazo_actual_vacunas").hidden = true;
		document.getElementById("embarazo_actual_vacunas").value = "";

		document.getElementById("embarazo_actual_parto_seccion").hidden = true;		
		document.getElementById("embarazo_actual_parto").checked = false;
		document.getElementById("embarazo_actual_cesarea").checked = false;
		document.getElementById("embarazo_actual_cesarea_detalle").hidden = true;
		document.getElementById("embarazo_actual_cesarea_detalle").value = "";

		document.getElementById("embarazo_actual_ecografia_label").hidden = true;
		document.getElementById("embarazo_actual_ecografias").hidden = true;
		document.getElementById("embarazo_actual_ecografias").value = "";

		document.getElementById("embarazo_actual_observaciones_label").hidden = true;
		document.getElementById("embarazo_actual_observaciones").hidden = true;
		document.getElementById("embarazo_actual_observaciones").value = "";		
	}

</script>