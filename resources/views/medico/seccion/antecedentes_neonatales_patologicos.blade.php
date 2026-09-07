@if($nueva_consulta == 4)
	<form method="post" action="{{ route('guardarantecedentesneonatales') }}" enctype="multipart/form-data">
		@csrf  
		<div class="row margin_left_5px">
			<p class="rodri_bold font_size_resumen">Antecedentes Neonatales</p>
		</div>	
		<input hidden id="es_nueva_consulta_cargar_fotos_an" name="es_nueva_consulta_cargar_fotos_an" value="4"/>
@else
	<form class="card background_panel" method="post" action="{{ route('guardarantecedentesneonatales') }}" enctype="multipart/form-data">
		@csrf  
@endif
	
	@if($consulta != null)
   	<input type="text" id="antecedentes_neonatales_consulta_id" name="antecedentes_neonatales_consulta_id" value="{{$consulta->id}}" hidden  />
   	@else
   	<input type="text" id="antecedentes_neonatales_consulta_id" name="antecedentes_neonatales_consulta_id" hidden />
   	@endif

   	@if($paciente != null)
   	<input type="text" id="antecedentes_neonatales_paciente_id" name="antecedentes_neonatales_paciente_id" value="{{$paciente->id}}" hidden />
   	@else
   	<input type="text" id="antecedentes_neonatales_paciente_id" name="antecedentes_neonatales_paciente_id" hidden />
   	@endif
	<div class="row margin_left_5px">
		<div class="col-md-6">		
			<div>
			<label>Nota:</label>			
		</div>
			<textarea class="form-control width450px_cel" id="antecedentes_neonatales_patologicos_nota" name="antecedentes_neonatales_patologicos_nota" rows="8" cols="60"></textarea>			
		</div>
		<div class="col-md-6">		
				<label id="ant_neonatales_agregar_foto_text">Agregar Foto:</label><br>
				<div id="seccion_antecedentes_neonatales_patologicos_fotos">
			        <input type="hidden" id="foto_ant_neonantales_id" name="foto_ant_neonantales_id" />
			        <input type="hidden" id="ant_neonantales_cantidad_fotos" name="ant_neonantales_cantidad_fotos" />
			        <input type="file" id="ant_neonatales_foto_1" name="ant_neonatales_foto_1" /> 
			     </div>
          		<br>
          		<button id="agregarBotonNuevaFotoAntNeoId" onclick="agregarBotonNuevaFotoAntNeo()" type="button" class="rodri_button_aceptar">Agregar</button> 		
          		<button id="guardarAntNeonatalesButtonId" type="submit" class="rodri_button_aceptar">Guardar</button> 			

     			<input hidden id="antecedentes_neonatales_numero_foto" name="antecedentes_neonatales_numero_foto" />
          		<div id="seccion_examen_complementario_ver_fotos" class="margin_top_12px">
					<a type="button" id="antecedentes_neonatales_foto_1" class="card-img-top img_little botonImage" alt="">
          				<img id="antecedentes_neonatales_foto" src="img/iconos/sin_imagen.jpg" class="card-img-top img_little botonImage">
          			</a>
					<div class="row margin_top_12px margin_left_60px">					
		    			<button type="button" onclick="antecedentesNeonatalesAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
				    	<input id="ant_neonatales_actual_cantidad_fotos" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
				    	<button type="button" onclick="antecedentesNeonatalesAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>				    					
					</div>		
				</div>
			</div>
	</div>	
</form>

