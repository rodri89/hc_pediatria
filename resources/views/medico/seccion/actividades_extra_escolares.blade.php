<form class="card background_panel_consulta_actual" id="seccion_actividades_extra_escolares_nueva_consulta">		
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickActividadesExtraEscolares()" type="button">Actividades Extra Escolares</a></b></h4>
	</div>	
	<div id="seccion_actividades_extra_escolares" hidden>		
		<div class="row margin_left_5px">		
			<textarea class="form-control width650px_cel" id="actividades_extra_escolares_detalles" name="actividades_extra_escolares_detalles" rows="8" cols="80"></textarea>	
		</div>
		<br>
	</div>
</form>

<div id="seccion_actividades_extra_escolares_consulta" hidden>			
</div>

<script type="text/javascript">
	
	function clickActividadesExtraEscolares(){
		seccionPrincipal = document.getElementById("seccion_actividades_extra_escolares");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	function guardarActividadesExtraEscolares(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var descripcion = document.getElementById("actividades_extra_escolares_detalles").value;
		//alert(consulta);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_actividades_extra_escolares',
           data:{consulta:consulta, paciente:paciente, descripcion:descripcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ACTIVIDADES EXTRA ESCOLARES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });		
	}

	function cargarActividadesExtraEscolaresConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		borrarSeccion("seccion_actividades_extra_escolares_consulta");
		document.getElementById("seccion_actividades_extra_escolares_nueva_consulta").hidden = true;

		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_actividades_extra_escolares',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("seccion_actividades_extra_escolares_consulta").hidden = false;
           			var seccion_resumen = document.getElementById("seccion_actividades_extra_escolares_consulta");
           			addTituloDescripcion("Actividades Extra Escolares", data.response_data.descripcion, seccion_resumen);           			          			
            	} else {
            		document.getElementById("seccion_actividades_extra_escolares_consulta").hidden = true;
            	}
            }
        });	
	}

	function cargarActividadesExtraEscolares(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_actividades_extra_escolares',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){              		           		
           			document.getElementById("actividades_extra_escolares_detalles").value = data.response_data.descripcion;
            	}
            }
        });	
	}

</script>