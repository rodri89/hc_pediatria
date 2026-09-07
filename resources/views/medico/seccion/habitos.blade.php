<form class="card background_panel_consulta_actual" id="seccion_habitos_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickHabitos()" type="button">Hábitos</a></b></h4>
	</div>	
	<div id="seccion_habitos" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="habitos_detalles" name="habitos_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>		
	</div>
</form>

<div id="seccion_habitos_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickHabitos(){
		seccionPrincipal = document.getElementById("seccion_habitos");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarHabitos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("habitos_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_habitos',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("HABITOS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarHabitosConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_habitos_consulta");
		document.getElementById("seccion_habitos_nueva_consulta").hidden = true;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_habitos_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_habitos_consulta");
           			addTituloDescripcion("Hábitos", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_habitos_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarHabitos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("habitos_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}
</script>