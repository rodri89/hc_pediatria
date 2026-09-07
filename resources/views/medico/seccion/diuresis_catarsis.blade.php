<form class="card background_panel_consulta_actual" id="seccion_diuresis_catarsis_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickDiuresisCatarsis()" type="button">Catarsis/Diuresis</a></b></h4>
	</div>	
	<div id="seccion_diuresis_catarsis" hidden>		
		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="diuresis_catarsis_detalles" name="diuresis_catarsis_detalles" rows="10" cols="100"></textarea>	
		</div>

		<br>		
	</div>
</form>

<div id="seccion_diuresis_catarsis_consulta" hidden>			
</div>


<script type="text/javascript">
	
	function clickDiuresisCatarsis(){
		seccionPrincipal = document.getElementById("seccion_diuresis_catarsis");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarDiuresisCatarsis(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("diuresis_catarsis_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_diuresis_catarsis',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("DIURESIS CATARSIS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarDiuresisCatarsisConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_diuresis_catarsis_consulta");
		document.getElementById("seccion_diuresis_catarsis_nueva_consulta").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_diuresis_catarsis',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		
           			document.getElementById("seccion_diuresis_catarsis_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_diuresis_catarsis_consulta");
           			addTituloDescripcion("Catarsis/Diuresis", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_diuresis_catarsis_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarCatarsisDiuresis(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_diuresis_catarsis',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           		           			
           			document.getElementById("diuresis_catarsis_detalles").value = data.response_data.descripcion;           			
            	}
            }
        });	
	}
</script>