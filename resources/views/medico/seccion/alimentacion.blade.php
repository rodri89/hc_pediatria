@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual" id="seccion_alimentacion_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickAlimentacion()" type="button">Alimentación</a></b></h4>
	</div>	
@else
<form id="alimentacion_nueva_consulta">	
	<div class="row margin_left_5px">
		<p class="rodri_bold font_size_resumen">Alimentación</p>
	</div>	
@endif
	<div id="seccion_alimentacion" hidden>
		<div class="row" id="seccion_consulta_pecho">
			<div class="row margin_left_5px margin_top_5px">
				<div class="custom-control custom-checkbox margin_left_20px margin_top_5px input_width_80px">
			    	<input onclick="clickCheckPecho()" type="checkbox" class="custom-control-input" id="consulta_actual_pecho">	    
			    	<label class="custom-control-label" for="consulta_actual_pecho">Pecho</label>		    	
			  	</div>
			  	<div id="seccion_pecho_detalle" class="row margin_left_20px" hidden>				    		
		        	<textarea class="form-control width450px_cel margin_left_5px" id="consulta_actual_pecho_detalle" name="consulta_actual_pecho_detalle" rows="5" cols="60"></textarea>		        	
			    </div>

			</div>
			<p hidden id="pecho_detalle_p" class="rodri_resumen_p margin_top_10px"></p>
		</div>

		<div class="row" id="seccion_consulta_leche_maternizada">
			<div class="row margin_left_5px margin_top_5px">
				<div class="custom-control custom-checkbox margin_left_20px margin_top_5px">
			    	<input onclick="clickCheckLecheMaternizada()" type="checkbox" class="custom-control-input" id="consulta_actual_leche_maternizada">	    
			    	<label class="custom-control-label" for="consulta_actual_leche_maternizada">Leche Maternizada</label>		    	
			  	</div>
			  	<div id="seccion_leche_maternizada_detalle" class="row margin_left_20px" hidden>				    		
		        	<textarea class="form-control width450px_cel margin_left_5px" id="consulta_actual_leche_maternizada_detalle" name="consulta_actual_leche_maternizada_detalle" rows="5" cols="60"></textarea>
		        	
		        	<!--<input type="text" class="form-control input_width_250px margin_left_5px" id="consulta_actual_leche_maternizada_detalle" name="consulta_actual_leche_maternizada_detalle"  placeholder=""/> -->
			    </div>
			</div>
			<p hidden id="leche_maternizada_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_10px"></p>
		</div>

		<div class="row" id="seccion_consulta_leche_vaca">
			<div class="row margin_left_5px margin_top_5px">
				<div class="custom-control custom-checkbox margin_left_20px margin_top_5px">
			    	<input onclick="clickCheckLecheVaca()" type="checkbox" class="custom-control-input" id="consulta_actual_leche_vaca">	    
			    	<label class="custom-control-label" for="consulta_actual_leche_vaca">Leche Vaca</label>		    	
			  	</div>
			  	<div id="seccion_leche_vaca_detalle" class="row margin_left_20px" hidden>				    		
			  		<textarea class="form-control width450px_cel margin_left_5px" id="consulta_actual_leche_vaca_detalle" name="consulta_actual_leche_vaca_detalle" rows="5" cols="60"></textarea>
		        	
		        	<!--<input type="text" class="form-control input_width_250px margin_left_5px" id="consulta_actual_leche_vaca_detalle" name="consulta_actual_leche_vaca_detalle"  placeholder=""/>-->
			    </div>
			</div>
			<p hidden id="leche_vaca_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_10px"></p>
		</div>
 
		<div class="row" id="seccion_consulta_dieta">
			<div class="margin_left_20px">
				<label class="margin_left_5px margin_top_5px">Dieta</label>		  	
			  	<div class="row margin_left_20px" id="seccion_consulta_tipo">			
			  		@if($nueva_consulta == 1 || $nueva_consulta == 3)
						<label class="margin_left_5px margin_top_5px input_width_110px" for="consulta_actual_dieta">-Tipo:</label>
					@else
						<label class="margin_left_5px margin_top_5px" for="consulta_actual_dieta">-Tipo:</label>
					@endif
		        	<textarea class="form-control width450px_cel margin_left_5px" id="consulta_actual_dieta" name="consulta_actual_dieta" rows="5" cols="60"></textarea>
		        	<p hidden id="tipo_detalle_p" class="rodri_resumen_p margin_top_5px"></p>
		        	<!--<input type="text" class="form-control input_width_250px margin_left_5px" id="consulta_actual_dieta" name="consulta_actual_dieta"  placeholder=""/>-->
				</div>
				<div class="row margin_left_20px margin_top_5px" id="seccion_consulta_comida_dia">			
					<label class="margin_left_5px margin_top_5px input_width_110px" for="consulta_actual_comida_dia">-Comidas/Día:</label>
		        	<input type="number" class="form-control input_width_250px margin_left_5px" id="consulta_actual_comida_dia" name="consulta_actual_comida_dia"  placeholder=""/>
		        	<p hidden id="comida_dia_detalle_p" class="rodri_resumen_p margin_left_5px margin_top_5px"></p>
				</div>
			</div>
		</div>

		<div class="row" id="seccion_consulta_hierro">
			<div class="row margin_left_5px margin_top_5px">
				<div class="custom-control custom-checkbox margin_left_20px margin_top_5px input_width_80px">
			    	<input onclick="clickCheckHierro()" type="checkbox" class="custom-control-input" id="consulta_actual_hierro">	    
			    	<label class="custom-control-label" for="consulta_actual_hierro">Hierro</label>		    	
			  	</div>
			  	<div id="seccion_hierro_dosis" class="row margin_left_20px" hidden>			
		    		<label class="margin_left_5px margin_top_5px" for="consulta_actual_comida_dia">Dosis:</label>
		        	<input type="text" class="form-control input_width_250px margin_left_5px" id="consulta_actual_hierro_dosis" name="consulta_actual_hierro_dosis"  placeholder=""/>
		        	<p hidden id="hierro_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_5px"></p>
			    </div>
			</div>
		</div>

		<div class="row" id="seccion_consulta_vitamina">		
			<div class="row margin_left_5px margin_top_5px">
				<div class="custom-control custom-checkbox margin_left_20px margin_top_5px input_width_80px">
			    	<input onclick="clickCheckVitamina()" type="checkbox" class="custom-control-input" id="consulta_actual_vitamina">	    
			    	<label class="custom-control-label" for="consulta_actual_vitamina">Vitaminas</label>		    	
			  	</div>
			  	<div id="seccion_vitamina_dosis" class="row margin_left_20px" hidden>			
		    		<label class="margin_left_5px margin_top_5px" for="consulta_actual_comida_dia">Dosis:</label>
		        	<input type="text" class="form-control input_width_250px margin_left_5px" id="consulta_actual_vitamina_dosis" name="consulta_actual_vitamina_dosis"  placeholder=""/>
		        	<p hidden id="vitamina_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_5px"></p>
			    </div>		  	
			</div>
		</div>

		<div hidden class="row margin_left_5px margin_top_5px" id="seccion_consulta_catarsis">
			@if($nueva_consulta == 1 || $nueva_consulta == 3)
				<label class="margin_left_5px margin_top_5px input_width_150px" for="consulta_actual_catarsis">Catarsis/Diuresis:</label>
			@else
				<label class="margin_left_5px margin_top_5px" for="consulta_actual_catarsis">Catarsis/Diuresis:</label>
			@endif
	    	<textarea class="form-control width450px margin_left_5px" id="consulta_actual_catarsis" name="consulta_actual_catarsis" rows="5" cols="60"></textarea>
	    	<p hidden id="catarsis_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_5px"></p>
	    	<!--<input type="text" class="form-control input_width_250px" id="consulta_actual_catarsis" name="consulta_actual_catarsis"  placeholder=""/>-->
		</div>

		<div hidden class="row margin_left_5px margin_top_5px" id="seccion_consulta_somnia">			
			@if($nueva_consulta == 1 || $nueva_consulta == 3)
				<label class="margin_left_5px margin_top_5px input_width_150px" for="consulta_actual_somnia">Somnia:</label>
			@else
				<label class="margin_left_5px margin_top_5px" for="consulta_actual_somnia">Somnia:</label>
			@endif
	    	<textarea class="form-control width450px margin_left_5px" id="consulta_actual_somnia" name="consulta_actual_somnia" rows="5" cols="60"></textarea>
	    	<p hidden id="somnia_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_5px"></p>
	    	<!--<input type="text" class="form-control input_width_250px" id="consulta_actual_somnia" name="consulta_actual_somnia"  placeholder=""/>-->
		</div>

		<!--<div class="row contenedor3">
	    	<button onclick="guardarDatos()" class="rodri_button contenido3">GUARDAR</button>
		</div>
		<br>-->
	</div>
