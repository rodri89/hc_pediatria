<form class="card background_panel_consulta_actual" id="seccion_vacunas_dos_nueva_consulta">
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickVacunasDos()" type="button">Vacunas</a></b></h4>
	</div>	
	<div id="seccion_vacunas_dos" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="vacunas_dos_detalles" name="vacunas_dos_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>
	</div>
</form>

<div id="seccion_vacunas_dos_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickVacunasDos(){
		seccionPrincipal = document.getElementById("seccion_vacunas_dos");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarVacunasDos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("vacunas_dos_detalles").value;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_vacunas_dos',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("VACUNAS DOS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarVacunasDos(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		borrarSeccion("seccion_vacunas_dos_consulta");
		document.getElementById("seccion_vacunas_dos_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_vacunas_dos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		           		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("seccion_vacunas_dos_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_vacunas_dos_consulta");
           			addTituloDescripcion("Vacunas", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {            		
            		document.getElementById("seccion_vacunas_dos_consulta").hidden = true;
            	}
            }
        });
	}

	function cargarVacunasDosNuevaConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;						
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_vacunas_dos',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		           		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("vacunas_dos_detalles").value = data.response_data.descripcion;

            	}
            }
        });	
	}
</script>