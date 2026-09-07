@if($nueva_consulta == 1)
<form hidden class="card background_panel_consulta_actual" id="seccion_lactancia_embarazo_previo_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickLactanciaEmbarazoPrevio()" type="button">Lactancia Embarazo Previo</a></b></h4>
	</div>	
@else
	<form hidden id="seccion_lactancia_embarazo_previo_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b>Lactancia Embarazo Previo</b></h4>
	</div>	
@endif
	<div id="seccion_lactancia_embarazo_previo" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="lactancia_embarazo_previo_detalles" name="lactancia_embarazo_previo_detalles" rows="4" cols="80"></textarea>	
		</div>
		<br>		
	</div>
</form>

<div id="seccion_lactancia_embarazo_previo_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickLactanciaEmbarazoPrevio(){
		seccionPrincipal = document.getElementById("seccion_lactancia_embarazo_previo");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function mostrarLactanciaEmbarazoPrevio(mostrar){
		if(mostrar){
			document.getElementById("seccion_lactancia_embarazo_previo_nueva_consulta").hidden = true;
		} else {
			document.getElementById("seccion_lactancia_embarazo_previo_nueva_consulta").hidden = false;
		}
	}

	function guardarLactanciaEmbarazoPrevio(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("lactancia_embarazo_previo_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_lactancia_embarazo_previo',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("LACTANCIA EMBARAZO PREVIO FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarLactanciaEmbarazoPrevioConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_lactancia_embarazo_previo',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){            		
           		document.getElementById("lactancia_embarazo_previo_detalles").value = "";
           		if(data.response_data != null && data.response_data.descripcion.localeCompare("")!=0){              		
           			document.getElementById("seccion_lactancia_embarazo_previo_nueva_consulta").hidden = false;      		
           			document.getElementById("seccion_lactancia_embarazo_previo").hidden = false;
           			document.getElementById("lactancia_embarazo_previo_detalles").value = data.response_data.descripcion;
            	} else {
            		document.getElementById("seccion_lactancia_embarazo_previo_nueva_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarLactanciaEmbarazoPrevio(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_habitos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("lactancia_embarazo_previo_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}
</script>