@if($nueva_consulta == 1 || $nueva_consulta == 3)
<form class="card background_panel_consulta_actual" method="post" action="{{ route('guardarexamenescomplementariosfotos') }}" enctype="multipart/form-data">	
	@csrf  
	<div class="row margin_left_5px">
		<h4><b><a onclick="clickExamenesComplementarios()" role="button" type="button">Examenes Complementarios</a></b></h4>
	</div>
@else
	@if($nueva_consulta == 4)
		<form id="examenes_complementarios_nueva_consulta" method="post" action="{{ route('guardarexamenescomplementariosfotos') }}" enctype="multipart/form-data">
		@csrf 
		<input hidden id="es_nueva_consulta_cargar_fotos" name="es_nueva_consulta_cargar_fotos" value="4"/>
	@else
		<form id="examenes_complementarios_nueva_consulta">	
	@endif
	<div class="row margin_left_5px">
		<p class="rodri_bold font_size_resumen">Examenes Complementarios</p>
	</div>	
@endif
	<div id="seccion_examenes_complementarios" hidden>		
		@if($consulta != null)
	   		<input type="text" id="examenes_complementarios_consulta_id" name="examenes_complementarios_consulta_id" value="{{$consulta->id}}" hidden />
	   	@else
	   		<input type="text" id="examenes_complementarios_consulta_id" name="examenes_complementarios_consulta_id" hidden />
	   	@endif

	   	@if($paciente != null)
	   		<input type="text" id="examenes_complementarios_paciente_id" name="examenes_complementarios_paciente_id" value="{{$paciente->id}}" hidden />
	   	@else
	   		<input type="text" id="examenes_complementarios_paciente_id" name="examenes_complementarios_paciente_id" hidden />
	   	@endif
	   	<input type="text" id="examenes_complementarios_fecha_solicito" name="examenes_complementarios_fecha_solicito"  hidden />
		
		<input hidden id="examenes_complementarios_id" name="examenes_complementarios_id" />
		<input hidden id="examenes_complementarios_numero" name="examenes_complementarios_numero"/>
		<input hidden id="examenes_complementarios_numero_consulta" name="examenes_complementarios_numero_consulta"/>
		<input hidden id="examenes_complementarios_numero_foto" name="examenes_complementarios_numero"/>

		<div class="row margin_left_5px">
			<div class="col-md-6">		
				<div class="row margin_left_5px margin_top_5px">			
					<label class="margin_top_5px input_width_80px" for="examenes_complementarios_solicito">Solicito:</label>
			    	<input type="text" class="form-control input_width_350px" id="examenes_complementarios_solicito" name="examenes_complementarios_solicito"  placeholder=""/>
				</div>				
				<div id="examenes_complementarios_resultado_text" class="row margin_left_5px">
					<label>Resultado:</label>			
				</div>
				@if($nueva_consulta == 1 || $nueva_consulta == 3)
					<textarea class="form-control width450px_cel margin_left_5px" id="examenes_complementarios_respuesta" name="examenes_complementarios_respuesta" rows="8" cols="60"></textarea>			
				@else
					<textarea class="form-control width450px_cel margin_left_5px" id="examenes_complementarios_respuesta" name="examenes_complementarios_respuesta" rows="4" cols="60"></textarea>			
				@endif
			</div>
			<div class="col-md-6">		
				<label id="ex_compl_agregar_foto_text">Agregar Foto:</label><br>
				<div id="seccion_examen_complementario_fotos">
			        <input type="hidden" id="foto_ex_complementario_id" name="foto_ex_complementario_id" />
			        <input type="hidden" id="ex_comp_cantidad_fotos" name="ex_comp_cantidad_fotos" />
			        <input type="file" id="ex_comp_foto_1" name="ex_comp_foto_1" /> 
			     </div>
          		<br>
          		<button id="agregarBotonNuevaFotoExCompId" onclick="agregarBotonNuevaFoto()" type="button" class="rodri_button_aceptar">AGREGAR</button> 			
          		<button id="botonGuardarFotoExCompId" type="submit" class="rodri_button_aceptar margin_left_20px_cel">GUARDAR</button>

          		<div id="seccion_examen_complementario_fotos_mostrar">
	          		<div id="seccion_examen_complementario_ver_fotos" class="margin_top_12px">
					<a type="button" id="examenes_complementarios_foto_1" class="card-img-top img_little botonImage" alt="">
	          			<img id="examenes_complementarios_foto" src="" class="card-img-top img_little botonImage">
	          		</a>
					<div class="row margin_top_12px margin_left_60px">					
			    			<button type="button" onclick="examenesComplementariosAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
					    	<input id="examenes_complementarios_actual_cantidad_fotos" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
					    	<button type="button" onclick="examenesComplementariosAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>				    					
					</div>
				</div>		
			</div>
			</div>
			
		</div>	

		<br>
		<div class="row contenedor3">
			<div class="contenido3">
		    	<button type="button" onclick="examenesComplementariosAnteriorSiguiente(0)" class="rodri_button_aceptar_cel margin_left_cel4"><</button>
		    	<input id="examenes_complementarios_actual_cantidad" disabled class="input_width_50px sinBackground margin_left_20px" value="0/0"></input>
		    	<button type="button" onclick="examenesComplementariosAnteriorSiguiente(1)" class="rodri_button_aceptar_cel">></button>
		    	<button id="nuevoExamenComplementarioButtonId" type="button" onclick="nuevoExamenComplementario()" class="rodri_button margin_left_20px margin_left_50px_solo_cel">NUEVA</button>
		    	<button id="guardarExamenComplementarioButtonId" type="button" onclick="guardarExamenComplementario(1)" class="rodri_button margin_left_20px">GUARDAR</button>
			</div>
		</div>		
		<br>
	</div>	
