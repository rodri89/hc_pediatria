<form class="card background_panel_consulta_actual" id="seccion_datos_objetivos_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickDatosObjetivos()" type="button">Datos Objetivos</a></b></h4>
	</div>	
	<div id="seccion_datos_objetivos" hidden>		
		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="datos_objetivos_detalles" name="datos_objetivos_detalles" rows="10" cols="100"></textarea>	
		</div>

		<br>		
	</div>
</form>

<div id="seccion_datos_objetivos_consulta" hidden>			
</div>


<script type="text/javascript">
	
	function clickDatosObjetivos(){
		seccionPrincipal = document.getElementById("seccion_datos_objetivos");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarDatosObjetivos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("datos_objetivos_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_datos_objetivos',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("DATOS OBJETIVOS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarDatosObjetivosConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_datos_objetivos_consulta");
		document.getElementById("seccion_datos_objetivos_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_datos_objetivos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		
           			document.getElementById("seccion_datos_objetivos_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_datos_objetivos_consulta");
           			addTituloDescripcion("Datos Objetivos", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_datos_objetivos_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarDatosObjetivos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_datos_objetivos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		           		
           			document.getElementById("datos_objetivos_detalles").value = data.response_data.descripcion;           		
            	} 
            }
        });	
	}
</script>