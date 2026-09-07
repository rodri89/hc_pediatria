<input hidden id="examen_fisico_tipo_consulta" value="{{$consulta->tipo_consulta}}"/>
@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickExamenFisico()" type="button">Examen Físico</a></b></h4>
	</div>	
@else
<form id="examen_fisico_nueva_consulta">	
	<div class="row margin_left_5px" id="examen_fisico_texto">
		<p class="rodri_bold font_size_resumen">Examen Físico</p>
	</div>
@endif
	<div id="seccion_examen_fisico" hidden>		
		<div class="row">
		 <!-- Solo debo mostrarlo si es una consulta de tipo Control de Salud-->
		<div id="seccion_examen_fisico_numeros" class="col-md-6">		
			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_peso">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_peso">Peso:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_peso" name="examen_fisico_peso"  placeholder=""/>
		    	<label class=" margin_top_5px margin_left_5px input_width_60px">kg.</label>
		    	<label class=" margin_top_5px margin_left_30px input_width_80px margin_top_3px_solo_cel" for="examen_fisico_percentil_peso" id="examen_fisico_percentil_peso_texto">Percentil</label>
		    	<input type="text" class="form-control input_width_80px margin_top_3px_solo_cel" id="examen_fisico_percentil_peso" name="examen_fisico_percentil_peso"  placeholder=""/>
			</div> 

			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_talla">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_talla">Talla:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_talla" name="examen_fisico_talla"  placeholder=""/>
		    	<label class=" margin_top_5px margin_left_5px input_width_60px">mts.</label>
		    	<label class=" margin_top_5px margin_left_30px input_width_80px margin_top_3px_solo_cel" for="examen_fisico_percentil_talla" id="examen_fisico_percentil_talla_texto">Percentil</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_80px margin_top_3px_solo_cel" id="examen_fisico_percentil_talla" name="examen_fisico_percentil_talla"  placeholder=""/>
			</div> 
					
			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_pc">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_pc">PC:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_pc" name="examen_fisico_pc"  placeholder=""/>
		    	<label class=" margin_top_5px margin_left_5px input_width_60px">cm.</label>
		    	<label class=" margin_top_5px margin_left_30px input_width_80px margin_top_3px_solo_cel" for="examen_fisico_percentil_pc" id="examen_fisico_percentil_pc_texto">Percentil</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_80px margin_top_3px_solo_cel" id="examen_fisico_percentil_pc" name="examen_fisico_percentil_pc"  placeholder=""/>
			</div> 
			
			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_ipd">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_ipd">IPD:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_ipd" name="examen_fisico_ipd"  placeholder=""/>
		    	<label class=" margin_top_5px margin_left_5px input_width_60px">gr/dia.</label>		    	
			</div>

			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_ta">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_ta">TA:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_ta" name="examen_fisico_ta"  placeholder=""/>
		    	<label class=" margin_top_5px margin_left_5px input_width_60px">mm HG.</label>		    	
			</div>

			<div class="row margin_left_5px margin_top_5px" id="seccion_examen_fisico_imc">			
				<label class=" margin_top_5px input_width_50px" for="examen_fisico_imc">IMC:</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_110px" id="examen_fisico_imc" name="examen_fisico_imc"  placeholder=""/>
		    	<button id="boton_calcular_imc" type="button" onclick="calcularIMC()" class="rodri_button_aceptar margin_left_5px">Calcular</button>
		    	<label class=" margin_top_5px margin_left_30px input_width_80px margin_top_3px_solo_cel" for="examen_fisico_percentil_imc" id="examen_fisico_percentil_imc_texto">Percentil</label>
		    	<input type="text" step=".01" min="0" class="form-control input_width_80px margin_top_3px_solo_cel" id="examen_fisico_percentil_imc" name="examen_fisico_percentil_imc"  placeholder=""/>
			</div> 
		
		</div>		
		<div class="col-md-6">		
			<div id="examen_fisico_subtitulo">
				<label>Examen Físico:</label>			
			</div>
			@if($nueva_consulta == 1 || $nueva_consulta == 3)
				@if($consulta->tipo_consulta == 1)					
					<textarea class="width450px_cel margin_top_5pxcel form-control" id="examen_fisico_nota" name="examen_fisico_nota" rows="10" cols="80"></textarea>	
				@else
					<textarea class="width650px_cel margin_top_5pxcel form-control" id="examen_fisico_nota" name="examen_fisico_nota" rows="10" cols="80"></textarea>	
				@endif
			@else
				<p hidden id="examen_fisico_detalle_p" class="rodri_resumen_p margin_left_10px margin_top_5px"></p>	
			@endif
		</div>
	</div>
		<br>
		<!--<div class="row contenedor3">
	    	<button onclick="guardarDatos()" class="rodri_button contenido3">GUARDAR</button>
		</div>
		<br> -->
	</div>

</form>

