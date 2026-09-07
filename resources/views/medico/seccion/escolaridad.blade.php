<form class="card background_panel_consulta_actual" id="seccion_escolaridad_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickEscolaridad()" type="button">Escolaridad</a></b></h4>
	</div>	
	<div id="seccion_escolaridad" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="escolaridad_detalles" name="escolaridad_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>		
	</div>
</form>

<div id="seccion_escolaridad_consulta" hidden>			
</div>
	
<script type="text/javascript">
	
	function clickEscolaridad(){
		seccionPrincipal = document.getElementById("seccion_escolaridad");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarEscolaridad(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("escolaridad_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_escolaridad',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ESCOLARIDAD FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarEscolaridadConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_escolaridad_consulta");
		document.getElementById("seccion_escolaridad_nueva_consulta").hidden = true;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_escolaridad',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_escolaridad_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_escolaridad_consulta");
           			addTituloDescripcion("Escolaridad", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_escolaridad_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarEscolaridad(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_escolaridad',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("escolaridad_detalles").value = data.response_data.descripcion;           			
            	}
            }
        });	
	}

</script>