<input hidden id="desarrollo_madurativo_fecha" value="0">
<input hidden class="form-control" type="text" name="dm_cantidad_meses_cargar" id="dm_cantidad_meses_cargar" value="-1"/>   
@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual">	
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickDesarrolloMadurativo()" type="button">Desarrollo Madurativo</a></b></h4>
	</div>	
@else
<form id="desarrollo_madurativo_nueva_consulta">	
	<div class="row margin_left_5px">
		<p class="rodri_bold font_size_resumen">Desarrollo Madurativo</p>
	</div>	
@endif
	<div id="seccion_desarrollo_madurativo" hidden>		
		<h5 id="desarrollo_madurativo_titulo">Menor de 1 mes</h5>
		<div class="table-responsive">
			<table class="table table-condensed tabla_con_borde" id="tabla_pacientes_dm" name="tabla_pacientes_dm">
			    <thead>
			        <tr>
			          <th class="editText input_width_50px rodri_th letra_size_07rem" scope="col">Motor Grueso</th>
			          <th class="editText input_width_50px rodri_th letra_size_07rem" scope="col">Motor Fino</th>
			          <th class="editText input_width_50px rodri_th letra_size_07rem" scope="col">Psicosocial</th>
			          <th class="editText input_width_50px rodri_th letra_size_07rem" scope="col">Lenguaje</th>
			      </tr>
			  	</thead>
			  	<tbody id="pacientes-list-dm" name="pacientes-list-dm">
	            <tr>                  
	                  <td>  <!-- BCG -->                                               
	                        <div class="custom-control custom-checkbox">
	                              <input onclick="clickDSCheck('vacuna_0')" type="checkbox" class="custom-control-input text-center" id="dm_1">
	                              <label class="custom-control-label letra_size_07rem" for="dm_1">Reflejos de busqueda y succion</label>
	                        </div>                        
	                  </td>
	                  <td>  <!-- HEPATITIS B -->                       
	                        <div class="custom-control custom-checkbox">
	                              <input onclick="clickDSCheck('vacuna_1')"  type="checkbox" class="custom-control-input" id="dm_2" >
	                              <label class="custom-control-label letra_size_07rem" for="dm_2">Prension palmar refleja</label>
	                        </div>                       
	                  </td>
	                  <td>  <!-- HEPATITIS B -->                       
	                        <div class="custom-control custom-checkbox">
	                              <input onclick="clickDSCheck('vacuna_1')"  type="checkbox" class="custom-control-input" id="dm_3" >
	                              <label class="custom-control-label letra_size_07rem" for="dm_3">Fijacion ocular</label>
	                        </div>                       
	                  </td>
	                  <td>  <!-- HEPATITIS B -->                       
	                        <div class="custom-control custom-checkbox">
	                              <input onclick="clickDSCheck('vacuna_1')"  type="checkbox" class="custom-control-input" id="dm_4" >
	                              <label class="custom-control-label letra_size_07rem" for="dm_4">Responde al sonido</label>
	                        </div>                       
	                  </td>
	              </tr>
	          </tbody>
	      	</table>
      	</div>
      	<div id="seccion_desarrollo_madurativo_observacion">
			<label>Observacion:</label>
			<div class="row margin_left_5px">	
				<textarea class="form-control width650px" id="desarrollo_madurativo_observacion" name="desarrollo_madurativo_menor_1mes_observacion" rows="5" cols="100"></textarea>	
			</div>
		</div>
		<br>
	</div>
</form>

