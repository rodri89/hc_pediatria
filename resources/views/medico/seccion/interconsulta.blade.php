@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickInterconsulta()" type="button">Interconsulta</a></b></h4>
	</div>
@else	
<form id="interconsulta_nueva_consulta">	
	<div class="row margin_left_5px">
		<p class="rodri_bold font_size_resumen">Interconsulta</p>
	</div>
@endif
	<div id="seccion_interconsulta" hidden>		
		<input hidden id="interconsulta_numero" name="interconsulta_numero"/>
		<input hidden id="interconsulta_numero_consulta" name="interconsulta_numero_consulta"/>
		<div>				
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
				<div id="seccion_interconsulta_respuesta" class="row margin_left_5px">
					<label>Respuesta:</label>			
				</div>
				@if($nueva_consulta == 1 || $nueva_consulta == 3)
					<textarea class="form-control width650px_cel margin_left_5px" id="interconsulta_respuesta" name="interconsulta_respuesta" rows="8" cols="60"></textarea>			
				@else
					<textarea class="form-control width650px_cel margin_left_5px" id="interconsulta_respuesta" name="interconsulta_respuesta" rows="4" cols="60"></textarea>			
				@endif
				
		</div>	

		<br>
		<div class="row contenedor3">
			<div class="contenido3">
		    	<button type="button" onclick="interconsultaAnteriorSiguiente(0)" class="rodri_button_aceptar_cel margin_left_cel4"><</button>
		    	<input id="interconsulta_actual_cantidad" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
		    	<button type="button" onclick="interconsultaAnteriorSiguiente(1)" class="rodri_button_aceptar_cel">></button>
		    	<button id="nuevaInterconsultaButtonId" type="button" onclick="nuevaInterconsulta()" class="rodri_button margin_left_20px margin_left_50px_solo_cel">NUEVA</button>
		    	<button id="guardarDatosInterconsultaButtonId" type="button" onclick="guardarDatosInterconsulta()" class="rodri_button margin_left_20px">GUARDAR</button>
			</div>
		</div>
		<br>
	</div>
</form>

