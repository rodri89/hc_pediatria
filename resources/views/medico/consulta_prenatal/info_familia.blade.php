@if($nueva_consulta == 1)
	<form class="card background_panel_consulta_actual" id="seccion_familia_nueva_consulta">				
		<div class="row margin_left_5px">
			<h4><b><a onclick="clickFamilia()" type="button">Familia</a></b></h4>
		</div>
@else
	<form id="seccion_familia_nueva_consulta">		
		<div class="row margin_left_5px">
			<h4><b>Familia</b></h4>
		</div>
@endif
		
	<div id="seccion_familia" hidden>		
		
		<div class="row margin_left_5px">					
			<label id="cp_familia_mama_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Mamá:</label>
        	<input type="text" class="form-control input_width_250px margin_left_5px" id="cp_familia_mama" name="cp_familia_mama"/>

        	<label id="cp_familia_mama_edad_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Edad:</label>
        	<input type="text" class="form-control input_width_60px margin_left_5px" id="cp_familia_mama_edad" name="cp_familia_mama_edad"/>

        	<label id="cp_familia_mama_ocupacion_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Ocupación:</label>
        	<input type="text" class="form-control input_width_250px margin_left_5px" id="cp_familia_mama_ocupacion" name="cp_familia_mama_ocupacion"/>			
		</div>

		<div class="row margin_left_5px margin_top_5px">					
			<label id="cp_familia_papa_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Papá:</label>
        	<input type="text" class="form-control input_width_250px margin_left_10px" id="cp_familia_papa" name="cp_familia_papa"/>

        	<label id="cp_familia_papa_edad_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Edad:</label>
        	<input type="text" class="form-control input_width_60px margin_left_5px" id="cp_familia_papa_edad" name="cp_familia_papa_edad"/>

        	<label id="cp_familia_papa_ocupacion_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Ocupación:</label>
        	<input type="text" class="form-control input_width_250px margin_left_5px" id="cp_familia_papa_ocupacion" name="cp_familia_papa_ocupacion"/>			
		</div>

		<div class="row margin_left_5px margin_top_5px">					
			<label id="cp_familia_bebe_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Bebé:</label>
			<input type="text" class="form-control input_width_250px margin_left_10px" id="cp_familia_bebe" name="cp_familia_bebe"/>

        	<label id="cp_familia_bebe_eg_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">EG:</label>
        	<input type="text" class="form-control input_width_60px margin_left_5px" id="cp_familia_bebe_eg" name="cp_familia_bebe_eg"/>

        	<label id="cp_familia_bebe_fpp_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">FPP:</label>
        	<input type="text" class="form-control input_width_60px margin_left_5px" id="cp_familia_bebe_fpp" name="cp_familia_bebe_fpp"/>			
		</div>

		<div class="row margin_left_5px margin_top_5px">					
			<label id="cp_familia_hermanos_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">Hermanos:</label>        				
        	<div id="cp_familia_hermanos_si_seccion" class="custom-control custom-radio margin_left_10px margin_top_5px">
		  		<input type="radio" id="cp_familia_hermanos_si" name="cp_familia_hermanos_group" class="custom-control-input">
		  		<label class="custom-control-label" for="cp_familia_hermanos_si">Si</label>
			</div>
			<div id="cp_familia_hermanos_no_seccion" class="custom-control custom-radio margin_top_5px">
				 <input type="radio" id="cp_familia_hermanos_no" name="cp_familia_hermanos_group" class="custom-control-input">
				 <label class="custom-control-label margin_left_5px" for="cp_familia_hermanos_no">No</label>
			</div>		
		</div>

		<br>		
	</div>
</form>

