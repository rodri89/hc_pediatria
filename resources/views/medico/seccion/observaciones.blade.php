<form class="card background_panel_consulta_actual" id="seccion_observaciones_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickObservaciones()" type="button">Observaciones</a></b></h4>
	</div>	
	<div id="seccion_observaciones" hidden>		
		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="observaciones_detalles" name="observaciones_detalles" rows="10" cols="100"></textarea>	
		</div>

		<br>		
	</div>
</form>

<div id="seccion_observaciones_consulta" hidden>			
</div>


<script type="text/javascript">
	
	function clickObservaciones(){
		seccionPrincipal = document.getElementById("seccion_observaciones");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarObservaciones(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("observaciones_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_observaciones',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito observaciones");
            	} else {
            		mostrarSanckbar("OBSERVACIONES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarObservacionesConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_observaciones_consulta");
		document.getElementById("seccion_observaciones_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_observaciones',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		
           			document.getElementById("seccion_observaciones_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_observaciones_consulta");
           			addTituloDescripcion("Observaciones", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_observaciones_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarObservaciones(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_observaciones',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		           		
           			document.getElementById("observaciones_detalles").value = data.response_data.descripcion;           		
            	} 
            }
        });	
	}
</script>