<script type="text/javascript">
	
	function clickInterconsulta(){
		seccionPrincipal = document.getElementById("seccion_interconsulta");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;	
			var f = new Date();
			var fecha = document.getElementById("interconsulta_fecha_solicito");
			fecha.value = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
			//document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());					
			cargarInterconsulta();
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}	

	function cargarInterconsulta() {
		var paciente = document.getElementById("paciente_id").value;	
		var consulta_actual = document.getElementById("consulta_id").value;	
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_numero_interconsulta',
           data:{paciente:paciente, consulta_actual:consulta_actual ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.numero != null && data.interconsulta != null && data.response == 1){           			
           			document.getElementById("interconsulta_numero").value = data.numero;
           			if(data.numero != 0){
           				document.getElementById("seccion_interconsulta").hidden = false;	           			
	        			document.getElementById("interconsulta_especialista").value = data.interconsulta.especialista;
	        			
	        			var fecha_aux = data.interconsulta.fechaSolicitud.split("-");
	        			var fecha_mostrar = fecha_aux[2]+"/"+fecha_aux[1]+"/"+fecha_aux[0];
	        			document.getElementById("interconsulta_fecha_solicito").value = fecha_mostrar;

						document.getElementById("interconsulta_solicito").value = data.interconsulta.solicito;
						document.getElementById("interconsulta_respuesta").value = data.interconsulta.respuesta;
						var valor = data.numero+"/"+data.numero;
	           			document.getElementById("interconsulta_actual_cantidad").value = valor;	
	           			//interconsultasReadOnlyCheck();	   			
           			}
           		}
            }
        });	
	}

	function cargarInterconsultaConsulta() {
		var paciente = document.getElementById("paciente_id").value;	
		var consulta = document.getElementById("consulta_id").value;
		document.getElementById("nuevaInterconsultaButtonId").hidden = true;
		document.getElementById("guardarDatosInterconsultaButtonId").hidden = true;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_numero_interconsulta',
           data:{paciente:paciente, consulta:consulta ,_token: '{{csrf_token()}}'},
           	success:function(data) {           	      
           		if(data.numero != null && data.interconsulta != null && data.response == 1){  
           			if((consulta >= data.interconsulta.consulta_respuesta) && (data.interconsulta.consulta_respuesta !=0)){           				
           				document.getElementById("seccion_interconsulta_respuesta").hidden = false;
           				document.getElementById("interconsulta_respuesta").hidden = false;           				
           			} else {           			
           				document.getElementById("seccion_interconsulta_respuesta").hidden = true;
           				document.getElementById("interconsulta_respuesta").hidden = true;           				
           			}
           			document.getElementById("interconsulta_numero").value = data.numero;
           			document.getElementById("interconsulta_numero_consulta").value = data.numero;           			
           			if(data.numero != 0){
           				document.getElementById("seccion_interconsulta").hidden = false;	           			
	        			document.getElementById("interconsulta_nueva_consulta").hidden = false;
	        			document.getElementById("interconsulta_especialista").value = data.interconsulta.especialista;
	        			
	        			var fecha_aux = data.interconsulta.fechaSolicitud.split("-");
	        			var fecha_mostrar = fecha_aux[2]+"/"+fecha_aux[1]+"/"+fecha_aux[0];
	        			document.getElementById("interconsulta_fecha_solicito").value = fecha_mostrar;

						document.getElementById("interconsulta_solicito").value = data.interconsulta.solicito;
						document.getElementById("interconsulta_respuesta").value = data.interconsulta.respuesta;
						var valor = data.numero+"/"+data.numero;
	           			document.getElementById("interconsulta_actual_cantidad").value = valor;		   			
           			}
           		} else {
           			document.getElementById("seccion_interconsulta").hidden = true;
           			document.getElementById("interconsulta_nueva_consulta").hidden = true;
           		}
            }
        });	
	}

	function interconsultaAnteriorSiguiente(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var consulta_actual = document.getElementById("consulta_id").value;	
		var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
		var consulta = 2;
		if(esNuevaConsulta == 1 || esNuevaConsulta == 4){
			consulta = 1;//document.getElementById("consulta_id").value;
			//var numero_actual = document.getElementById("interconsulta_numero_consulta").value;			
		}/* else {
			var numero_actual = document.getElementById("interconsulta_numero").value;
		}*/
		var numero_actual = document.getElementById("interconsulta_numero").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;	
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_interconsulta',
           data:{paciente:paciente, consulta:consulta ,numero:numero, consulta_actual:consulta_actual ,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response == 1 && data.interconsulta != null) {           		           			           			
           			document.getElementById("interconsulta_numero").value = data.interconsulta.numero;
           			if(data.interconsulta.numero != 0){
           				
           				document.getElementById("seccion_interconsulta").hidden = false;	           			
	        			document.getElementById("interconsulta_especialista").value = data.interconsulta.especialista;
	        			
	        			var fecha_aux = data.interconsulta.fechaSolicitud.split("-");
	        			var fecha_mostrar = fecha_aux[2]+"/"+fecha_aux[1]+"/"+fecha_aux[0];
	        			document.getElementById("interconsulta_fecha_solicito").value = fecha_mostrar;

						document.getElementById("interconsulta_solicito").value = data.interconsulta.solicito;
						document.getElementById("interconsulta_respuesta").value = data.interconsulta.respuesta;
						//interconsultasReadOnlyCheck();
						if(esNuevaConsulta == 0) {
							//alert(consulta_actual+" "+data.interconsulta.consulta_respuesta);
							if((consulta_actual >= data.interconsulta.consulta_respuesta)&&(data.interconsulta.consulta_respuesta!=0)){           				
		           				document.getElementById("seccion_interconsulta_respuesta").hidden = false;
		           				document.getElementById("interconsulta_respuesta").hidden = false;           				
		           			} else {           			
		           				document.getElementById("seccion_interconsulta_respuesta").hidden = true;
		           				document.getElementById("interconsulta_respuesta").hidden = true;           				
		           			}
		           			//alert(data.tope);
							var valor = data.interconsulta.numero +"/"+data.tope;
							document.getElementById("interconsulta_numero_consulta").value = data.numero;
						}
						else
							var valor = data.interconsulta.numero+"/"+data.tope;
	           			document.getElementById("interconsulta_actual_cantidad").value = valor;		   			
           			}
           		}
            }
        });
	}

	function nuevaInterconsulta(){
		document.getElementById("interconsulta_especialista").value = "";
		document.getElementById("interconsulta_solicito").value = "";
		document.getElementById("interconsulta_respuesta").value = "";		
		var numero = document.getElementById("interconsulta_actual_cantidad").value;
		var numero_aux = numero.split("/");
		numero = parseInt(numero_aux[1]) + 1;
		document.getElementById("interconsulta_numero").value = numero;
		var fecha_consulta_dia = document.getElementById('fecha_consulta_dia');
		if(fecha_consulta_dia != null && fecha_consulta_dia.value.localeCompare('')!=0){
			var fcmes = document.getElementById('fecha_consulta_mes').value;
			var fcanio = document.getElementById('fecha_consulta_anio').value;
			var nuevafi = fecha_consulta_dia.value+"/"+fcmes+"/"+fcanio;
			document.getElementById("interconsulta_fecha_solicito").value = nuevafi;
		} else {
			var newDate = new Date();
			var dia = newDate.getDate();
			var mes = newDate.getMonth() + 1;
			var anio = newDate.getFullYear();
			document.getElementById("interconsulta_fecha_solicito").value = dia+"/"+mes+"/"+anio;
		}		
	}

	function guardarDatosInterconsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;	
		var numero = document.getElementById("interconsulta_numero").value;	

		var especialista = document.getElementById("interconsulta_especialista").value;
		var solicito = document.getElementById("interconsulta_solicito").value;
		var respuesta = document.getElementById("interconsulta_respuesta").value;
		var fechaSolicitud = document.getElementById("interconsulta_fecha_solicito").value;

		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_interconsulta',
           data:{consulta:consulta, paciente:paciente, numero:numero, solicito:solicito, especialista:especialista, respuesta:respuesta ,fechaSolicitud:fechaSolicitud,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			var valor = data.numero+"/"+data.cantidadInterconsultas;
           			document.getElementById("interconsulta_actual_cantidad").value = valor;
           			mostrarSnackbar("INTERCONSULTA GUARDADA");
            	} else {
            		mostrarSnackbar("INTERCONSULTA FALLO");
            	}
            }
        });
	}

	function interconsultasReadOnlyCheck() {
		var especialista = document.getElementById("interconsulta_especialista");
		var solicito = document.getElementById("interconsulta_solicito");
		var respuesta = document.getElementById("interconsulta_respuesta");
		var fechaSolicitud = document.getElementById("interconsulta_fecha_solicito");

		if(especialista!=null && especialista.value.localeCompare('')==0){
			especialista.readOnly = false;
		} else {
			especialista.readOnly = true;
		}

		if(solicito!=null && solicito.value.localeCompare('')==0){
			solicito.readOnly = false;
		} else {
			solicito.readOnly = true;
		}

		if(respuesta!=null && respuesta.value.localeCompare('')==0){
			respuesta.readOnly = false;
		} else {
			respuesta.readOnly = true;
		}

		if(fechaSolicitud!=null && fechaSolicitud.value.localeCompare('')==0){
			fechaSolicitud.readOnly = false;
		} else {
			fechaSolicitud.readOnly = true;
		}
	}

</script>