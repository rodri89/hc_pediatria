<form class="card background_panel_consulta_actual" id="seccion_nota_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickNota()" type="button">Notas</a></b></h4>
	</div>	
	<div id="seccion_nota" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="nota_detalles" name="nota_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>
	</div>
</form>

<div id="seccion_nota_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickNota(){
		seccionPrincipal = document.getElementById("seccion_nota");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarNotas(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("nota_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_nota',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("NOTAS FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarNotaConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_nota_consulta");
		document.getElementById("seccion_nota_nueva_consulta").hidden = true;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_nota',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_nota_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_nota_consulta");
           			addTituloDescripcion("Nota", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_nota_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarNota(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_nota',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		           			
           			document.getElementById("nota_detalles").value = data.response_data.descripcion;           		           			          			
            	} 
            }
        });	
	}

</script>