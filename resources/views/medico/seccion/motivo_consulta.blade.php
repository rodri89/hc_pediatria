<form class="card background_panel_consulta_actual" id="seccion_motivo_consulta_nueva_consulta">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickMotivoConsulta()" type="button">Motivo Consulta</a></b></h4>
	</div>	
	<div id="seccion_motivo_consulta" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="width200px" id="motivo_consulta_detalles" name="motivo_consulta_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>	
	</div>
</form>

<div id="seccion_motivo_consulta_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickMotivoConsulta(){
		seccionPrincipal = document.getElementById("seccion_motivo_consulta");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarMotivoConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("motivo_consulta_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_motivo_consulta',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("MOTIVO CONSULTA FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarMotivoConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_motivo_consulta_consulta");
		document.getElementById("seccion_motivo_consulta_nueva_consulta").hidden = true;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_motivo_consulta',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_motivo_consulta_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_motivo_consulta_consulta");
           			addTituloDescripcion("Motivo Consulta", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_motivo_consulta_consulta").hidden = true;
            	}
            }
        });	
	}

</script>