<script type="text/javascript">

	function inicializarAntecedentesNeonatales(){
		var cantidadFotos = document.getElementById("ant_neonantales_cantidad_fotos").value;				
		$('#ant_neonantales_cantidad_fotos').val(1);				
	}

	function agregarBotonNuevaFotoAntNeo(){
	    var cantidadFotos = document.getElementById("ant_neonantales_cantidad_fotos").value;  //1      
	    var viejoValor = parseInt(cantidadFotos); // 1
	    var nuevoValor = parseInt(cantidadFotos) + 1;      
	  //  var btn = document.getElementById("foto-"+viejoValor);
	    var ultimoFile = document.getElementById("ant_neonatales_foto_"+viejoValor);
	    var f = ultimoFile.value;
	    if(f.localeCompare('') != 0){
	      $('#ant_neonantales_cantidad_fotos').val(nuevoValor);
	      var seccion = document.getElementById("seccion_antecedentes_neonatales_patologicos_fotos");                  
	      var input = document.createElement("INPUT");
	      input.type = 'file';
	      input.id = 'ant_neonatales_foto_'+nuevoValor;
	      input.name = 'ant_neonatales_foto_'+nuevoValor;
	      seccion.appendChild(input);
	    }
  	}

  	function guardarNotaAntecedentesNeonantales(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var nota = document.getElementById("antecedentes_neonatales_patologicos_nota").value;

  		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_nota_antecedentes_neonatales_patologicos',
           data:{consulta:consulta, paciente:paciente, nota:nota, _token: '{{csrf_token()}}'},
           	success:function(data) {               	
        		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ANTECEDENTES NEONATALES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });
  	}

  	function cargarAntecedentesNeonatales(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_neonantales_nota',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           			
           			document.getElementById("antecedentes_neonatales_patologicos_nota").value = data.response_data.nota;           	
           			cargarFotoAntecedentesNeonatales();
            	} else {
            		//mostrarSanckbar("ANTECEDENTES NEONATALES FALLO");            		
            	}
            }
        });	
	}

	function cargarAntecedentesNeonatalesConsulta(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		document.getElementById("agregarBotonNuevaFotoAntNeoId").hidden = true;
		document.getElementById("guardarAntNeonatalesButtonId").hidden = true;
		document.getElementById("ant_neonatales_agregar_foto_text").hidden = true;
		document.getElementById("ant_neonatales_foto_1").hidden = true;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_neonantales_nota',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data != null && data.response == 1){           			
           			document.getElementById("antecedentes_neonatales_patologicos_nota").value = data.response_data.nota;           	
           			cargarFotoAntecedentesNeonatales();
            	} else {
            		//mostrarSanckbar("ANTECEDENTES NEONATALES FALLO");            		
            	}
            }
        });	
	}

	function cargarFotoAntecedentesNeonatales(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		//var ex_compl_id = document.getElementById("examenes_complementarios_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_fotos_antecedentes_neonantales',
           data:{consulta:consulta, paciente:paciente, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1) {           		           		      			           			
           			// medico1/examenes_complementarios/7JddMlunI1SPXoVSZcv3sloguzyRpkgAWsbFk66c.jpeg           			
           			if(data.fotosAntecedentesNeonatales !=null && data.fotosAntecedentesNeonatales.foto.localeCompare('') != 0) {                           		
           				var imagen = document.getElementById("antecedentes_neonatales_foto");
		                 $("#antecedentes_neonatales_foto").attr("src", "img/"+data.fotosAntecedentesNeonatales.foto);
												
		                var img = document.getElementById("antecedentes_neonatales_foto");		                
		                $("#antecedentes_neonatales_foto").attr("src", "img/"+data.fotosAntecedentesNeonatales.foto);
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.fotosAntecedentesNeonatales.foto, 1);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }     
		                
		                document.getElementById("antecedentes_neonatales_numero_foto").value = data.fotosAntecedentesNeonatales.numero;
		                var valorFoto = data.fotosAntecedentesNeonatales.numero+"/"+data.fotosAntecedentesNeonatales.numero;
	           			document.getElementById("ant_neonatales_actual_cantidad_fotos").value = valorFoto;		                       		                
	           			document.getElementById("cantidad_fotos_modal_anp").value = valorFoto;		                       		                
		              } else {		              			            		              
		                $("#antecedentes_neonatales_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		                document.getElementById("ant_neonatales_actual_cantidad_fotos").value = "0/0";
		                document.getElementById("cantidad_fotos_modal_anp").value = "0/0";
		              }
            	}            	
            }
        });		
  	}

  	function antecedentesNeonatalesAnteriorSiguienteFoto(opcion){
		var paciente = document.getElementById("paciente_id").value;		
		var numero_actual = document.getElementById("antecedentes_neonatales_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_neonatales_ant_sig_foto',
           data:{paciente:paciente ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.response_data.numero != 0){           				
           				document.getElementById("antecedentes_neonatales_numero_foto").value = data.response_data.numero;
	        			        				        								
						var valor = data.response_data.numero+"/"+data.cantidadFotos;
	           			document.getElementById("ant_neonatales_actual_cantidad_fotos").value = valor;
	           			document.getElementById("cantidad_fotos_modal_anp").value = valor;		   			
           			}
           			if(data.response_data.foto.localeCompare('') != 0) {                		                
		                var img = document.getElementById("antecedentes_neonatales_foto");		                
		                $("#antecedentes_neonatales_foto").attr("src", "img/"+data.response_data.foto);
		                /*img.onclick = function() {                                                  
		                  onClickVer(data.response_data.foto);
		                }*/
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.response_data.foto, 1);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }                 		                
		              } else {		              	
		                $("#antecedentes_neonatales_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		              }
           		}
            }
        });
	}

	 function antecedentesNeonatalesAnteriorSiguienteFotoModal(opcion){
		var paciente = document.getElementById("paciente_id").value;		
		var numero_actual = document.getElementById("antecedentes_neonatales_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_neonatales_ant_sig_foto',
           data:{paciente:paciente ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.response_data.numero != 0){           				
           				document.getElementById("antecedentes_neonatales_numero_foto").value = data.response_data.numero;	        						
						var valor = data.response_data.numero+"/"+data.cantidadFotos;
	           			document.getElementById("ant_neonatales_actual_cantidad_fotos").value = valor;
	           			document.getElementById("cantidad_fotos_modal_anp").value = valor;		   			
           			}
           			if(data.response_data.foto.localeCompare('') != 0) {                		                
		                var img = document.getElementById("antecedentes_neonatales_foto");		                
		                $("#antecedentes_neonatales_foto").attr("src", "img/"+data.response_data.foto);		                
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.response_data.foto, 1);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }                 		                		                		               
		                $("#img_src").attr("src", "img/"+data.response_data.foto);		                                		               
		              } else {		              	
		                $("#img_src").attr("src", "img/iconos/sin_imagen.jpg");
		                $("#antecedentes_neonatales_foto").attr("src", "img/"+data.response_data.foto);	                
		              }
           		}
            }
        });
	}

	function antecedentesNeonatalesReadOnly(valor){
		document.getElementById("antecedentes_neonatales_patologicos_nota").readOnly = valor;
	}

	function getFullscreen(element){
	  if(element.requestFullscreen) {
	      element.requestFullscreen();
	    } else if(element.mozRequestFullScreen) {
	      element.mozRequestFullScreen();
	    } else if(element.webkitRequestFullscreen) {
	      element.webkitRequestFullscreen();
	    } else if(element.msRequestFullscreen) {
	      element.msRequestFullscreen();
	    }
	}

</script>