<div id="seccion_familia_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickFamilia(){
		seccionPrincipal = document.getElementById("seccion_familia");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarFamilia(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		
		var cp_familia_mama = document.getElementById("cp_familia_mama").value;
		var cp_familia_mama_edad = document.getElementById("cp_familia_mama_edad").value;
		var cp_familia_mama_ocupacion = document.getElementById("cp_familia_mama_ocupacion").value;

		var cp_familia_papa = document.getElementById("cp_familia_papa").value;
		var cp_familia_papa_edad = document.getElementById("cp_familia_papa_edad").value;
		var cp_familia_papa_ocupacion = document.getElementById("cp_familia_papa_ocupacion").value;

		var cp_familia_bebe = document.getElementById("cp_familia_bebe").value;
		var cp_familia_bebe_eg = document.getElementById("cp_familia_bebe_eg").value;
		var cp_familia_bebe_fpp = document.getElementById("cp_familia_bebe_fpp").value;

		var hermanos = 0;
		if(document.getElementById("cp_familia_hermanos_si").checked)
			hermanos = 1;
		if(document.getElementById("cp_familia_hermanos_no").checked)
			hermanos = 2;	
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_familia',
           data:{consulta:consulta, paciente:paciente, cp_familia_mama:cp_familia_mama, cp_familia_mama_edad:cp_familia_mama_edad, cp_familia_mama_ocupacion:cp_familia_mama_ocupacion, cp_familia_papa:cp_familia_papa, cp_familia_papa_edad:cp_familia_papa_edad, cp_familia_papa_ocupacion, cp_familia_papa_ocupacion, cp_familia_bebe:cp_familia_bebe, cp_familia_bebe_eg:cp_familia_bebe_eg, cp_familia_bebe_fpp:cp_familia_bebe_fpp, hermanos:hermanos ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
           			mostrarSnackbar("FAMILIA GUARDADO");
            	} else {
            		mostrarSnackbar("FAMILIA FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarFamiliaConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		

		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_familia',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         	
           		ocultarCamposFamilia();	
           		if(data.response_data != null && data.response == 1){              		           		           		          
					document.getElementById("seccion_familia_nueva_consulta").hidden = false;
					document.getElementById("seccion_familia").hidden = false;

					if(data.response_data.mama.localeCompare("")!=0){
						document.getElementById("cp_familia_mama_label").hidden = false;
						document.getElementById("cp_familia_mama").hidden = false;
						document.getElementById("cp_familia_mama").value = data.response_data.mama;
					}
					if(data.response_data.mama_edad.localeCompare("")!=0){
						document.getElementById("cp_familia_mama_edad_label").hidden = false;
						document.getElementById("cp_familia_mama_edad").hidden = false;
						document.getElementById("cp_familia_mama_edad").value = data.response_data.mama_edad;
					} 
					if(data.response_data.mama_ocupacion.localeCompare("")!=0){
						document.getElementById("cp_familia_mama_ocupacion_label").hidden = false;
						document.getElementById("cp_familia_mama_ocupacion").hidden = false;
						document.getElementById("cp_familia_mama_ocupacion").value = data.response_data.mama_ocupacion;
					}
					if(data.response_data.papa.localeCompare("")!=0){
						document.getElementById("cp_familia_papa_label").hidden = false;
						document.getElementById("cp_familia_papa").hidden = false;
						document.getElementById("cp_familia_papa").value = data.response_data.papa;
					}
					if(data.response_data.papa_edad.localeCompare("")!=0){
						document.getElementById("cp_familia_papa_edad_label").hidden = false;
						document.getElementById("cp_familia_papa_edad").hidden = false;
						document.getElementById("cp_familia_papa_edad").value = data.response_data.papa_edad;
					}
					if(data.response_data.papa_ocupacion.localeCompare("")!=0){
						document.getElementById("cp_familia_papa_ocupacion_label").hidden = false;
						document.getElementById("cp_familia_papa_ocupacion").hidden = false;
						document.getElementById("cp_familia_papa_ocupacion").value = data.response_data.papa_ocupacion;
					}
					if(data.response_data.bebe.localeCompare("")!=0){
						document.getElementById("cp_familia_bebe_label").hidden = false;
						document.getElementById("cp_familia_bebe").hidden = false;
						document.getElementById("cp_familia_bebe").value = data.response_data.bebe;
					}
					if(data.response_data.eg.localeCompare("")!=0){
						document.getElementById("cp_familia_bebe_eg_label").hidden = false;
						document.getElementById("cp_familia_bebe_eg").hidden = false;
						document.getElementById("cp_familia_bebe_eg").value = data.response_data.eg;
					}
					if(data.response_data.fpp.localeCompare("")!=0){
						document.getElementById("cp_familia_bebe_fpp_label").hidden = false;
						document.getElementById("cp_familia_bebe_fpp").hidden = false;
						document.getElementById("cp_familia_bebe_fpp").value = data.response_data.fpp;
					}
					if(data.response_data.hermanos != 0){
						document.getElementById("cp_familia_hermanos_label").hidden = false;
						document.getElementById("cp_familia_hermanos_si_seccion").hidden = false;
						document.getElementById("cp_familia_hermanos_no_seccion").hidden = false;
						if(data.response_data.hermanos == 1){
							document.getElementById("cp_familia_hermanos_si").checked = true;
						} else {							
							document.getElementById("cp_familia_hermanos_no").checked = true;						
						}											
					}
            	} else {
            		document.getElementById("seccion_familia_nueva_consulta").hidden = true;
            	}
            }
        });	
	}

	function ocultarCamposFamilia(){
		document.getElementById("cp_familia_mama_label").hidden = true;
		document.getElementById("cp_familia_mama").hidden = true;
		document.getElementById("cp_familia_mama").value = "";
		document.getElementById("cp_familia_mama_edad_label").hidden = true;
		document.getElementById("cp_familia_mama_edad").hidden = true;
		document.getElementById("cp_familia_mama_edad").value = "";
		document.getElementById("cp_familia_mama_ocupacion_label").hidden = true;
		document.getElementById("cp_familia_mama_ocupacion").hidden = true;
		document.getElementById("cp_familia_mama_ocupacion").value = "";
		document.getElementById("cp_familia_papa_label").hidden = true;
		document.getElementById("cp_familia_papa").hidden = true;
		document.getElementById("cp_familia_papa").value = "";
		document.getElementById("cp_familia_papa_edad_label").hidden = true;
		document.getElementById("cp_familia_papa_edad").hidden = true;
		document.getElementById("cp_familia_papa_edad").value = "";
		document.getElementById("cp_familia_papa_ocupacion_label").hidden = true;		
		document.getElementById("cp_familia_papa_ocupacion").hidden = true;
		document.getElementById("cp_familia_papa_ocupacion").value = "";
		document.getElementById("cp_familia_bebe_label").hidden = true;
		document.getElementById("cp_familia_bebe").hidden = true;
		document.getElementById("cp_familia_bebe").value = "";
		document.getElementById("cp_familia_bebe_eg_label").hidden = true;
		document.getElementById("cp_familia_bebe_eg").hidden = true;
		document.getElementById("cp_familia_bebe_eg").value = "";
		document.getElementById("cp_familia_bebe_fpp_label").hidden = true;
		document.getElementById("cp_familia_bebe_fpp").hidden = true;
		document.getElementById("cp_familia_bebe_fpp").value = "";
		document.getElementById("cp_familia_hermanos_label").hidden = true;
		document.getElementById("cp_familia_hermanos_si_seccion").hidden = true;
		document.getElementById("cp_familia_hermanos_no_seccion").hidden = true;
		document.getElementById("cp_familia_hermanos_si_seccion").checked = false;
		document.getElementById("cp_familia_hermanos_no_seccion").checked = false;
	}

	function cargarFamilia(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("familia_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}
</script>