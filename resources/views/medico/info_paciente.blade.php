<form class="card background_panel">
	<div class="row">                      
		  @csrf
		<div class="row">
			  <div class="margin_left_20px">
				  	<small>DNI</small>      
				   	@if($paciente != null && $paciente->dni != null)
				   		<input type="text" class="form-control input_width_150px" id="dni" name="dni"  value="{{$paciente->dni}}" readonly/>
				   	@else
				   		<input type="text" class="form-control input_width_150px" id="dni" name="dni"  value="" readonly/>
				   	@endif
			  </div>

			  <div class="margin_left_20px">
				  <small>Nombre</small>      
				  @if($paciente != null && $paciente->nombre != null)
				  	<input type="text" class="form-control input_width_250px" id="nombre" name="nombre" value="{{$paciente->nombre}}" readonly/>
				  @else
				  	<input type="text" class="form-control input_width_250px" id="nombre" name="nombre"   value="" readonly />
				  @endif
			  </div>

			  <div class="margin_left_20px">
				  <small>Apellido</small>      
				  @if($paciente != null && $paciente->apellido != null)
				  	<input type="text" class="form-control input_width_250px" id="apellido" name="apellido"  value="{{$paciente->apellido}}" readonly/>
				  @else
				  	<input type="text" class="form-control input_width_250px" id="apellido" name="apellido"  readonly/>
				  @endif
			  </div>

			  <div class="margin_left_20px">
			  	  <small>Fecha Nacimiento</small>
			  	  @if($paciente != null && $paciente->fecha_nacimiento != null)
				  	<input type="text" class="form-control input_width_250px" id="fecha_nacimiento" name="fecha_nacimiento"  value="{{$paciente->fecha_nacimiento}}" readonly />
				  @else      
				  <input type="text" class="form-control input_width_150px" id="fecha_nacimiento" name="fecha_nacimiento"  readonly/>
				  @endif
			 </div>

			 <div class="margin_left_20px">
				  <small>Sexo</small>      
				  @if($paciente != null && $paciente->sexo != null)
				  	<input hidden id="sexo_paciente" name="sexo_paciente" value="{{$paciente->sexo}}">
				  	<div class="row">
				  	<div class="custom-control custom-radio margin_left_10px_cel">
				  		@if($paciente != null && $paciente->sexo == "M")
				  			<input type="radio" id="sexo_m" name="sexo" class="custom-control-input" checked readonly>
				  		@else
				  			<input type="radio" id="sexo_m" name="sexo" class="custom-control-input" readonly>
				  		@endif
				  		<label class="custom-control-label" for="sexo_m">M</label>
					</div>
					<div class="custom-control custom-radio">
						 @if($paciente != null && $paciente->sexo == "F")
						 	<input type="radio" id="sexo_f" name="sexo" class="custom-control-input" checked readonly>
						 @else
						 	<input type="radio" id="sexo_f" name="sexo" class="custom-control-input" readonly>
						 @endif
						 <label class="custom-control-label margin_left_5px" for="sexo_f">F</label>
					</div>
				   </div>
				  @else
				  <div class="row">
				  	<div class="custom-control custom-radio">
				  		<input type="radio" id="sexo_m" name="sexo" class="custom-control-input" readonly>
				  		<label class="custom-control-label" for="sexo_m">M</label>
					</div>
					<div class="custom-control custom-radio">
						 <input type="radio" id="sexo_f" name="sexo" class="custom-control-input" readonly>
						 <label class="custom-control-label margin_left_5px" for="sexo_f">F</label>
					</div>
				   </div>
				   @endif				
			  </div>

			 <div class="margin_left_20px">	  
			 	 <small>Hermanos</small> 
			 	 @if($paciente != null && $paciente->cantidad_hermanos != null)     
			  	 	<input type="text" class="form-control input_width_50px" id="hermanos" name="hermanos" value="{{$paciente->cantidad_hermanos}}" readonly />        
			  	 @else
			  		<input type="text" class="form-control input_width_50px" id="hermanos" name="hermanos"  readonly />        
			     @endif
			 </div>

		</div>

		<div class="row">
			<div class="margin_left_20px">
			  <small>Edad</small>     
			  @if($paciente != null && $paciente->fecha_nacimiento != null)   
			  	<input type="text" class="form-control input_width_350px" id="edad" name="edad"  readonly />
			  @else
			  	<input type="text" class="form-control input_width_350px" id="edad" name="edad"   readonly/>
			  @endif
			</div>

			 <div class="margin_left_20px">
			  <small>Localidad</small>     
			  @if($paciente != null && $paciente->localidad != null)   
			  	<input type="text" class="form-control input_width_350px" id="localidad" name="localidad"  value="{{$paciente->localidad}}" readonly />
			  @else
			  	<input type="text" class="form-control input_width_350px" id="localidad" name="localidad"   readonly/>
			  @endif
			</div>

			<div class="margin_left_20px">
			  <small>Domicilio</small>   
			   @if($paciente != null && $paciente->domicilio != null)     
			   	<input type="text" class="form-control input_width_350px" id="domicilio" name="domicilio"  value="{{$paciente->domicilio}}"  readonly/>
			   @else
			  	<input type="text" class="form-control input_width_350px" id="domicilio" name="domicilio" readonly />
			  @endif
			</div>
		</div>

		<div class="row">
			<div class="margin_left_20px">
			  <small>Mail</small>      
			  @if($paciente != null && $paciente->mail != null)     
			  	<input type="text" class="form-control input_width_350px" id="mail" name="mail" value="{{$paciente->mail}}" readonly />
			  @else
			  	<input type="text" class="form-control input_width_350px" id="mail" name="mail"  readonly />
			  @endif
			</div>

			<div class="margin_left_20px">
			  <small>Telefono</small>      
			  @if($paciente != null && $paciente->telefono != null)     
			  	<input type="text" class="form-control input_width_150px" id="telefono" name="telefono"  value="{{$paciente->telefono}}" readonly />
			  @else
			   <input type="text" class="form-control input_width_150px" id="telefono" name="telefono"   readonly />
			  @endif
			</div>

			 <div class="margin_left_20px">  
			  <small>Nombre Madre</small>
			  @if($paciente != null && $paciente->nombre_madre != null)     
			 	<input type="text" class="form-control input_width_250px" id="nombre_madre" name="nombre_madre" value="{{$paciente->nombre_madre}}" readonly />
			  @else      
			  	<input type="text" class="form-control input_width_250px" id="nombre_madre" name="nombre_madre"  readonly />
			  @endif
			</div>

			<div class="margin_left_20px">
			  <small>Nombre Padre</small>      
			  @if($paciente != null && $paciente->nombre_padre != null)     
			  	<input type="text" class="form-control input_width_250px" id="nombre_padre" name="nombre_padre" value="{{$paciente->nombre_padre}}" readonly />
			  @else
			 	 <input type="text" class="form-control input_width_250px" id="nombre_padre" name="nombre_padre"  readonly />
			  @endif
			</div>	  	     

		</div>

		<div class="row">
			<div class="margin_left_20px">
			  <small>Obra Social</small>      
			  @if($paciente != null && $paciente->obra_social != null)    
			  	<input type="text" class="form-control" id="obrasocial" name="obra_social" value="{{$paciente->obra_social}}" readonly />
			  @else
			  	<input type="text" class="form-control" id="obrasocial" name="obra_social"  readonly />
			  @endif
			</div>

			<div class="margin_left_20px">
			  <small>N°Afiliado</small>      
			  @if($paciente != null && $paciente->numero_afiliado != null)    
			  	<input type="text" class="form-control" id="numero_afiliado" name="numero_afiliado" value="{{$paciente->numero_afiliado}}" readonly />
			  @else
			  	<input type="text" class="form-control" id="numero_afiliado" name="numero_afiliado" readonly />
			  @endif
			</div>  

			<div class="margin_left_20px">
			  <small>Tipo Plan</small>      
			  @if($paciente != null && $paciente->obra_social_plan != null)    
			  	<input type="text" class="form-control" id="plan_obra_social" name="plan_obra_social" value="{{$paciente->obra_social_plan}}" readonly />
			  @else
			  	<input type="text" class="form-control" id="plan_obra_social" name="plan_obra_social" readonly  />
			  @endif
			</div>

			<div class="margin_left_20px margin_top_20px">				
			  <h4 class="margin_top_5px"><b><a onclick="clickFamiligrama()" type="button"><u>Familigrama</u></a></b></h4>	   	
			</div>			
		</div>

	</div>	
