
@extends('plantillas/plantilla_medico')
<!--
@if($consulta != null && $consulta->activo == 2)
  @section('title_header','Nueva Consulta Foto')
@else
  @section('title_header','Consulta')
@endif
-->
@include('utils.manipular_imagenes')
@section('contenedor')


<form class="card background_panel" method="post" action="{{ route('guardarnuevaconsultafoto') }}" enctype="multipart/form-data">
@if($consulta!=null)
  <input hidden class="form-control" type="text" name="consulta_id" id="consulta_id" value="{{$consulta->id}}" />   
@else
  <input hidden class="form-control" type="text" name="consulta_id" id="consulta_id" value="-1" />   
@endif

@if($paciente!=null)
  <input hidden class="form-control" type="text" name="paciente_id" id="paciente_id" value="{{$paciente->id}}" />   
  <input hidden class="form-control" type="text" name="paciente_nombre" id="paciente_nombre" value="{{$paciente->nombre}}" />   
  <input hidden class="form-control" type="text" name="paciente_apellido" id="paciente_apellido" value="{{$paciente->apellido}}" />   
@else
  <input hidden class="form-control" type="text" name="paciente_id" id="paciente_id" value="-1" />   
@endif
<input hidden class="form-control" type="text" name="es_nueva_consulta" id="es_nueva_consulta" value="{{$nueva_consulta}}" /> 

	@csrf  
	<div class="col-md-6">		
		<label>Agregar Foto:</label><br>
		<div id="seccion_nueva_consulta_fotos">
	        <input type="hidden" id="foto_ant_neonantales_id2" name="foto_ant_neonantales_id2" />	        
	        <input type="hidden" id="nueva_consulta_fotos_cantidad_fotos" name="nueva_consulta_fotos_cantidad_fotos" value="1" />
	        <input type="file" id="nueva_consulta_foto_1" name="nueva_consulta_foto_1" /> 
	     </div>
  		<br>
  		<button id="agregarBotonNuevaFotoAntNeoId" onclick="agregarBotonNuevaFotoNCF()" type="button" class="rodri_button_aceptar">Agregar</button> 		
  		<button id="guardarAntNeonatalesButtonId" type="submit" class="rodri_button_aceptar">Guardar</button> 

		<input hidden id="nueva_consulta_foto_numero_foto" name="nueva_consulta_foto_numero_foto" />
  		<div id="seccion_examen_complementario_ver_fotos" class="margin_top_12px">
			<a type="button" id="antecedentes_neonatales_foto_1" class="card-img-top img_little botonImage" alt="">
      			<img id="nueva_consulta_foto" src="img/iconos/sin_imagen.jpg" class="card-img-top img_little botonImage">
      		</a>
			<div class="row margin_top_12px margin_left_60px">					
    			<button type="button" onclick="nuevaConsultaFotoAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
		    	<input id="nueva_consulta_foto_actual_cantidad_fotos" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
		    	<button type="button" onclick="nuevaConsultaFotoAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>				    					
			</div>		
		</div>
	</div>
</form>

