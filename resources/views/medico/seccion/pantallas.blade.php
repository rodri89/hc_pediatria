<form class="card background_panel_consulta_actual" id="seccion_pantallas_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickPantallas()" type="button">Pantallas</a></b></h4>
	</div>	
	<div id="seccion_pantallas" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="pantallas_detalles" name="pantallas_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>
	</div>
</form>

<div id="seccion_pantallas_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickPantallas(){
		seccionPrincipal = document.getElementById("seccion_pantallas");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarPantallas(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("pantallas_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_pantallas',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("PANTALLAS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarPantallasConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_pantallas_consulta");
		document.getElementById("seccion_pantallas_nueva_consulta").hidden = true;	
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_pantallas',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_pantallas_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_pantallas_consulta");
           			addTituloDescripcion("Pantallas", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_pantallas_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarPantallas(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_pantallas',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("pantallas_detalles").value = data.response_data.descripcion;           		
            	}
            }
        });	
	}
</script>