</form>


@include('modal.modal_familigrama')

<script type="text/javascript">
	function calcularEdad(){		
        
		var edad_aux_array = calcularEdad3(document.getElementById("fecha_nacimiento").value);
		var fechaMostrarAux = document.getElementById("fecha_nacimiento").value;
		var fechaMostrarArray = fechaMostrarAux.split("-");
		var nuevaFechaMostrar = fechaMostrarArray[2]+"/"+fechaMostrarArray[1]+"/"+fechaMostrarArray[0];	
		document.getElementById("fecha_nacimiento").value = nuevaFechaMostrar;

		var edad_aux = edad_aux_array.split("-");
		var edad = '';
		if(edad_aux[0] != 0){
			edad = edad + edad_aux[0];
			if(edad == 1)
				edad +=" año, "
			else
				edad +=" años, "
		}
		if(edad_aux[1] != 0){
			edad = edad + edad_aux[1]+" meses y ";
		}
		edad = edad + edad_aux[2]+" dias ";	
		document.getElementById("edad").value = edad; 
	}


	function calcularEdad2(fecha) {		
       	
        var values = fecha.split('-');
        var dia = parseInt(values[2]);
        var mes = parseInt(values[1]);
        var ano = parseInt(values[0]);

        // cogemos los valores actuales
        var fecha_hoy = new Date();
        var ahora_ano = fecha_hoy.getYear();
        var ahora_mes = fecha_hoy.getMonth() + 1;
        var ahora_dia = fecha_hoy.getDate();                  	                  

        // realizamos el calculo
        var edad = (ahora_ano + 1900) - ano;  
        //alert(ahora_ano + 1900 - parseInt(ano));             
        if (ahora_mes < mes) {
            edad--;
        }
        if ((mes == ahora_mes) && (ahora_dia < dia)) {
            edad--;
        }
        if (edad > 1900) {
            edad -= 1900;
        }

        // calculamos los meses
        var meses = 0;

        if (ahora_mes > mes && dia > ahora_dia)
            meses = ahora_mes - mes - 1;
        else if (ahora_mes > mes)
            meses = ahora_mes - mes
        if (ahora_mes < mes && dia < ahora_dia)
            meses = 12 - (mes - ahora_mes);
        else if (ahora_mes < mes)
            meses = 12 - (mes - ahora_mes + 1);
        if (ahora_mes == mes && dia > ahora_dia)
            meses = 11;

        // calculamos los dias
        var dias = 0;
        if (ahora_dia > dia)
            dias = ahora_dia - dia;
        if (ahora_dia < dia) {           
            ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
            dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
        }
        //alert("edad2 "+anios + "-" + meses + "-" + dias);
        return edad + "-" + meses + "-" + dias;
    }

    function esNumero(strNumber) {
	    if (strNumber == null) return false;
	    if (strNumber == undefined) return false;
	    if (typeof strNumber === "number" && !isNaN(strNumber)) return true;
	    if (strNumber == "") return false;
	    if (strNumber === "") return false;
	    var psInt, psFloat;
	    psInt = parseInt(strNumber);
	    psFloat = parseFloat(strNumber);
	    return !isNaN(strNumber) && !isNaN(psFloat);
	}

	function clickFamiligrama() {		
		var paciente = document.getElementById("paciente_id").value;
		//var ex_compl_id = document.getElementById("examenes_complementarios_id").value;					
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_foto_familigrama',
           data:{paciente:paciente, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1) {           		           		      			           			           			
           			if(data.familigrama !=null && data.familigrama.foto.localeCompare('') != 0) {                           		
           				var imagen = document.getElementById("familigrama_foto");
		                 $("#familigrama_foto").attr("src", "img/"+data.familigrama.foto);
						
		                var img = document.getElementById("familigrama_foto");		                
		                $("#familigrama_foto").attr("src", "img/"+data.familigrama.foto);
		                img.onclick = function() {                                                  
		                  onClickVerMI(data.familigrama.foto, 1);	
		                  document.getElementById("panelAvanzarMI").hidden = true;
		                  document.getElementById("panelCantidadMI").hidden = true;
		                  
		                  $('#modal_familigrama_cargar_fotos').modal('hide');  	                  
		                }     		                
		              } else {		              			            		              
		                $("#familigrama_foto").attr("src", "img/iconos/sin_imagen.jpg");                		                
		              }
            	}            	
            }
        });
        var es_nueva_consulta = document.getElementById("es_nueva_consulta").value;
        if(es_nueva_consulta == 0){
        	document.getElementById("familigrama_agregar_foto_text").hidden = true;
        	document.getElementById("familigrama_foto_1").hidden = true;
        	document.getElementById("guardarFamiligramaButtonId").hidden = true;        	
        	document.getElementById("modalTitleFamiligrama").innerHTML = "Ver Foto Familigrama";
        }
		var paciente_id = document.getElementById("paciente_id").value;
		$('#paciente_id_fm').val(paciente_id);  
		$('#modal_familigrama_cargar_fotos').modal('show');  
	}


	function calcularEdad3(fecha) {
 
    var FechaNacimiento =  fecha; //document.getElementById('miEdad').value;
 
    var fechaNace = new Date(FechaNacimiento);
    var fechaActual = new Date()
 
    var mes = fechaActual.getMonth();
    var dia = fechaActual.getDate();
    var año = fechaActual.getFullYear();
 
    fechaActual.setDate(dia);
    fechaActual.setMonth(mes);
    fechaActual.setFullYear(año);
 
    dias_tranascurridos = Math.floor(((fechaActual - fechaNace) / (1000 * 60 * 60 * 24) ));
 
	 anios = Math.floor(dias_tranascurridos/365);
     meses_1 = dias_tranascurridos % 365;
     meses = Math.floor(meses_1/30);
     dias =meses_1%30;
 
    /*document.getElementById('Edadcalculada').innerHTML = 'La edad es: ' + dias_tranascurridos + ' Dias';
	document.getElementById('anios').value = anios;
	document.getElementById('meses').value = meses;
	document.getElementById('dias').value = dias;*/
   // alert(anios + "-" + meses + "-" + dias);
 	return anios + "-" + meses + "-" + dias;

}


</script>