<script type="text/javascript">

	function agregarBotonNuevaFotoNCF(){
	    var cantidadFotos = document.getElementById("nueva_consulta_fotos_cantidad_fotos").value;  //1      
	    var viejoValor = parseInt(cantidadFotos); // 1
	    var nuevoValor = parseInt(cantidadFotos) + 1;      
	  //  var btn = document.getElementById("foto-"+viejoValor);
	    var ultimoFile = document.getElementById("nueva_consulta_foto_"+viejoValor);
	    var f = ultimoFile.value;
	    if(f.localeCompare('') != 0){
	      $('#nueva_consulta_fotos_cantidad_fotos').val(nuevoValor);
	      var seccion = document.getElementById("seccion_nueva_consulta_fotos");                  
	      var input = document.createElement("INPUT");
	      input.type = 'file';
	      input.id = 'nueva_consulta_foto_'+nuevoValor;
	      input.name = 'nueva_consulta_foto_'+nuevoValor;
	      seccion.appendChild(input);
	    }
  	}

  	function cargarFotosNuevaConsulta(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		//var ex_compl_id = document.getElementById("examenes_complementarios_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_fotos_nueva_consulta',
           data:{consulta:consulta, paciente:paciente, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1) {           		           		      			           			
           			// medico1/examenes_complementarios/7JddMlunI1SPXoVSZcv3sloguzyRpkgAWsbFk66c.jpeg           			
           			if(data.consultaFotos !=null && data.consultaFotos.foto.localeCompare('') != 0) {                           				
		                var img = document.getElementById("nueva_consulta_foto");		                
		                $("#nueva_consulta_foto").attr("src", "img/"+data.consultaFotos.foto);
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.consultaFotos.foto, 3);
		                }    
		                document.getElementById("nueva_consulta_foto_numero_foto").value = data.consultaFotos.numero;
		                var valorFoto = data.consultaFotos.numero+"/"+data.consultaFotos.numero;

	           			document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valorFoto;	
	           			document.getElementById("cantidad_fotos_modal_ncf").value = valorFoto;	                       		                
		              } else {		              			                		                
		                $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		                document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = "0/0";
		                document.getElementById("cantidad_fotos_modal_ncf").value = "0/0";
		              }
            	}            	
            }
        });		
  	}

  	function nuevaConsultaFotoAnteriorSiguienteFoto(opcion){
		var paciente = document.getElementById("paciente_id").value;		
		var consulta = document.getElementById("consulta_id").value;		
		var numero_actual = document.getElementById("nueva_consulta_foto_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_fotos_nueva_consulta_ant_sig_foto',
           data:{paciente:paciente, consulta:consulta ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.response_data.numero != 0){           				
           				document.getElementById("nueva_consulta_foto_numero_foto").value = data.response_data.numero;
	        			        				        								
						var valor = data.response_data.numero+"/"+data.cantidadFotos;
	           			document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valor;		   			
	           			document.getElementById("cantidad_fotos_modal_ncf").value = valor;
           			}
           			if(data.response_data.foto.localeCompare('') != 0) {                		                
		                var img = document.getElementById("nueva_consulta_foto");		                
		                $("#nueva_consulta_foto").attr("src", "img/"+data.response_data.foto);
		                
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.response_data.foto, 3);
		                }                		                
		              } else {		              	
		                $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		              }
           		}
            }
        });
	}

	function nuevaConsultaFotoAnteriorSiguienteFotoModal(opcion){
		var paciente = document.getElementById("paciente_id").value;		
		var consulta = document.getElementById("consulta_id").value;		
		var numero_actual = document.getElementById("nueva_consulta_foto_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_fotos_nueva_consulta_ant_sig_foto',
           data:{paciente:paciente, consulta:consulta ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.response_data.numero != 0){           				
           				document.getElementById("nueva_consulta_foto_numero_foto").value = data.response_data.numero;
	        			        				        								
						var valor = data.response_data.numero+"/"+data.cantidadFotos;
	           			document.getElementById("nueva_consulta_foto_actual_cantidad_fotos").value = valor;		   			
	           			document.getElementById("cantidad_fotos_modal_ncf").value = valor;
           			}
           			if(data.response_data.foto.localeCompare('') != 0) {                		                
		                var img = document.getElementById("nueva_consulta_foto");		                
		                $("#nueva_consulta_foto").attr("src", "img/"+data.response_data.foto);
		                
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.response_data.foto, 3);
		                }
		                $("#img_src").attr("src", "img/"+data.response_data.foto); 	
		              } else {	
		              	$("#img_src").attr("src", "img/iconos/sin_imagen.jpg"); 	                		                
		                $("#nueva_consulta_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		              }
           		}
            }
        });
	}

  function cargarTitleHeader(){
    var nombre = document.getElementById("paciente_nombre").value;
    var apellido = document.getElementById("paciente_apellido").value;
    document.getElementById("static_header").hidden = true;
    document.getElementById("static_header_2").innerHTML = "Nueva Consulta Foto";
    document.getElementById("static_header_nombre").innerHTML = apellido+", "+nombre;    
  }

  window.onload=function() {
  	mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
  	var esNuevaConsulta = document.getElementById("es_nueva_consulta").value;
  	if(esNuevaConsulta == 0 || esNuevaConsulta == 3) {
  		cargarFotosNuevaConsulta();
  	}
  	cargarTitleHeader();  	  	
  }

</script>

@endsection