<script type="text/javascript">

	function cargarDesarolloMadurativoConsulta() {
		var consulta = document.getElementById("consulta_id").value;			
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_desarrollo_madurativo_consulta',
           data:{consulta:consulta,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
    			if(data.response == 1){      			
    				document.getElementById("desarrollo_madurativo_nueva_consulta").hidden = false;
    				document.getElementById("seccion_desarrollo_madurativo").hidden = false; 
    				document.getElementById("seccion_desarrollo_madurativo").hidden = false; 
					
    				clickDesarrolloMadurativo();    				
    			}
    			else { 
    				document.getElementById("seccion_desarrollo_madurativo").hidden = true;
    				document.getElementById("desarrollo_madurativo_nueva_consulta").hidden = true;
    			}

            }
        });	
	}
	
	function clickDesarrolloMadurativo() {
		seccionPrincipal = document.getElementById("seccion_desarrollo_madurativo");	
		if(seccionPrincipal.hidden == true){
			seccionPrincipal.hidden = false;			
		}
		else {			
			seccionPrincipal.hidden = true;
		}

		var es_nueva_consulta = document.getElementById("es_nueva_consulta").value;		

		var opcion = 1;
		if(es_nueva_consulta == 1 || es_nueva_consulta == 3) {
			opcion = 2;		
			desarrolloMadurativoGuardarEdad();
		}
//		alert(opcion);		  2
		cargarTitulo(opcion);		
		//if(document.getElementById("dm_cantidad_meses_cargar").value == -1){		
			//alert(desarrollo_madurativo_fecha);
			if(calcularCantidadMeses(opcion)<24) {					
				crearTabla(calcularCantidadMeses(opcion), opcion);				
			} else {				
				cargarDesarrolloMadurativoObservacion();
				document.getElementById("tabla_pacientes_dm").hidden = true;
			}
		//}
	}

	/*function validarDesarrolloMadurativoFecha(){
		var consulta = document.getElementById("consulta_id").value;	
				
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_desarrollo_madurativo_fecha',
           data:{consulta_id:consulta, _token: '{{csrf_token()}}'},
           	success:function(data){          
           		alert(data.response_data.edad_paciente);
           		var es_nueva_consulta = document.getElementById("es_nueva_consulta").value;
           		if(data.response_data.edad_paciente == -2 && (es_nueva_consulta == 3 || es_nueva_consulta == 1))
           			document.getElementById("desarrollo_madurativo_fecha").value = 1;
           		else
           			document.getElementById("desarrollo_madurativo_fecha").value = 0;
            }	
        });
	} */

	function cargarDesarrolloMadurativoObservacion(){
		var consulta = document.getElementById("consulta_id").value;	
		var paciente_id = document.getElementById("paciente_id").value;
		//alert(consulta+" "+paciente_id);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_desarrollo_madurativo_observacion',
           data:{consulta_id:consulta, paciente_id:paciente_id, _token: '{{csrf_token()}}'},
           	success:function(data){          
           	//alert(data.response_data.observacion);          	                	
    			if(data.response_data != null){    				
    				document.getElementById("seccion_desarrollo_madurativo_observacion").hidden = false;
    				document.getElementById("desarrollo_madurativo_observacion").hidden = false;
    				document.getElementById("seccion_desarrollo_madurativo").hidden = false;
    				
    				document.getElementById("desarrollo_madurativo_observacion").value = data.response_data.observacion;
    			} else {    				
    				//document.getElementById("seccion_desarrollo_madurativo_observacion").hidden = true;
    				document.getElementById("desarrollo_madurativo_observacion").value = '';
    			}
            }	
        });
	}

	function calcularCantidadMeses(opcion){
		if(opcion == 2){
			var e = document.getElementById("fecha_nacimiento").value;
			var fecha_consulta_dia = document.getElementById("fecha_consulta_dia");
			if(fecha_consulta_dia != null && fecha_consulta_dia.value.localeCompare("") != 0){
				e = document.getElementById("fecha_consulta_dia").value+"/"+document.getElementById("fecha_consulta_mes").value+"/"+document.getElementById("fecha_consulta_anio").value;
			}
		}
		if(opcion == 1){						
			return document.getElementById("dm_cantidad_meses_cargar").value;	
		}

		var ee = e.split("/");

		var fechaActual = new Date(); 
    	var mes = fechaActual.getMonth()+1;
    	var dia = fechaActual.getDate();
    	var anio = fechaActual.getFullYear();
    	var fechaHoy = anio+"-"+mes+"-"+dia;
    	
		var edad_aux_array = calcularEdad4(ee[2]+"-"+ee[1]+"-"+ee[0], fechaHoy);
		var edad_aux = edad_aux_array.split("-");
		var edad = 0;
		if(edad_aux[0] != 0){
		edad = edad + (parseInt(edad_aux[0]) * 12);
		}
		if(edad_aux[1] != 0){
			edad = edad + parseInt(edad_aux[1]);
		}
		if(edad == 8)
			edad = 7;
		if(edad == 10 || edad == 11)
			edad = 9;
		if(edad == 13 || edad == 14)
			edad = 12;
		if(edad == 16 || edad == 17)
			edad = 15;
		if(edad > 18 && edad < 24 )
			edad = 18;
		
		return edad;
	}

	function clickDSCheck(id) {
		var paciente_id = document.getElementById("paciente_id").value;
		var consulta_id = document.getElementById("consulta_id").value;
		var myId = "dm_"+id;
		var checkbox_value = document.getElementById(myId).checked;		
		var checked = 0;
		if(checkbox_value)
			checked = 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_desarrollo_madurativo_check',
           data:{consulta_id:consulta_id, paciente_id:paciente_id, desarrollo_madurativo_id:id, checked:checked ,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
    			                   	
            }
        });	
	}

	//opcion 1 = consulta cargada, 2 = nueva consulta
	function cargarTitulo(opcion){
		var mes = calcularCantidadMeses(opcion);
		if(opcion == 1)
			mes = document.getElementById("dm_cantidad_meses_cargar").value;
		var titulo = document.getElementById("desarrollo_madurativo_titulo");		
		if(mes== 0){
			titulo.innerHTML ="Menor de 1 mes";
		}
		if(mes == 1)
			titulo.innerHTML = mes+" mes";
		if(mes== 2 || mes== 3 || mes== 4 || mes== 5 || mes== 6 || mes==12 || mes==15 || mes==18 || mes==24){
			titulo.innerHTML = mes+" meses";
		}
		if(mes == 7 || mes== 8){
			titulo.innerHTML = "7 y 8 meses";	
		}
		if(mes == 9 || mes== 10 || mes== 11){
			titulo.innerHTML = "9, 10 y 11 meses";	
		}
		if(mes > 24){
			titulo.innerHTML = "Mayor 2 años";		
		}		
	}

	function crearTabla(edad, opcion) {	
		if(opcion == 1){
			edad = document.getElementById("dm_cantidad_meses_cargar").value;
		}		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_desarrollo_madurativo_tabla',
           data:{edad:edad, opcion:opcion ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1) {
           			$("#tabla_pacientes_dm").find("tr:gt(0)").remove();  
           			//alert("data tope "+data.tope);         			
           			 for (i = 0; i < data.tope; i++) {           			
                 	 	var motorGrueso = "<td></td>";
                 	 	if(data.motor_grueso[i] != null){                 	 		
                 	 		motorGrueso = "<td class='editText'><div class='custom-control custom-checkbox'><input onclick=clickDSCheck("+data.motor_grueso[i].id+") type='checkbox' class='custom-control-input text-center' id='dm_"+data.motor_grueso[i].id+"'><label class='custom-control-label letra_size_07rem' for='dm_"+data.motor_grueso[i].id+"'>"+data.motor_grueso[i].descripcion+"</td>"; 
                 	 	}
                 	 	var motorFino = "<td></td>";
                 	 	if(data.motor_fino[i] != null){
                 	 		motorFino = "<td class='editText'><div class='custom-control custom-checkbox'><input onclick=clickDSCheck("+data.motor_fino[i].id+") type='checkbox' class='custom-control-input text-center' id='dm_"+data.motor_fino[i].id+"'><label class='custom-control-label letra_size_07rem' for='dm_"+data.motor_fino[i].id+"'>"+data.motor_fino[i].descripcion+"</td>"; 
                 	 	}
                 	 	var psicosocial = "<td></td>";
                 	 	if(data.psicosocial[i] != null){
                 	 		psicosocial = "<td class='editText'><div class='custom-control custom-checkbox'><input onclick=clickDSCheck("+data.psicosocial[i].id+") type='checkbox' class='custom-control-input text-center' id='dm_"+data.psicosocial[i].id+"'><label class='custom-control-label letra_size_07rem' for='dm_"+data.psicosocial[i].id+"'>"+data.psicosocial[i].descripcion+"</td>"; 
                 	 	}
                 	 	var lenguaje = "<td></td>";
                 	 	if(data.lenguaje[i] != null){
                 	 		lenguaje = "<td class='editText'><div class='custom-control custom-checkbox'><input onclick=clickDSCheck("+data.lenguaje[i].id+") type='checkbox' class='custom-control-input text-center' id='dm_"+data.lenguaje[i].id+"'><label class='custom-control-label letra_size_07rem' for='dm_"+data.lenguaje[i].id+"'>"+data.lenguaje[i].descripcion+"</td>"; 
                 	 	}

           			 	var paciente = "<tr>"+motorGrueso+motorFino+psicosocial+lenguaje+"</tr>";

           			 	$('#pacientes-list-dm').append(paciente); 
           			 }  
           			 cargarDesarrolloMadurativo(data.opcion);
            	} 
            }
        });		
	}

	function getDesarrolloMadurativoEdadConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;

		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_desarrollo_madurativo_edad_consulta',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){       
           	if(data.mes != null)                  		
           		document.getElementById("dm_cantidad_meses_cargar").value = data.mes;
            }
        });	
	}

	//opcion 1 si viene desde una consulta, opcion 2 si viene de una nueva consulta
	function cargarDesarrolloMadurativo(opcion){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		if(opcion == 1){
			document.getElementById("seccion_desarrollo_madurativo").hidden = false;
			var mes = -2;
			var mes = document.getElementById("dm_cantidad_meses_cargar").value;
		}
		else{			
			var mes = calcularCantidadMeses(opcion);
			var _mes = mes;
		}		
		document.getElementById("desarrollo_madurativo_nueva_consulta");
		//crearTabla(_mes);		
		//alert("mes: "+mes+" paciente:"+paciente+ " opcion:"+ opcion);	 1 37 1
		//alert(mes);		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_desarrollo_madurativo',
           data:{consulta:consulta, paciente:paciente, mes:mes,_token: '{{csrf_token()}}'},
           	success:function(data) {              	
           		//var mostrar = 0;                  		
           		if(data.response == 1 || data.observacion_detalle.observacion.localeCompare('')!= 0){           			           		
	           		if(data.mes == -2)
	           				document.getElementById("desarrollo_madurativo_titulo").innerHTML = "";
	           		/*if(data.response_data[0].observacion.localeCompare('') != 0 && data.response_data[0].checked == 2){	           			
	           			document.getElementById("tabla_pacientes_dm").hidden = true;
	           			if(data.mes == -2)
	           				document.getElementById("desarrollo_madurativo_titulo").innerHTML = "";
	           			else
	           				document.getElementById("desarrollo_madurativo_titulo").innerHTML = "Mayor 2 años";
	           		}	*/           		
	           		if((data.observacion_detalle != null && data.observacion_detalle.observacion.localeCompare('')!=0) || (opcion == 2)){
	           			document.getElementById("seccion_desarrollo_madurativo_observacion").hidden = false;
	           			document.getElementById("desarrollo_madurativo_observacion").value = data.observacion_detalle.observacion;	           			
	           		} else {
	           			document.getElementById("seccion_desarrollo_madurativo_observacion").hidden = true;
	           			document.getElementById("desarrollo_madurativo_observacion").value = '';
	           		}	           		
	           		
	           		for (i = 0; i < data.tope; i++) { 	           				           			
	           			var checkbox = document.getElementById("dm_"+data.response_data[i].desarrollo_madurativo_id);
	           			if(checkbox!=null){
		           			mostrar = 1;
		           			if(data.response_data[i].checked == 1)
		           				checkbox.checked = true;
		           			else
		           				checkbox.checked = false;
	           			}	           			
	           		}	           		
	           		if(data.response == 1){
	           			document.getElementById("tabla_pacientes_dm").hidden = false;
	           		} else {
	           			document.getElementById("tabla_pacientes_dm").hidden = true;
	           		}
           		} else {
           			if(document.getElementById("es_nueva_consulta").value == 0)
           				document.getElementById("desarrollo_madurativo_nueva_consulta").hidden = true;
           		} 

            }
        });		
	}

	function desarrolloMadurativoGuardarObservacion(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var observacion = document.getElementById("desarrollo_madurativo_observacion").value;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_desarrollo_madurativo_observacion',
           data:{consulta:consulta, paciente:paciente, observacion:observacion,_token: '{{csrf_token()}}'},
           	success:function(data){                         		           		
				           				
            }
        });		
	}

	function desarrolloMadurativoGuardarEdad(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var edad = calcularCantidadMeses(2);

		var fechaConsultaDia = document.getElementById("fecha_consulta_dia");
		if(fechaConsultaDia!=null && fechaConsultaDia.value.localeCompare('')!=0){
			edad = -2;
		}
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_desarrollo_madurativo_edad',
           data:{consulta:consulta, paciente:paciente, edad:edad,_token: '{{csrf_token()}}'},
           	success:function(data){                         		           		
				           				
            }
        });		
	}

</script>