</form>

<script type="text/javascript">
	
	function nuevoExamenComplementario() {
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
		var tipoConsulta = document.getElementById("tipo_consulta").value;
		if(esNuevaConsulta != 4)
			guardarDatosDosFotos();		
		//alert(consulta);
		$.ajax({
	           type:'POST',
	           dataType:'JSON',
	           url:'/nuevo_examen_complementario',
	           data:{consulta:consulta, paciente:paciente, _token: '{{csrf_token()}}'},
	           	success:function(data) {               	
	           		if(data.response == 1) {           			           			
	           			if(data.examenComplementario != null) {
	           				//alert(data.examenComplementario);
	           				//deshabilitarBotonesExComplementarios(false); 	           				           				           			
	           				document.getElementById("examenes_complementarios_id").value = "-1";
	           				document.getElementById("examenes_complementarios_numero").value = data.examenComplementario;
	           				document.getElementById("examenes_complementarios_solicito").value = "";
	           				document.getElementById("examenes_complementarios_respuesta").value = "";           
	           				 $("#examenes_complementarios_foto").attr("src", "img/iconos/sin_imagen.jpg");                 
		                	document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = "0/0";
		                	guardarExamenComplementario(0);
		                	deshabilitarBotonesExComplementarios(false);
	           			}
	            	}
	            }
	        });
	}

	function deshabilitarBotonesExComplementarios(valor){	
		var btnNuevo = document.getElementById("nuevoExamenComplementarioButtonId");
		var btnGuardar = document.getElementById("guardarExamenComplementarioButtonId");
		document.getElementById("agregarBotonNuevaFotoExCompId").disabled = valor;
		document.getElementById("botonGuardarFotoExCompId").disabled = valor;
		document.getElementById("examenes_complementarios_solicito").disabled = valor;
		document.getElementById("examenes_complementarios_respuesta").disabled = valor;		
		if(valor){
			btnGuardar.disabled = true;
			btnNuevo.disabled = false;
			btnGuardar.setAttribute('class', 'rodri_button_disabled margin_left_20px');								
			btnNuevo.setAttribute('class', 'rodri_button margin_left_20px');								
		} else {
			btnGuardar.disabled = false;
			btnNuevo.disabled = true;
			btnNuevo.setAttribute('class', 'rodri_button_disabled margin_left_20px');								
			btnGuardar.setAttribute('class', 'rodri_button margin_left_20px');								
		}
	}

	function clickExamenesComplementarios() {
		seccionPrincipal = document.getElementById("seccion_examenes_complementarios");	
		if(seccionPrincipal.hidden == true){
			//getExamanesComplementariosId();	
			var ex_compl_id = document.getElementById("examenes_complementarios_id").value;
			if(ex_compl_id == 0){
				//deshabilitarBotonesExComplementarios(true);
			}
			$('#ex_comp_cantidad_fotos').val(1);
  			$('#foto_ex_complementario_id').val(ex_compl_id); 
			var f = new Date();
			var fecha = document.getElementById("examenes_complementarios_fecha_solicito");
			fecha.value = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
			seccionPrincipal.hidden = false;				
			cargarFotoExamenComplementario(ex_compl_id);
			var ex_comp_cantidad = document.getElementById("examenes_complementarios_actual_cantidad").value;
			if(ex_comp_cantidad.localeCompare("0/0") == 0){
				deshabilitarBotonesExComplementarios(true);
			} else {				
				deshabilitarBotonesExComplementarios(false);
				var btnNuevo = document.getElementById("nuevoExamenComplementarioButtonId");
            	btnNuevo.disabled = false;				
				btnNuevo.setAttribute('class', 'rodri_button margin_left_20px');	
			}			
		}
		else {
			seccionPrincipal.hidden = true;
		}
	}

	 function agregarBotonNuevaFoto(){
	    var cantidadFotos = document.getElementById("ex_comp_cantidad_fotos").value;  //1      
	    var viejoValor = parseInt(cantidadFotos); // 1
	    var nuevoValor = parseInt(cantidadFotos) + 1;      
	  //  var btn = document.getElementById("foto-"+viejoValor);
	    var ultimoFile = document.getElementById("ex_comp_foto_"+viejoValor);	    
	    var f = ultimoFile.value;
	    if(f.localeCompare('') != 0){
	      $('#ex_comp_cantidad_fotos').val(nuevoValor);
	      var seccion = document.getElementById("seccion_examen_complementario_fotos");                  
	      var input = document.createElement("INPUT");
	      input.type = 'file';
	      input.id = 'ex_comp_foto_'+nuevoValor;
	      input.name = 'ex_comp_foto_'+nuevoValor;
	      seccion.appendChild(input);
	    }
  	}

  	function getExamanesComplementariosId(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;

  		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_examenes_complementarios_ultimo',
           data:{consulta:consulta, paciente:paciente, _token: '{{csrf_token()}}'},
           	success:function(data) {               	
           		if(data.response == 1) {           			           			
           			if(data.examenComplementario != null) {                			     			
           				document.getElementById("examenes_complementarios_id").value = data.examenComplementario.id;           				
           				document.getElementById("examenes_complementarios_solicito").value = data.examenComplementario.solicito;
           				document.getElementById("examenes_complementarios_respuesta").value = data.examenComplementario.respuesta;
           				var fecha_aux = data.examenComplementario.fechaSolicitud.split("-");
           				document.getElementById("examenes_complementarios_fecha_solicito").value = fecha_aux[2]+"/"+fecha_aux[1]+"/"+fecha_aux[0];           				
           			} else {
           				document.getElementById("examenes_complementarios_id").value = 0;
           			}
            	}
            }
        });		
  	}

  	function cargarFotoExamenComplementario(ex_compl_id){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;

		if(ex_compl_id == null)
			ex_compl_id = document.getElementById("examenes_complementarios_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_fotos_examenes_complementarios',
           data:{consulta:consulta, paciente:paciente, ex_compl_id:ex_compl_id, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		           		
           		if(data.response == 1) {             		
           			// medico1/examenes_complementarios/7JddMlunI1SPXoVSZcv3sloguzyRpkgAWsbFk66c.jpeg           			
           			if(data.fotosExamenComplementario!=null && data.fotosExamenComplementario.foto.localeCompare('') != 0) {	
           			 	       		                
		                var imagen = document.getElementById("examenes_complementarios_foto");
		                $("#examenes_complementarios_foto").attr("src", "img/"+data.fotosExamenComplementario.foto);
						/*imagen.addEventListener("dblclick", function(e){
						  getFullscreen(this);
						},false);*/

						imagen.onclick = function() {                                                  
		                  onClickVerMI(data.fotosExamenComplementario.foto, 2);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                } 		                   
		                document.getElementById("examenes_complementarios_numero_foto").value = data.fotosExamenComplementario.numero;
		                var valorFoto = data.fotosExamenComplementario.numero+"/"+data.fotosExamenComplementario.numero;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valorFoto;
	           			document.getElementById("cantidad_fotos_modal_ec").value = valorFoto;			                       		                
		              } else {		              			                		                
		                $("#examenes_complementarios_foto").attr("src", "img/iconos/sin_imagen.jpg");                                
		                document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = "0/0";
		                document.getElementById("cantidad_fotos_modal_ec").value = "0/0";	
		              }
            	}            	
            }
        });		
  	}



  	function guardarExamenComplementario(mostrarSnack){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var ex_compl_id = document.getElementById("examenes_complementarios_id").value;			
		var ex_compl_numero = document.getElementById("examenes_complementarios_numero").value;			
		var solicito = document.getElementById("examenes_complementarios_solicito").value;			
		var respuesta = document.getElementById("examenes_complementarios_respuesta").value;
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_examenes_complementarios',
           data:{consulta:consulta, paciente:paciente, ex_compl_id:ex_compl_id,ex_compl_numero:ex_compl_numero, solicito:solicito, respuesta:respuesta, mostrarSnack:mostrarSnack, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1) {
           			//deshabilitarBotonesExComplementarios(true);
           			var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
           			var valor = data.examenesComplementarios.numero+"/"+data.numero_consulta_tope;
           			/*if(esNuevaConsulta == 1 || esNuevaConsulta == 3){
           				valor = data.examenesComplementarios.numero_consulta_tope+"/"+data.numero_consulta_tope;	
           			} */           			
           			document.getElementById("examenes_complementarios_actual_cantidad").value = valor;
         			document.getElementById("examenes_complementarios_id").value = data.examenesComplementarios.id;  			
           			if(data.mostrarSnack == 1)
           				mostrarSnackbar("EX. COMP GUARDADOS");
            	} else {
            		mostrarSnackbar("EX. COMP FALLO");
            	}
            	var btnNuevo = document.getElementById("nuevoExamenComplementarioButtonId");
            	btnNuevo.disabled = false;				
				btnNuevo.setAttribute('class', 'rodri_button margin_left_20px');	
            }            
        });	
  	}

  	function cargarExamenesComplementarios() {
		var paciente = document.getElementById("paciente_id").value;
		var consulta_actual = document.getElementById("consulta_id").value;
		$('#ex_comp_cantidad_fotos').val(1);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios',
           data:{paciente:paciente, consulta_actual:consulta_actual ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.numero != null && data.response == 1){           			
           			document.getElementById("examenes_complementarios_numero").value = data.numero;
           			if(data.numero != 0){
           				document.getElementById("seccion_examenes_complementarios").hidden = false;	           			
	        			document.getElementById("examenes_complementarios_solicito").value = data.examenes_complementarios.solicito;	        			       	
						document.getElementById("examenes_complementarios_respuesta").value = data.examenes_complementarios.respuesta;
						var ex = document.getElementById("examenes_complementarios_id").value = data.examenes_complementarios.id;
						var valor = data.numero+"/"+data.numero;
	           			document.getElementById("examenes_complementarios_actual_cantidad").value = valor;		           			
	           			var valorFoto = data.numero_fotos+"/"+data.numero_fotos;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valorFoto;
	           			document.getElementById("cantidad_fotos_modal_ec").value = valorFoto;			           			
	           			cargarFotoExamenComplementario(ex);
	           			//examenesComplementariosReadOnlyCheck();
           			} 
           		}
            }
        });	
	}

	 function cargarExamenesComplementariosConsulta() {
		var paciente = document.getElementById("paciente_id").value;
		var consulta = document.getElementById("consulta_id").value;			
		$('#ex_comp_cantidad_fotos').val(1);
		document.getElementById("seccion_examen_complementario_fotos").hidden = true;
		document.getElementById("agregarBotonNuevaFotoExCompId").hidden = true;
		document.getElementById("botonGuardarFotoExCompId").hidden = true;
		document.getElementById("nuevoExamenComplementarioButtonId").hidden = true;
		document.getElementById("guardarExamenComplementarioButtonId").hidden = true;
		document.getElementById("ex_compl_agregar_foto_text").hidden = true;		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios',
           data:{paciente:paciente, consulta:consulta ,_token: '{{csrf_token()}}'},
           	success:function(data) {                         		
           		if(data.numero != null && data.response == 1 && data.examenes_complementarios != null ) {           			
           			if((consulta >= data.examenes_complementarios.consulta_respuesta)  && (data.examenes_complementarios.consulta_respuesta !=0)){           			
           				document.getElementById("examenes_complementarios_resultado_text").hidden = false;
           				document.getElementById("examenes_complementarios_respuesta").hidden = false;
           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = false;           				
           			} else {           			
           				document.getElementById("examenes_complementarios_resultado_text").hidden = true;
           				document.getElementById("examenes_complementarios_respuesta").hidden = true;
           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = true;
           			}
           			document.getElementById("examenes_complementarios_numero_consulta").value = data.numero;
           			document.getElementById("examenes_complementarios_numero").value = data.numero;           			
           			if(data.numero != 0){
           				document.getElementById("seccion_examenes_complementarios").hidden = false;	       
           				document.getElementById("examenes_complementarios_nueva_consulta").hidden = false;    			
	        			document.getElementById("examenes_complementarios_solicito").value = data.examenes_complementarios.solicito;	        			       	
						document.getElementById("examenes_complementarios_respuesta").value = data.examenes_complementarios.respuesta;
						var ex = document.getElementById("examenes_complementarios_id").value = data.examenes_complementarios.id;
						var valor = data.numero+"/"+data.numero;
	           			document.getElementById("examenes_complementarios_actual_cantidad").value = valor;		           			
	           			var valorFoto = data.numero_fotos+"/"+data.numero_fotos;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valorFoto;	
	           			document.getElementById("cantidad_fotos_modal_ec").value = valorFoto;		           			
	           			cargarFotoExamenComplementario(ex);
           			} 
           		} else {
           			document.getElementById("examenes_complementarios_nueva_consulta").hidden = true;
           			document.getElementById("seccion_examenes_complementarios").hidden = true;	      
           		}
            }
        });	
	}

	function cargarExamenesComplementariosConsultaCargarFoto() {
		var paciente = document.getElementById("paciente_id").value;
		var consulta = document.getElementById("consulta_id").value;			
		$('#ex_comp_cantidad_fotos').val(1);		
		document.getElementById("nuevoExamenComplementarioButtonId").hidden = true;
		document.getElementById("guardarExamenComplementarioButtonId").hidden = true;
		examenesComplementariosReadOnly(true);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios',
           data:{paciente:paciente, consulta:consulta ,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		document.getElementById("seccion_examenes_complementarios").hidden = false;	       
           		document.getElementById("examenes_complementarios_nueva_consulta").hidden = false;    			
           		if(data.numero != null && data.response == 1 && data.examenes_complementarios != null){
           			if((consulta >= data.examenes_complementarios.consulta_respuesta)  && (data.examenes_complementarios.consulta_respuesta !=0)){           			
           				document.getElementById("examenes_complementarios_resultado_text").hidden = false;
           				document.getElementById("examenes_complementarios_respuesta").hidden = false;
           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = false;
           				document.getElementById("ex_compl_agregar_foto_text").hidden = false;
						document.getElementById("seccion_examen_complementario_fotos").hidden = false;
						document.getElementById("agregarBotonNuevaFotoExCompId").hidden = false;
						document.getElementById("botonGuardarFotoExCompId").hidden = false;
           			} else {           			
           				document.getElementById("examenes_complementarios_resultado_text").hidden = true;
           				document.getElementById("examenes_complementarios_respuesta").hidden = true;
           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = true;
           				document.getElementById("ex_compl_agregar_foto_text").hidden = true;
           				document.getElementById("seccion_examen_complementario_fotos").hidden = true;
						document.getElementById("agregarBotonNuevaFotoExCompId").hidden = true;
						document.getElementById("botonGuardarFotoExCompId").hidden = true;
           			}                   					
           			document.getElementById("examenes_complementarios_numero_consulta").value = data.numero;
           			document.getElementById("examenes_complementarios_numero").value = data.numero;
           			if(data.numero != 0){
           				
	        			document.getElementById("examenes_complementarios_solicito").value = data.examenes_complementarios.solicito;	        			       	
						document.getElementById("examenes_complementarios_respuesta").value = data.examenes_complementarios.respuesta;
						var ex = document.getElementById("examenes_complementarios_id").value = data.examenes_complementarios.id;
						var valor = data.numero+"/"+data.numero;
	           			document.getElementById("examenes_complementarios_actual_cantidad").value = valor;		           			
	           			var valorFoto = data.numero_fotos+"/"+data.numero_fotos;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valorFoto;	
	           			document.getElementById("cantidad_fotos_modal_ec").value = valorFoto;		           			
	           			cargarFotoExamenComplementario(ex);
           			} 
           		} 
            }
        });	
	}

	function examenesComplementariosAnteriorSiguiente(opcion){
		var ex_compl_id = document.getElementById("examenes_complementarios_id").value;
		var paciente = document.getElementById("paciente_id").value;	
		var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
		var consultaActual = document.getElementById("consulta_id").value;
		var consulta = 2;
		if(esNuevaConsulta == 1 || esNuevaConsulta == 4){
			var consulta = 1;
			//consulta = document.getElementById("consulta_id").value;
			//var numero_actual = document.getElementById("examenes_complementarios_numero_consulta").value;
		}/* else {
			var numero_actual = document.getElementById("examenes_complementarios_numero").value;
		}*/
		var numero_actual = document.getElementById("examenes_complementarios_numero").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;	
			//alert(consulta);	
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios_ant_sig',
           data:{paciente:paciente, consulta:consulta ,numero:numero, ex_compl_id:ex_compl_id, consultaActual:consultaActual ,_token: '{{csrf_token()}}'},
           	success:function(data){   
           	//alert(data.tope);           
           		if(data.response == 1) {           			
           			document.getElementById("examenes_complementarios_numero").value = data.examenesComplementarios.numero;
           			if(data.examenesComplementarios.numero != 0) {
           				document.getElementById("examenes_complementarios_id").value = data.examenesComplementarios.id;
           				document.getElementById("examenes_complementarios_numero").value = data.examenesComplementarios.numero;
	        			document.getElementById("examenes_complementarios_solicito").value = data.examenesComplementarios.solicito;	        				        		
						document.getElementById("examenes_complementarios_respuesta").value = data.examenesComplementarios.respuesta
						if(esNuevaConsulta == 0 || esNuevaConsulta == 4) {
							if((consultaActual >= data.examenesComplementarios.consulta_respuesta)&&(data.examenesComplementarios.consulta_respuesta!=0)){  			
		           				document.getElementById("examenes_complementarios_resultado_text").hidden = false;
		           				document.getElementById("examenes_complementarios_respuesta").hidden = false;
		           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = false;           				
		           				if(esNuevaConsulta == 4){
		           					mostrarPanelAdminFotos(false);
		           				}
		           			} else {           		
		           				mostrarPanelAdminFotos(true);	
		           				document.getElementById("examenes_complementarios_resultado_text").hidden = true;
		           				document.getElementById("examenes_complementarios_respuesta").hidden = true;
		           				document.getElementById("seccion_examen_complementario_fotos_mostrar").hidden = true;
		           			}		           			
							document.getElementById("examenes_complementarios_numero_consulta").value = data.numero;

							var valor = data.examenesComplementarios.numero+"/"+data.tope;
						} else {
							var valor = data.examenesComplementarios.numero+"/"+data.tope;
						}
	           			document.getElementById("examenes_complementarios_actual_cantidad").value = valor;		   				     
           				//examenesComplementariosReadOnlyCheck();
           				cargarFotoExamenComplementario(data.examenesComplementarios.id);
           			}
           		}
            }
        });
	}

	function examenesComplementariosAnteriorSiguienteFoto(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var ex_compl_id = document.getElementById("examenes_complementarios_id").value;			
		var numero_actual = document.getElementById("examenes_complementarios_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;

		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios_ant_sig_foto',
           data:{paciente:paciente, ex_compl_id:ex_compl_id ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.examenComplementarioFoto.numero != null && data.examenComplementarioFoto.numero != 0){           				
           				document.getElementById("examenes_complementarios_numero_foto").value = data.examenComplementarioFoto.numero;
	        			        				        								
						var valor = data.examenComplementarioFoto.numero+"/"+data.tope;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valor;	
	           			document.getElementById("cantidad_fotos_modal_ec").value = valor;		   			
           			}
           			if(data.examenComplementarioFoto.foto.localeCompare('') != 0) { 
           			               		                
		                var imagen = document.getElementById("examenes_complementarios_foto");
		                 $("#examenes_complementarios_foto").attr("src", "img/"+data.examenComplementarioFoto.foto);
						/*imagen.addEventListener("dblclick", function(e){
						  getFullscreen(this);
						},false);*/
						imagen.onclick = function() {                                                  
		                  onClickVerMI(data.examenComplementarioFoto.foto, 2);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                } 	
		              } else {		              	
		                $("#examenes_complementarios_foto").attr("src", "img/iconos/sin_imagen.jpg");                                 
		              }
           		}
            }
        });
	}

	function examenesComplementariosAnteriorSiguienteFotoModal(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var ex_compl_id = document.getElementById("examenes_complementarios_id").value;			
		var numero_actual = document.getElementById("examenes_complementarios_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_examenes_complementarios_ant_sig_foto',
           data:{paciente:paciente, ex_compl_id:ex_compl_id ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.examenComplementarioFoto.numero != 0){           				
           				document.getElementById("examenes_complementarios_numero_foto").value = data.examenComplementarioFoto.numero;
	        			        				        								
						var valor = data.examenComplementarioFoto.numero+"/"+data.tope;
	           			document.getElementById("examenes_complementarios_actual_cantidad_fotos").value = valor;
	           			document.getElementById("cantidad_fotos_modal_ec").value = valor;			   			
           			}
           			if(data.examenComplementarioFoto.foto.localeCompare('') != 0) { 
           			               		                
		                var imagen = document.getElementById("examenes_complementarios_foto");
		                 $("#examenes_complementarios_foto").attr("src", "img/"+data.examenComplementarioFoto.foto);
						/*imagen.addEventListener("dblclick", function(e){
						  getFullscreen(this);
						},false);*/
						imagen.onclick = function() {                                                  
		                  onClickVerMI(data.examenComplementarioFoto.foto, 2);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }
		                $("#img_src").attr("src", "img/"+data.examenComplementarioFoto.foto); 	
		              } else {	
		              	$("#img_src").attr("src", "img/iconos/sin_imagen.jpg"); 	              	
		                $("#examenes_complementarios_foto").attr("src", "img/iconos/sin_imagen.jpg");                                 
		              }
           		}
            }
        });
	}

	function mostrarPanelAdminFotos(valor){
		document.getElementById("ex_compl_agregar_foto_text").hidden = valor;
		document.getElementById("seccion_examen_complementario_fotos").hidden = valor;
		document.getElementById("agregarBotonNuevaFotoExCompId").hidden = valor;
		document.getElementById("botonGuardarFotoExCompId").hidden = valor;
	}

	function examenesComplementariosReadOnly(valor){
		document.getElementById("examenes_complementarios_solicito").readOnly = valor;	        				        		
		document.getElementById("examenes_complementarios_respuesta").readOnly = valor;
	}

	function examenesComplementariosReadOnlyCheck(){
		var solicito = document.getElementById("examenes_complementarios_solicito");
		var respuesta = document.getElementById("examenes_complementarios_respuesta");
		if(solicito!=null && solicito.value.localeCompare('')==0){
			solicito.readOnly = false;
		} else {
			solicito.readOnly = true;
		}

		if(respuesta!=null && respuesta.value.localeCompare('')==0){
			respuesta.readOnly = false;
		} else {
			respuesta.readOnly = true;
		}
	}

</script>