<form class="card background_panel_consulta_actual" id="seccion_datos_subjetivos_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickDatosSubjetivos()" type="button">Datos Subjetivos</a></b></h4>
	</div>	
	<div id="seccion_datos_subjetivos" hidden>		
		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="datos_subjetivos_detalles" name="datos_subjetivos_detalles" rows="10" cols="100"></textarea>	
		</div>

		<br>		
	</div>
</form>

<div id="seccion_datos_subjetivos_consulta" hidden>			
</div>


<script type="text/javascript">
	
	function clickDatosSubjetivos(){
		seccionPrincipal = document.getElementById("seccion_datos_subjetivos");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarDatosSubjetivos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("datos_subjetivos_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_datos_subjetivos',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("DATOS SUBJETIVOS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarDatosSubjetivosConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_datos_subjetivos_consulta");
		document.getElementById("seccion_datos_subjetivos_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_datos_subjetivos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		
           			document.getElementById("seccion_datos_subjetivos_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_datos_subjetivos_consulta");
           			addTituloDescripcion("Datos Subjetivos", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_datos_subjetivos_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarDatosSubjetivos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_datos_subjetivos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		           		
           			document.getElementById("datos_subjetivos_detalles").value = data.response_data.descripcion;           		
            	} 
            }
        });	
	}
</script>