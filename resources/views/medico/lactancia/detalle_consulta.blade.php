<form class="card background_panel_consulta_actual" id="seccion_detalle_consulta_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickDetalleConsulta()" type="button">Observaciones</a></b></h4>
	</div>	
	<div id="seccion_detalle_consulta">		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="dc_detalles" name="dc_detalles" rows="10" cols="80"></textarea>	
		</div>
		<br>
	</div>
</form>

<div id="seccion_detalle_consulta_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickDetalleConsulta(){
		seccionPrincipal = document.getElementById("seccion_detalle_consulta");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarDetalleConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("dc_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_detalle_consulta_lactancia',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("DETALLE CONSULTA FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarDetalleConsultaConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_detalle_consulta_consulta");
		document.getElementById("seccion_detalle_consulta_nueva_consulta").hidden = true;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_detalle_consulta_lactancia',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_detalle_consulta_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_detalle_consulta_consulta");
           			addTituloDescripcion("Observaciones", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_detalle_consulta_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarDetalleConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_detalle_consulta_lactancia',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("dc_detalles").value = data.response_data.descripcion;           		           			          			
            	} 
            }
        });	
	}

</script>