</form>

<script type="text/javascript">
	
	function agregarDetalleNuevaConsulta(text){

	}

	function clickAlimentacion(){
		seccionAlimentacion = document.getElementById("seccion_alimentacion");
	//		alert(seccionAlimentacion);
		if(seccionAlimentacion.hidden == true){
			seccionAlimentacion.hidden = false;			
		}
		else {
			seccionAlimentacion.hidden = true;
		}
	}

	function clickCheckHierro(){
		hierroCheck = document.getElementById("consulta_actual_hierro").checked;
		if(hierroCheck){
			document.getElementById("seccion_hierro_dosis").hidden = false;
		} else {
			document.getElementById("seccion_hierro_dosis").hidden = true;
			document.getElementById("consulta_actual_hierro_dosis").value = "";
		}
	}

	function clickCheckVitamina(){
		vitaminaCheck = document.getElementById("consulta_actual_vitamina").checked;
		if(vitaminaCheck){
			document.getElementById("seccion_vitamina_dosis").hidden = false;
		} else {
			document.getElementById("seccion_vitamina_dosis").hidden = true;
			document.getElementById("consulta_actual_vitamina_dosis").value = "";
		}
	}

	function clickCheckPecho(){
		pechoCheck = document.getElementById("consulta_actual_pecho").checked;
		if(pechoCheck){
			document.getElementById("seccion_pecho_detalle").hidden = false;			
		} else {			
			document.getElementById("seccion_pecho_detalle").hidden = true;
			document.getElementById("consulta_actual_pecho_detalle").value = "";
		}
	}

	function clickCheckLecheMaternizada(){
		lecheMaternizadaCheck = document.getElementById("consulta_actual_leche_maternizada").checked;
		if(lecheMaternizadaCheck){
			document.getElementById("seccion_leche_maternizada_detalle").hidden = false;			
		} else {			
			document.getElementById("seccion_leche_maternizada_detalle").hidden = true;
			document.getElementById("consulta_actual_leche_maternizada_detalle").value = "";
		}
	}

	function clickCheckLecheVaca(){
		lecheVacaCheck = document.getElementById("consulta_actual_leche_vaca").checked;
		if(lecheVacaCheck){
			document.getElementById("seccion_leche_vaca_detalle").hidden = false;			
		} else {			
			document.getElementById("seccion_leche_vaca_detalle").hidden = true;
			document.getElementById("consulta_actual_leche_vaca_detalle").value = "";
		}
	}

	function guardarAlimentacion(){		
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		var pecho = 2;
		var pecho_detalle = "";
		var pecho_check = document.getElementById("consulta_actual_pecho").checked;
		if(pecho_check){
			pecho = 1;
			var pecho_input = document.getElementById("consulta_actual_pecho_detalle");
			if(pecho_input != null) {				
				pecho_detalle = pecho_input.value;		
			}
		} else {
			pecho = 0;
		}

		var leche_maternizada = 2;
		var leche_maternizada_detalle = "";
		var leche_maternizada_check = document.getElementById("consulta_actual_leche_maternizada").checked;
		if(leche_maternizada_check){
			leche_maternizada = 1;
			var leche_maternizada_input = document.getElementById("consulta_actual_leche_maternizada_detalle");
			if(leche_maternizada_input != null) {				
				leche_maternizada_detalle = leche_maternizada_input.value;		
			}
		} else {
			leche_maternizada = 0;
		}

		var leche_vaca = 2;
		var leche_vaca_detalle = "";
		var leche_vaca_check = document.getElementById("consulta_actual_leche_vaca").checked;
		if(leche_vaca_check){
			leche_vaca = 1;
			var leche_vaca_input = document.getElementById("consulta_actual_leche_vaca_detalle");
			if(leche_vaca_input != null) {				
				leche_vaca_detalle = leche_vaca_input.value;		
			}
		} else {
			leche_vaca = 0;
		}

		var dieta_tipo = document.getElementById("consulta_actual_dieta").value;
		var dieta_comidas = document.getElementById("consulta_actual_comida_dia").value;

		var hierro = 2;
		var hierro_dosis = "";
		var hierro_check = document.getElementById("consulta_actual_hierro").checked;
		if(hierro_check){
			hierro = 1;
			var hierro_input = document.getElementById("consulta_actual_hierro_dosis");
			if(hierro_input != null) {				
				hierro_dosis = hierro_input.value;		
			}
		} else {
			hierro = 0;
		}

		var vitamina = 2;
		var vitamina_dosis = "";
		var vitamina_check = document.getElementById("consulta_actual_vitamina").checked;
		if(vitamina_check){
			vitamina = 1;
			var vitamina_input = document.getElementById("consulta_actual_vitamina_dosis");
			if(vitamina_input != null) {				
				vitamina_dosis = vitamina_input.value;		
			}
		} else {
			vitamina = 0;
		}

		var catarsis = document.getElementById("consulta_actual_catarsis").value;
		var somnia = document.getElementById("consulta_actual_somnia").value;

		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_alimentacion',
           data:{consulta:consulta, paciente:paciente, pecho:pecho, pecho_detalle:pecho_detalle, leche_maternizada:leche_maternizada, leche_maternizada_detalle:leche_maternizada_detalle, leche_vaca:leche_vaca, leche_vaca_detalle:leche_vaca_detalle, dieta_tipo:dieta_tipo, dieta_comidas:dieta_comidas, hierro:hierro, hierro_dosis:hierro_dosis, vitamina:vitamina, vitamina_dosis:vitamina_dosis, catarsis:catarsis, somnia:somnia ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ALIMENTACION FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}
	
	function cargarAlimentacion(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_alimentacion',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           			
           			if(data.response_data.pecho == 1){
           				document.getElementById("consulta_actual_pecho").checked = true;
         				document.getElementById("seccion_pecho_detalle").hidden = false;  				
         				document.getElementById("consulta_actual_pecho_detalle").value = data.response_data.pecho_detalle;
           			} 

           			if(data.response_data.leche_maternizada == 1){
           				document.getElementById("consulta_actual_leche_maternizada").checked = true;
         				document.getElementById("seccion_leche_maternizada_detalle").hidden = false;  				
         				document.getElementById("consulta_actual_leche_maternizada_detalle").value = data.response_data.leche_maternizada_detalle;
           			} 

           			if(data.response_data.leche_vaca == 1){
           				document.getElementById("consulta_actual_leche_vaca").checked = true;
         				document.getElementById("seccion_leche_vaca_detalle").hidden = false;  				
         				document.getElementById("consulta_actual_leche_vaca_detalle").value = data.response_data.leche_vaca_detalle;
           			} 

           			document.getElementById("consulta_actual_dieta").value = data.response_data.dieta_tipo;
           			document.getElementById("consulta_actual_comida_dia").value = data.response_data.dieta_comidas;
           			document.getElementById("consulta_actual_catarsis").value = data.response_data.catarsis;
           			document.getElementById("consulta_actual_somnia").value = data.response_data.somnia;
					
					if(data.response_data.hierro == 1){
           				document.getElementById("consulta_actual_hierro").checked = true;
         				document.getElementById("seccion_hierro_dosis").hidden = false;  				
         				document.getElementById("consulta_actual_hierro_dosis").value = data.response_data.hierro_dosis;
           			} 

           			if(data.response_data.vitamina == 1){
           				document.getElementById("consulta_actual_vitamina").checked = true;
         				document.getElementById("seccion_vitamina_dosis").hidden = false;  				
         				document.getElementById("consulta_actual_vitamina_dosis").value = data.response_data.vitamina_dosis;
           			}           					

           			document.getElementById("seccion_alimentacion").hidden = false;	
            	} else {
            		//mostrarSanckbar("ALIMENTACION FALLO");            		
            	}
            }
        });	
	}

	function cargarAlimentacionConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;	
	//	borrarCamposAlimentacion();		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_alimentacion',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         	
           		var seccion_alimentacion = document.getElementById("seccion_alimentacion");
           		var seccion_alimentacion_titulo = document.getElementById("alimentacion_nueva_consulta");
           		seccion_alimentacion_titulo.hidden = true;
           		seccion_alimentacion.hidden = true;
           		if(data.response_data != null && data.response == 1){           			
           			if(data.response_data.pecho == 1){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
						var seccion_pecho_detalle = document.getElementById("seccion_pecho_detalle");           				
           				document.getElementById("seccion_consulta_pecho").hidden = false; 
           				document.getElementById("consulta_actual_pecho").checked = true;
         				document.getElementById("seccion_pecho_detalle").hidden = false;  				
         				document.getElementById("consulta_actual_pecho_detalle").hidden = true;
         				document.getElementById("pecho_detalle_p").hidden = false;
         				document.getElementById("pecho_detalle_p").innerHTML = data.response_data.pecho_detalle;         				         	
           			} else {
           				document.getElementById("seccion_consulta_pecho").hidden = true;  	
           				document.getElementById("pecho_detalle_p").hidden = true;
           			}

           			if(data.response_data.leche_maternizada == 1){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
           				document.getElementById("consulta_actual_leche_maternizada").checked = true;
         				document.getElementById("seccion_leche_maternizada_detalle").hidden = false;  	
         				document.getElementById("seccion_consulta_leche_maternizada").hidden = false;
         				document.getElementById("consulta_actual_leche_maternizada_detalle").hidden = true;  	
           				document.getElementById("leche_maternizada_detalle_p").hidden = false;
         				document.getElementById("leche_maternizada_detalle_p").innerHTML = data.response_data.leche_maternizada_detalle;
           			} else {
           				document.getElementById("seccion_consulta_leche_maternizada").hidden = true;  	
           				document.getElementById("leche_maternizada_detalle_p").hidden = true;
           			}

           			if(data.response_data.leche_vaca == 1){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
           				document.getElementById("consulta_actual_leche_vaca").checked = true;
         				document.getElementById("seccion_leche_vaca_detalle").hidden = false;  	
         				document.getElementById("consulta_actual_leche_vaca_detalle").hidden = true;	
         				document.getElementById("seccion_consulta_leche_vaca").hidden = false;  	
           				document.getElementById("leche_vaca_detalle_p").hidden = false;		
         				document.getElementById("leche_vaca_detalle_p").innerHTML = data.response_data.leche_vaca_detalle;
           			} else {
           				document.getElementById("seccion_consulta_leche_vaca").hidden = true;  	
           				document.getElementById("leche_vaca_detalle_p").hidden = true;
           			}

           			document.getElementById("seccion_consulta_dieta").hidden = true;
           			document.getElementById("consulta_actual_dieta").hidden = true;
           			if(data.response_data.dieta_tipo!=null && data.response_data.dieta_tipo.localeCompare('')!=0){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
           				document.getElementById("tipo_detalle_p").innerHTML = data.response_data.dieta_tipo;
           				document.getElementById("tipo_detalle_p").hidden = false; 
           				document.getElementById("seccion_consulta_dieta").hidden = false;
           				document.getElementById("seccion_consulta_tipo").hidden = false;
           			} else {
           				document.getElementById("seccion_consulta_tipo").hidden = true;
           				document.getElementById("tipo_detalle_p").hidden = true;
           			}

           			if(data.response_data.dieta_comidas!=null && data.response_data.dieta_comidas.localeCompare('')!=0){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
						document.getElementById("seccion_consulta_comida_dia").hidden = false;
						document.getElementById("comida_dia_detalle_p").innerHTML = data.response_data.dieta_comidas;
						document.getElementById("comida_dia_detalle_p").hidden = false;
						document.getElementById("consulta_actual_comida_dia").hidden = true;
           			} else {
           				document.getElementById("comida_dia_detalle_p").hidden = true;
           				document.getElementById("seccion_consulta_comida_dia").hidden = true;
           			}
           			
           			if(data.response_data.catarsis!=null && data.response_data.catarsis.localeCompare('')!=0){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
						document.getElementById("seccion_consulta_catarsis").hidden = false;
						document.getElementById("catarsis_detalle_p").innerHTML = data.response_data.catarsis;
						document.getElementById("catarsis_detalle_p").hidden = false;
						document.getElementById("consulta_actual_catarsis").hidden = true;
           			} else {
           				document.getElementById("catarsis_detalle_p").hidden = true;
           				document.getElementById("seccion_consulta_catarsis").hidden = true;
           			}

           			if(data.response_data.somnia!=null && data.response_data.somnia.localeCompare('')!=0){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
						document.getElementById("seccion_consulta_somnia").hidden = false;
						document.getElementById("somnia_detalle_p").innerHTML = data.response_data.somnia;
						document.getElementById("somnia_detalle_p").hidden = false;
						document.getElementById("consulta_actual_somnia").hidden = true;
           			} else {
           				document.getElementById("somnia_detalle_p").hidden = true;
           				document.getElementById("seccion_consulta_somnia").hidden = true;
           			}           			           			
					
					if(data.response_data.hierro == 1){
						seccion_alimentacion.hidden = false;
						seccion_alimentacion_titulo.hidden = false;
						document.getElementById("seccion_consulta_hierro").hidden = false; 
           				document.getElementById("consulta_actual_hierro").checked = true;
           				if(data.response_data.hierro_dosis.localeCompare("")!=0){           					
	         				document.getElementById("seccion_hierro_dosis").hidden = false;  	
	         				document.getElementById("consulta_actual_hierro_dosis").hidden = true;	
	         				document.getElementById("hierro_detalle_p").hidden = false;			
	         				document.getElementById("hierro_detalle_p").innerHTML = data.response_data.hierro_dosis;
         				} else {
         					document.getElementById("seccion_hierro_dosis").hidden = true;  	
	         				document.getElementById("consulta_actual_hierro_dosis").hidden = false;	
	         				document.getElementById("hierro_detalle_p").hidden = true;			
	         				document.getElementById("hierro_detalle_p").innerHTML = "";
         				}
           			} else {
						document.getElementById("seccion_consulta_hierro").hidden = true;
						document.getElementById("hierro_detalle_p").hidden = true;	
           			}

           			if(data.response_data.vitamina == 1){
           				seccion_alimentacion.hidden = false;
           				seccion_alimentacion_titulo.hidden = false;
           				document.getElementById("consulta_actual_vitamina").checked = true;
           				if(data.response_data.vitamina_dosis.localeCompare("")!=0){
           					document.getElementById("seccion_vitamina_dosis").hidden = false;  				
	         				document.getElementById("consulta_actual_vitamina_dosis").hidden = true;
	         				document.getElementById("vitamina_detalle_p").innerHTML = data.response_data.vitamina_dosis;
	         				document.getElementById("seccion_consulta_vitamina").hidden = false;
							document.getElementById("vitamina_detalle_p").hidden = false;	
           				} else {
           					document.getElementById("seccion_vitamina_dosis").hidden = true;  				
	         				document.getElementById("consulta_actual_vitamina_dosis").hidden = false;
	         				document.getElementById("vitamina_detalle_p").innerHTML = "";
	         				document.getElementById("seccion_consulta_vitamina").hidden = true;
							document.getElementById("vitamina_detalle_p").hidden = true;	
           				}
           			} else {
						document.getElementById("seccion_consulta_vitamina").hidden = true;
						document.getElementById("vitamina_detalle_p").hidden = true;	
           			}           					           			
            	} else {
            		//mostrarSanckbar("ALIMENTACION FALLO");            		
            	}
            }
        });	
	}

	function borrarCamposAlimentacion(){
		document.getElementById("hierro_detalle_p").innerHTML = "";
		document.getElementById("vitamina_detalle_p").innerHTML = "";
		document.getElementById("seccion_vitamina_dosis").hidden = true;
		document.getElementById("seccion_hierro_dosis").hidden = true;

		document.getElementById("tipo_detalle_p").innerHTML = "";
		document.getElementById("consulta_actual_pecho").checked = false;
		document.getElementById("pecho_detalle_p").innerHTML = "";
		document.getElementById("consulta_actual_leche_maternizada").checked = false;
		document.getElementById("leche_maternizada_detalle_p").innerHTML = "";
		document.getElementById("consulta_actual_leche_vaca").checked = false;
		document.getElementById("leche_vaca_detalle_p").innerHTML = "";
		document.getElementById("consulta_actual_dieta").innerHTML = "";
		document.getElementById("comida_dia_detalle_p").innerHTML = "";
		document.getElementById("consulta_actual_hierro").checked = false;
		document.getElementById("consulta_actual_hierro_dosis").innerHTML = "";
		document.getElementById("consulta_actual_vitamina").checked = false;
		document.getElementById("consulta_actual_vitamina_dosis").innerHTML = "";
	}

</script>