<script type="text/javascript">
	
	function clickExamenFisico(){
		seccionPrincipal = document.getElementById("seccion_examen_fisico");	
		var tipo_consulta = document.getElementById("examen_fisico_tipo_consulta").value;
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
		if(tipo_consulta == 1){
			document.getElementById("seccion_examen_fisico_numeros").hidden = false;
			document.getElementById("examen_fisico_subtitulo").hidden = false;
		} else {
			document.getElementById("examen_fisico_subtitulo").hidden = true;			
			document.getElementById("seccion_examen_fisico_numeros").hidden = true;
		}
	}

	//peso/talla al cuadrado

	function calcularIMC(){
		var peso = document.getElementById("examen_fisico_peso").value;
		var talla = document.getElementById("examen_fisico_talla").value;
		if(peso.includes(',')){
			peso = peso.replace(',','.');
		}
		if(talla.includes(',')){
			talla = talla.replace(',','.');
		}
		talla = talla * talla;
		var imc_aux = peso / talla;
		imc_aux = trunc(imc_aux, 2);
		document.getElementById("examen_fisico_imc").value = imc_aux;		
	}

	function trunc (x, posiciones = 0) {
	  var s = x.toString()
	  var l = s.length
	  var decimalLength = s.indexOf('.') + 1
	  var numStr = s.substr(0, decimalLength + posiciones)
	  return Number(numStr)
	}

	function guardarExamenFisico(){		
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		var tipo_consulta = document.getElementById("examen_fisico_tipo_consulta").value;

		if(tipo_consulta == 1){
			var peso = document.getElementById("examen_fisico_peso").value;
			var peso_percentil = document.getElementById("examen_fisico_percentil_peso").value;

			var talla = document.getElementById("examen_fisico_talla").value;
			var talla_percentil = document.getElementById("examen_fisico_percentil_talla").value;

			var pc = document.getElementById("examen_fisico_pc").value;
			var pc_percentil = document.getElementById("examen_fisico_percentil_pc").value;

			var ipd = document.getElementById("examen_fisico_ipd").value;
			var ta = document.getElementById("examen_fisico_ta").value;

			var imc = document.getElementById("examen_fisico_imc").value;
			var imc_percentil = document.getElementById("examen_fisico_percentil_imc").value;
		}
		var nota = document.getElementById("examen_fisico_nota").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_examen_fisico',
           data:{consulta:consulta, paciente:paciente, peso:peso, peso_percentil:peso_percentil, talla:talla, talla_percentil:talla_percentil, pc:pc, pc_percentil:pc_percentil, ipd:ipd, ta:ta, imc:imc, imc_percentil:imc_percentil, nota:nota ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("EXAMEN FISICO FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarExamenFisico(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examen_fisico',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           			
           			document.getElementById("examen_fisico_nota").value = data.response_data.nota;           			
           			document.getElementById("examen_fisico_peso").value =data.response_data.peso;
					document.getElementById("examen_fisico_percentil_peso").value = data.response_data.peso_percentil;
					document.getElementById("examen_fisico_talla").value = data.response_data.talla;
					document.getElementById("examen_fisico_percentil_talla").value = data.response_data.talla_percentil;
					document.getElementById("examen_fisico_pc").value = data.response_data.pc;
					document.getElementById("examen_fisico_percentil_pc").value = data.response_data.pc_percentil;
					document.getElementById("examen_fisico_ipd").value = data.response_data.ipd;
					document.getElementById("examen_fisico_ta").value = data.response_data.ta;
					document.getElementById("examen_fisico_imc").value = data.response_data.imc;
					document.getElementById("examen_fisico_percentil_imc").value = data.response_data.imc_percentil;
           			document.getElementById("seccion_examen_fisico").hidden = false;	
            	} else {
            		//mostrarSanckbar("EXAMEN FISICO FALLO");            		
            	}
            }
        });	
	}

	function cargarExamenFisicoConsulta(tipo_consulta){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examen_fisico',
           data:{consulta:consulta, paciente:paciente, tipo_consulta:tipo_consulta,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           			           			
           			document.getElementById("examen_fisico_texto").hidden = false;         
           			document.getElementById("examen_fisico_detalle_p").hidden = false;   
           			document.getElementById("seccion_examen_fisico").hidden = false;        			           			 
           			document.getElementById("seccion_examen_fisico_numeros").hidden = true;
           			document.getElementById("examen_fisico_nueva_consulta").hidden = false;
           			mostrarSecciones();     
           			if(data.tipo_consulta == 1) {           				
           				ocultarSecciones(data);
           				document.getElementById("seccion_examen_fisico_numeros").hidden = false;
           				document.getElementById("examen_fisico_subtitulo").hidden = false;	
	           			document.getElementById("examen_fisico_peso").value = data.response_data.peso;	           			
						document.getElementById("examen_fisico_percentil_peso").value = data.response_data.peso_percentil;
						document.getElementById("examen_fisico_talla").value = data.response_data.talla;
						document.getElementById("examen_fisico_percentil_talla").value = data.response_data.talla_percentil;
						document.getElementById("examen_fisico_pc").value = data.response_data.pc;
						document.getElementById("examen_fisico_percentil_pc").value = data.response_data.pc_percentil;
						document.getElementById("examen_fisico_ipd").value = data.response_data.ipd;
						document.getElementById("examen_fisico_ta").value = data.response_data.ta;
						document.getElementById("examen_fisico_imc").value = data.response_data.imc;
						document.getElementById("examen_fisico_percentil_imc").value = data.response_data.imc_percentil;	           			
	           			document.getElementById("boton_calcular_imc").hidden = true;	           			       
           			} else {
           				document.getElementById("examen_fisico_subtitulo").hidden = true;
           			}
           			if(data.response_data.nota.localeCompare("")==0){
           				document.getElementById("examen_fisico_subtitulo").hidden = true;
           				document.getElementById("examen_fisico_detalle_p").innerHTML = "";            			
           			} else {
           				if(data.tipo_consulta == 1){
           					document.getElementById("examen_fisico_subtitulo").hidden = false;
           				} else {
           					document.getElementById("examen_fisico_subtitulo").hidden = true;
           				}           				
           				document.getElementById("examen_fisico_detalle_p").innerHTML = data.response_data.nota;            			
           			}
            	} else {
            		document.getElementById("examen_fisico_texto").hidden = true;         
            		document.getElementById("examen_fisico_nueva_consulta").hidden = true;
            		document.getElementById("seccion_examen_fisico").hidden = true;
            	}
            }
        });	
	}

	// oculta las secciones de aquellos valores que son 0 o estan vacios. o no fueron completados.
	function ocultarSecciones(data){
		if(data.response_data.peso_percentil.localeCompare("")==0 || data.response_data.peso_percentil.localeCompare("0.00")==0){
			document.getElementById("examen_fisico_percentil_peso_texto").hidden = true;
			document.getElementById("examen_fisico_percentil_peso").hidden = true;			
		}
		if(data.response_data.talla_percentil.localeCompare("")==0 || data.response_data.talla_percentil.localeCompare("0.00")==0){
			document.getElementById("examen_fisico_percentil_talla_texto").hidden = true;
			document.getElementById("examen_fisico_percentil_talla").hidden = true;
		}
		if(data.response_data.pc_percentil.localeCompare("")==0 || data.response_data.pc_percentil.localeCompare("0.00")==0){
			document.getElementById("examen_fisico_percentil_pc_texto").hidden = true;
			document.getElementById("examen_fisico_percentil_pc").hidden = true;
		}
		if(data.response_data.imc_percentil.localeCompare("")==0 || data.response_data.imc_percentil.localeCompare("0.00")==0){
			document.getElementById("examen_fisico_percentil_imc_texto").hidden = true;
			document.getElementById("examen_fisico_percentil_imc").hidden = true;
		}
		if(data.response_data.peso.localeCompare("")==0 || data.response_data.peso.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_peso").hidden = true;
		}
		if(data.response_data.talla.localeCompare("")==0 || data.response_data.talla.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_talla").hidden = true;
		}
		if(data.response_data.pc.localeCompare("")==0 || data.response_data.pc.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_pc").hidden = true;
		}
		if(data.response_data.ipd.localeCompare("")==0 || data.response_data.ipd.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_ipd").hidden = true;
		}
		if(data.response_data.ta.localeCompare("")==0 || data.response_data.ta.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_ta").hidden = true;
		}
		if(data.response_data.imc.localeCompare("")==0 || data.response_data.imc.localeCompare("0.00")==0){
			document.getElementById("seccion_examen_fisico_imc").hidden = true;
		}
	}

	function mostrarSecciones(){
		document.getElementById("examen_fisico_percentil_peso_texto").hidden = false;
		document.getElementById("examen_fisico_percentil_peso").hidden = false;
		document.getElementById("examen_fisico_percentil_talla_texto").hidden = false;
		document.getElementById("examen_fisico_percentil_talla").hidden = false;
		document.getElementById("examen_fisico_percentil_pc_texto").hidden = false;
		document.getElementById("examen_fisico_percentil_pc").hidden = false;
		document.getElementById("examen_fisico_percentil_imc_texto").hidden = false;
		document.getElementById("examen_fisico_percentil_imc").hidden = false;
		document.getElementById("seccion_examen_fisico_peso").hidden = false;
		document.getElementById("seccion_examen_fisico_talla").hidden = false;
		document.getElementById("seccion_examen_fisico_pc").hidden = false;
		document.getElementById("seccion_examen_fisico_ipd").hidden = false;
		document.getElementById("seccion_examen_fisico_ta").hidden = false;
		document.getElementById("seccion_examen_fisico_imc").hidden = false;
	}
</script>