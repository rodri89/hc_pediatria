<form class="card background_panel_consulta_actual" id="seccion_somnia_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickSomnia()" type="button">Somnia</a></b></h4>
	</div>	
	<div id="seccion_somnia" hidden>		
		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="somnia_detalles" name="somnia_detalles" rows="10" cols="100"></textarea>	
		</div>

		<br>		
	</div>
</form>

<div id="seccion_somnia_consulta" hidden>			
</div>


<script type="text/javascript">
	
	function clickSomnia(){
		seccionPrincipal = document.getElementById("seccion_somnia");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarSomnia(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("somnia_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_somnia',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("SOMNIA FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarSomniaConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_somnia_consulta");
		document.getElementById("seccion_somnia_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_somnia',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		
           			document.getElementById("seccion_somnia_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_somnia_consulta");
           			addTituloDescripcion("Somnia", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_somnia_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarSomnia(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_somnia',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		           			
           			document.getElementById("somnia_detalles").value = data.response_data.descripcion;           			
            	} 
            }
        });	
	}
</script>