@if($nueva_consulta == 1)
<form class="card background_panel_consulta_actual" id="seccion_ant_obstetricos_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickAntecedentesObstetricos()" type="button">Antecedentes Obstétricos</a></b></h4>
	</div>	
@else
	<form id="seccion_ant_obstetricos_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b>Antecedentes Obstétricos</b></h4>
	</div>	
@endif
	<div id="seccion_ant_obstetricos" hidden>		
		
		<div class="row margin_left_5px margin_top_12px">
			<label id="ant_obstetricos_g_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">G:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="ant_obstetricos_g" name="ant_obstetricos_g"  placeholder=""/>		
			
			<label id="ant_obstetricos_p_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">P:</label>
	        <input onchange="checkp()" type="number" class="form-control input_width_80px margin_left_5px" id="ant_obstetricos_p" name="ant_obstetricos_p"  placeholder=""/>

	        <label id="ant_obstetricos_a_label" class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">A:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="ant_obstetricos_a" name="ant_obstetricos_a"  placeholder=""/>		

	        <div class="row margin_left_5px">		
				<textarea class="form-control width650px_cel" id="ant_obstetricos_detalles" name="ant_obstetricos_detalles" rows="4" cols="80"></textarea>	
			</div>
		</div>
	
		<br>		
	</div>
</form>

<div id="seccion_ant_obstetricos_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickAntecedentesObstetricos(){
		seccionPrincipal = document.getElementById("seccion_ant_obstetricos");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function checkp(){
		if(document.getElementById("ant_obstetricos_p")!= null && document.getElementById("ant_obstetricos_p").value > 0){
			mostrarLactanciaEmbarazoPrevio(false);
		} else {
			mostrarLactanciaEmbarazoPrevio(true);
		}
	}

	function guardarAntecedentesObstetricos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var ant_obstetricos_g = document.getElementById("ant_obstetricos_g").value;
		var ant_obstetricos_p = document.getElementById("ant_obstetricos_p").value;
		var ant_obstetricos_a = document.getElementById("ant_obstetricos_a").value;
		var ant_obstetricos_detalles = document.getElementById("ant_obstetricos_detalles").value;		

		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_antecedentes_obstetricos',
           data:{consulta:consulta, paciente:paciente, ant_obstetricos_g:ant_obstetricos_g, ant_obstetricos_p:ant_obstetricos_p, ant_obstetricos_a:ant_obstetricos_a, ant_obstetricos_detalles:ant_obstetricos_detalles ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
           			mostrarSnackbar("ANTECEDENTES OBSTETRICOS GUARDADOS");
            	} else {
            		mostrarSnackbar("ANTECEDENTES OBSTETRICOS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarAntecedentesObstetricosConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_obstetricos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		ocultarCamposAntecedentesObstetrico();
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_ant_obstetricos_nueva_consulta").hidden = false;
					document.getElementById("seccion_ant_obstetricos").hidden = false;
					
					document.getElementById("ant_obstetricos_g_label").hidden = false;
					document.getElementById("ant_obstetricos_g").hidden = false;				
					if(data.response_data.g == -1)
						document.getElementById("ant_obstetricos_g").value = "";
					else
						document.getElementById("ant_obstetricos_g").value = data.response_data.g;

				 	document.getElementById("ant_obstetricos_p_label").hidden = false;
					document.getElementById("ant_obstetricos_p").hidden = false;																	
					if(data.response_data.p > 0){						
						document.getElementById("ant_obstetricos_p").value = data.response_data.p;
						cargarLactanciaEmbarazoPrevioConsulta();										
					} else {						
						if(data.response_data.p == -1)
							document.getElementById("ant_obstetricos_p").value = "";
						else
							document.getElementById("ant_obstetricos_p").value = 0;
					}	          			
					
					document.getElementById("ant_obstetricos_a_label").hidden = false;
					document.getElementById("ant_obstetricos_a").hidden = false;				
					if(data.response_data.a == -1)
						document.getElementById("ant_obstetricos_a").value = "";				
					else
						document.getElementById("ant_obstetricos_a").value = data.response_data.a;				
				
					if(data.response_data.descripcion.localeCompare("") != 0){						
						document.getElementById("ant_obstetricos_detalles").hidden = false;				
						document.getElementById("ant_obstetricos_detalles").value = data.response_data.descripcion;				
					}
            	} else {
            		document.getElementById("seccion_ant_obstetricos_nueva_consulta").hidden = true;					
            	}
            }
        });	
	}

	function ocultarCamposAntecedentesObstetrico(){
		document.getElementById("ant_obstetricos_g_label").hidden = true;
		document.getElementById("ant_obstetricos_g").hidden = true;
		document.getElementById("ant_obstetricos_g").value = "";
		document.getElementById("ant_obstetricos_p_label").hidden = true;
		document.getElementById("ant_obstetricos_p").hidden = true;
		document.getElementById("ant_obstetricos_p").value = "";
		document.getElementById("ant_obstetricos_a_label").hidden = true;
		document.getElementById("ant_obstetricos_a").hidden = true;
		document.getElementById("ant_obstetricos_a").value = "";
		document.getElementById("ant_obstetricos_detalles").hidden = true;
		document.getElementById("ant_obstetricos_detalles").value = "";
	}

	function cargarAntecedentesObstetricos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("ant_obstetricos_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}
</script>