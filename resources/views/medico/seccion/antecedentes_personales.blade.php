@if($nueva_consulta == 4)
	<form method="post" action="{{ route('guardarinternacionesfotos') }}" enctype="multipart/form-data">
		@csrf 		
		<div class="row margin_left_5px">
			<p class="rodri_bold font_size_resumen">Antecedentes Personales</p>
		</div>	
		<input hidden id="es_nueva_consulta_cargar_fotos_ap" name="es_nueva_consulta_cargar_fotos_ap" value="4"/>
@else
	<form class="card background_panel" method="post" action="{{ route('guardarinternacionesfotos') }}" enctype="multipart/form-data">
		@csrf  
@endif
	<div class="row margin_left_5px">		
		<label>Enfermedad Actual:</label>
	</div>
	<div class="row margin_left_5px">		
		<input type="text" class="form-control input_width_450px" id="ant_personales_enfermedad_actual" name="ant_personales_enfermedad_actual"/>
	</div>
	<input hidden id="ant_personales_numero">
	<div class="row margin_top_5px margin_left_5px">		
	 	<div class="margin_top_5px">
			<label>Internaciones:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px ">
			  		<input onclick="clickInternacionesSi()" type="radio" id="ant_personales_internaciones_si" name="ant_personales_internaciones_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_internaciones_si">Si</label>
				</div>
				<div class="custom-control custom-radio ">
					 <input onclick="clickInternacionesNo()" type="radio" id="ant_personales_internaciones_no" name="ant_personales_internaciones_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_internaciones_no">No</label>
				</div>													
			</div>			
		</div>
	</div>

	<div hidden id="seccion_ant_personales_si">
		<div class="row">
			<div class="col-md-6">		
				<div class="row margin_left_5px">
					<div class="margin_left_20px">
				  		<small>Motivo:</small>      
				  		<input type="text" class="form-control input_width_250px" id="ant_personales_internaciones_si_motivo" name="ant_personales_internaciones_si_motivo"  placeholder="Motivo"/>
			  		</div>
				</div>
				<div class="row margin_left_5px">
					<div class="margin_left_20px">
				  		<small>Lugar:</small>      
				  		<input type="text" class="form-control input_width_250px" id="ant_personales_internaciones_si_lugar" name="ant_personales_internaciones_si_lugar"  placeholder="Lugar"/>
			  		</div>
				</div>
				<div class="row margin_left_5px">
					<div class="margin_left_20px">
				  		<small>Duración:</small>      
				  		<input type="text" class="form-control input_width_250px" id="ant_personales_internaciones_si_duracion" name="ant_personales_internaciones_si_duracion"  placeholder="Duración"/>
			  		</div>
				</div>
				<div class="row margin_left_5px">
					<div class="margin_left_20px">
				  		<small>Indicación al alta:</small>   
				  		<div class="row margin_left_5px">   
				  		<textarea class="form-control width450px_cel" id="ant_personales_internaciones_si_indicacion_alta" name="ant_personales_internaciones_si_indicacion_alta" rows="5" cols="60" placeholder="Indicación al alta"></textarea>		
				  	</div>
			  		</div>
				</div>
			</div>
			@if($consulta != null)
			<input type="text" id="internaciones_consulta_id" name="internaciones_consulta_id" value="{{$consulta->id}}"  hidden />
			@endif
			<input type="text" id="internaciones_paciente_id" name="internaciones_paciente_id" value="{{$paciente->id}}"  hidden />
			<input hidden id="internaciones_id" name="internaciones_id" />
			<input hidden id="antecedentes_personales_id" name="antecedentes_personales_id" />
			<input hidden id="internaciones_numero" name="internaciones_numero"/>
			<input hidden id="internaciones_numero_foto" name="internaciones_numero_foto"/>
			
			<div class="col-md-6">		
				<label id="internaciones_agregar_foto_text">Agregar Foto:</label><br>
				<div id="seccion_internaciones_fotos">
			        <input type="hidden" id="foto_internaciones_id" name="foto_internaciones_id" />
			        <input type="hidden" id="internaciones_cantidad_fotos" name="internaciones_cantidad_fotos" />
			        <input disabled type="file" id="internaciones_foto_1" name="internaciones_foto_1" /> 
			    </div>
          		<br>
          		<button disabled id="agregarBotonNuevaFotoInternacionesId" onclick="agregarBotonNuevaFotoInt()" type="button" class="rodri_button_aceptar">AGREGAR</button> 			
          		<button disabled id="botonGuardarFotoInternacionId" type="submit" class="rodri_button_aceptar margin_left_20px">GUARDAR</button>

          		<div id="seccion_internaciones_ver_fotos" class="margin_top_12px">
					<a type="button" id="internaciones_foto_1" class="card-img-top img_little botonImage" alt="">
	          			<img id="internaciones_foto" src="img/iconos/sin_imagen.jpg" class="card-img-top img_little botonImage">
	          		</a>
					<div class="row margin_top_12px margin_left_60px">					
			    			<button type="button" onclick="internacionesAnteriorSiguienteFoto(0)" class="rodri_button_aceptar_si"><</button>
					    	<input id="internaciones_actual_cantidad_fotos" disabled class="letrasblancas input_width_50px sinBackground margin_left_20px" value="0/0"></input>
					    	<button type="button" onclick="internacionesAnteriorSiguienteFoto(1)" class="rodri_button_aceptar_si">></button>				    					
					</div>		
				</div>
			</div>
			
		</div>

		<div class="row contenedor3 margin_top_20px">
			<div class="contenido3">
		    	<button type="button" onclick="internacionesAnteriorSiguiente(0)" class="rodri_button_aceptar_cel margin_left_cel3"><</button>
		    	<input id="internaciones_actual_cantidad" disabled class="letrasblancas input_width_50px sinBackground margin_left_20px" value="0/0"></input>
		    	<button type="button" onclick="internacionesAnteriorSiguiente(1)" class="rodri_button_aceptar_cel input_width_appx_cel">></button>
		    	<button id="nuevoInternacionesButtonId" type="button" onclick="nuevoInternaciones()" class="rodri_button_aceptar margin_left_20px">NUEVA</button>
		    	<button id="guardarInternacionesButtonId" type="button" onclick="guardarInternaciones()" class="rodri_button_aceptar margin_left_20px">GUARDAR</button>
			</div>
		</div>		

	</div> <!-- FIN SECCION ANT PERSONALES SI -->

	<div class="row margin_top_5px margin_left_5px">		
	 	<div>
			<label class="margin_left_5px">Alergias:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickAlergiasSi()" type="radio" id="ant_personales_alergias_si" name="ant_personales_alergias_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_alergias_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickAlergiasNo()" type="radio" id="ant_personales_alergias_no" name="ant_personales_alergias_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_alergias_no">No</label>
				</div>
				<textarea hidden class="width450px_cel form-control margin_left_5px" id="ant_personales_alergias_si_detalle" name="ant_personales_alergias_si_detalle" rows="5" cols="60"></textarea>			
				<!--<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_personales_alergias_si_detalle" name="ant_personales_alergias_si_detalle"/>								-->
			</div>			
		</div>
	</div>

	<div class="row margin_top_5px margin_left_5px">		
	 	<div>
			<label class="margin_left_5px">Qx:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickQxSi()" type="radio" id="ant_personales_qx_si" name="ant_personales_qx_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_qx_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickQxNo()" type="radio" id="ant_personales_qx_no" name="ant_personales_qx_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_qx_no">No</label>
				</div>
				<textarea hidden class="width450px_cel form-control margin_left_5px" id="ant_personales_qx_si_detalle" name="ant_personales_qx_si_detalle" rows="5" cols="60"></textarea>
				<!--<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_personales_qx_si_detalle" name="ant_personales_qx_si_detalle"/>								-->
			</div>			
		</div>
	</div>

	<div class="row margin_top_5px margin_left_5px">		
	 	<div>
			<label class="margin_left_5px">Traumatismos:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickTraumatismosSi()" type="radio" id="ant_personales_traumatismos_si" name="ant_personales_traumatismos_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_traumatismos_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickTraumatismosNo()" type="radio" id="ant_personales_traumatismos_no" name="ant_personales_traumatismos_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_traumatismos_no">No</label>
				</div>
				<textarea hidden class="width450px_cel form-control margin_left_5px" id="ant_personales_traumatismos_si_detalle" name="ant_personales_traumatismos_si_detalle" rows="5" cols="60"></textarea>
				<!--<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_personales_traumatismos_si_detalle" name="ant_personales_traumatismos_si_detalle"/>								-->
			</div>			
		</div>
	</div>

	<div class="row margin_top_5px margin_left_5px">		
	 	<div>
			<label class="margin_left_5px">Transfusiones:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickTransfusionesSi()" type="radio" id="ant_personales_transfusiones_si" name="ant_personales_transfusiones_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_transfusiones_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickTransfusionesNo()" type="radio" id="ant_personales_transfusiones_no" name="ant_personales_transfusiones_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_transfusiones_no">No</label>
				</div>
				<textarea hidden class="width450px_cel form-control margin_left_5px" id="ant_personales_transfusiones_si_detalle" name="ant_personales_transfusiones_si_detalle" rows="5" cols="60"></textarea>
				<!--<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_personales_transfusiones_si_detalle" name="ant_personales_transfusiones_si_detalle"/>								-->
			</div>			
		</div>
	</div>

	<div class="row margin_top_5px margin_left_5px">		
	 	<div>
			<label class="margin_left_5px">Otro:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickAntPersonalesOtrosSi()" type="radio" id="ant_personales_otro_si" name="ant_personales_otro_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_personales_otro_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickAntPersonalesOtrosNo()" type="radio" id="ant_personales_otro_no" name="v" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_personales_otro_no">No</label>
				</div>
				<textarea hidden class="width450px_cel form-control margin_left_5px" id="ant_personales_otro_si_detalle" name="ant_personales_otro_si_detalle" rows="5" cols="60"></textarea>
				<!--<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_personales_transfusiones_si_detalle" name="ant_personales_transfusiones_si_detalle"/>								-->
			</div>			
		</div>
	</div>

	<div class="row contenedor3 margin_top_20px">
			<div class="contenido3">
		    	<button id="ant_personales_sig_btn" type="button" onclick="antPersonalesAnteriorSiguiente(0)" class="rodri_button_aceptar_cel"><</button>
		    	<input id="ant_personales_actual_cantidad" disabled class="letrasblancas input_width_50px_cel sinBackground margin_left_5px" value="0/0"></input>		    	
		    	<button id="ant_personales_ant_btn" type="button" onclick="antPersonalesAnteriorSiguiente(1)" class="rodri_button_aceptar_cel">></button>		    
		    	<button id="ant_personales_nuevo_btn" type="button" onclick="antPersonalesNuevo()" class="rodri_button_aceptar">Nuevo</button>		    	
		    	<button id="ant_personales_guardar_btn" type="button" onclick="guardarAntecedentesPersonales()" class="rodri_button_aceptar">Guardar</button>		    	
			</div>
		</div>	

</form>

<script type="text/javascript">
	
	function clickInternacionesSi(){
		var seccion = document.getElementById("seccion_ant_personales_si");
		seccion.hidden = false;
	}

	function clickInternacionesNo(){
		var seccion = document.getElementById("seccion_ant_personales_si");
		seccion.hidden = true;
		document.getElementById("ant_personales_internaciones_si_motivo").value = "";			
		document.getElementById("ant_personales_internaciones_si_lugar").value = "";			
		document.getElementById("ant_personales_internaciones_si_duracion").value = "";			
		document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = "";			
	}

	function clickAlergiasSi(){
		var detalles = document.getElementById("ant_personales_alergias_si_detalle");
		detalles.hidden = false;
	}

	function clickAlergiasNo(){
		var detalles = document.getElementById("ant_personales_alergias_si_detalle");
		detalles.hidden = true;
		detalles.value = "";
	}

	function clickQxSi(){
		var detalles = document.getElementById("ant_personales_qx_si_detalle");
		detalles.hidden = false;
	}

	function clickQxNo(){
		var detalles = document.getElementById("ant_personales_qx_si_detalle");
		detalles.hidden = true;
		detalles.value = "";
	}

	function clickTraumatismosSi(){
		var detalles = document.getElementById("ant_personales_traumatismos_si_detalle");
		detalles.hidden = false;		
	}

	function clickTraumatismosNo(){
		var detalles = document.getElementById("ant_personales_traumatismos_si_detalle");
		detalles.hidden = true;
		detalles.value = "";
	}

	function clickTransfusionesSi(){
		var detalles = document.getElementById("ant_personales_transfusiones_si_detalle");
		detalles.hidden = false;		
	}

	function clickTransfusionesNo(){
		var detalles = document.getElementById("ant_personales_transfusiones_si_detalle");
		detalles.hidden = true;
		detalles.value = "";
	}

	function clickAntPersonalesOtrosSi(){
		var detalles = document.getElementById("ant_personales_otro_si_detalle");
		detalles.hidden = false;		
	}

	function clickAntPersonalesOtrosNo(){
		var detalles = document.getElementById("ant_personales_otro_si_detalle");
		detalles.hidden = true;
		detalles.value = "";
	}
	

	function guardarAntecedentesPersonales(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var enfermedad_actual = document.getElementById("ant_personales_enfermedad_actual").value;
		var numero = document.getElementById("ant_personales_numero").value;
		var interanaciones_si = document.getElementById("ant_personales_internaciones_si").checked;
		var interanaciones_no = document.getElementById("ant_personales_internaciones_no").checked;
		var internaciones = 2;		
		if(interanaciones_si)
			internaciones = 1;
		if(interanaciones_no)
			internaciones = 0;
			/*
		var internaciones_motivo = document.getElementById("ant_personales_internaciones_si_motivo").value;
		var interanaciones_lugar = document.getElementById("ant_personales_internaciones_si_lugar").value;
		var interanaciones_duracion = document.getElementById("ant_personales_internaciones_si_duracion").value;
		var interanaciones_indicacion_alta = document.getElementById("ant_personales_internaciones_si_indicacion_alta").value;
		*/
		var alergias = 2;
		var alergia_detalle = "";
		var alergias_si = document.getElementById("ant_personales_alergias_si").checked;
		var alergias_no = document.getElementById("ant_personales_alergias_no").checked;
		if(alergias_si){
			alergias = 1;
			var alergia_detalle = document.getElementById("ant_personales_alergias_si_detalle").value;
		}
		if(alergias_no)
			alergias = 0;

		var qx = 2;
		var qx_detalle = "";
		var qx_si = document.getElementById("ant_personales_qx_si").checked;
		var qx_no = document.getElementById("ant_personales_qx_no").checked;
		if(qx_si){
			qx = 1;
			qx_detalle = document.getElementById("ant_personales_qx_si_detalle").value;
		} 
		if(qx_no)
			qx = 0;

		var traumatismo = 2;
		var traumatismo_detalle = "";
		var traumatismos_si = document.getElementById("ant_personales_traumatismos_si").checked;
		var traumatismos_no = document.getElementById("ant_personales_traumatismos_no").checked;
		if(traumatismos_si){
			traumatismo = 1;
			traumatismo_detalle = document.getElementById("ant_personales_traumatismos_si_detalle").value;
		}
		if(traumatismos_no)
			traumatismo = 0;

		var transfusiones = 2;
		var transfusiones_detalle = "";
		var transfusiones_si = document.getElementById("ant_personales_transfusiones_si").checked;
		var transfusiones_no = document.getElementById("ant_personales_transfusiones_no").checked;
		if(transfusiones_si){
			transfusiones = 1;
			transfusiones_detalle = document.getElementById("ant_personales_transfusiones_si_detalle").value;
		}
		if(transfusiones_no)
			transfusiones = 0;	

		var otro = 2;
		var otro_detalle = "";
		var otro_si = document.getElementById("ant_personales_otro_si").checked;
		var otro_no = document.getElementById("ant_personales_otro_no").checked;
		if(otro_si){
			otro = 1;
			otro_detalle = document.getElementById("ant_personales_otro_si_detalle").value;
		}
		if(otro_no)
			otro = 0;	

		var activo = 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_antecedentes_personales',
           data:{consulta:consulta, paciente:paciente,enfermedad_actual:enfermedad_actual, internaciones:internaciones, alergias:alergias, alergia_detalle:alergia_detalle, qx:qx, qx_detalle:qx_detalle, traumatismo:traumatismo, traumatismo_detalle:traumatismo_detalle, transfusiones:transfusiones, transfusiones_detalle:transfusiones_detalle, otro:otro, otro_detalle:otro_detalle,numero:numero, activo:activo ,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response == 1){           			
           			//alert("Guardado con exito actividades extra escolares");
           			habilitarBotonesAntPersonales(2);
           			mostrarSnackbar("GUARDADO");           			
            	} else {            		
            		mostrarSnackbar("ANTECEDENTES PERSONALES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });	
	}

	function cargarAntecedentesPersonalesConsulta(){
		cargarAntecedentesPersonales();
		document.getElementById("ant_personales_nuevo_btn").hidden = true;		
		document.getElementById("ant_personales_guardar_btn").hidden = true;		
		document.getElementById("nuevoInternacionesButtonId").hidden = true;
		document.getElementById("guardarInternacionesButtonId").hidden = true;
		document.getElementById("agregarBotonNuevaFotoInternacionesId").hidden = true;
		document.getElementById("botonGuardarFotoInternacionId").hidden = true;
		document.getElementById("internaciones_agregar_foto_text").hidden = true;
		document.getElementById("internaciones_foto_1").hidden = true;
	}

	// valor == 2 click en guardar
	// valor == 1 click en nuevo
	// valor == 0 recien fue cargado
	function habilitarBotonesAntPersonales(valor) {
		if(valor == 0){
			document.getElementById("ant_personales_sig_btn").disabled = false;
			document.getElementById("ant_personales_ant_btn").disabled = false;			
			document.getElementById("ant_personales_guardar_btn").disabled = true;
			document.getElementById("ant_personales_nuevo_btn").disabled = false;			
		}
		if(valor == 1){
			document.getElementById("ant_personales_sig_btn").disabled = true;
			document.getElementById("ant_personales_ant_btn").disabled = true;			
			document.getElementById("ant_personales_guardar_btn").disabled = false;
			document.getElementById("ant_personales_nuevo_btn").disabled = true;			
		}
		if(valor == 2){
			document.getElementById("ant_personales_sig_btn").disabled = false;
			document.getElementById("ant_personales_ant_btn").disabled = false;			
			document.getElementById("ant_personales_guardar_btn").disabled = true;
			document.getElementById("ant_personales_nuevo_btn").disabled = true;			
		}
		
	}

	function cargarAntecedentesPersonales(){		
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;	
		var tipo_consulta = document.getElementById("es_nueva_consulta").value;					
		document.getElementById("ant_personales_numero").value = 1;
		antecedentesPersonalesReadOnly(true);
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_personales',
           data:{consulta:consulta, paciente:paciente, tipo_consulta:tipo_consulta,_token: '{{csrf_token()}}'},
           	success:function(data){             		
           		if(data.response_data != null && data.response == 1) {         
					document.getElementById("antecedentes_personales_id").value = data.response_data.id;
					document.getElementById("ant_personales_numero").value = data.response_data.numero;
					document.getElementById("ant_personales_actual_cantidad").value = data.response_data.numero+"/"+data.response_data.numero;           			
         			document.getElementById("ant_personales_enfermedad_actual").value = data.response_data.enfermedad_actual;		
					if(data.response_data.internaciones == 1) {
						$('#internaciones_cantidad_fotos').val(1);						
						document.getElementById("ant_personales_internaciones_si").checked = true;
						document.getElementById("seccion_ant_personales_si").hidden = false;						
						if(data.internacion != null){						
							document.getElementById("ant_personales_internaciones_si_motivo").value = data.internacion.motivo;
							document.getElementById("ant_personales_internaciones_si_lugar").value = data.internacion.lugar;
							document.getElementById("ant_personales_internaciones_si_duracion").value = data.internacion.duracion;
							document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = data.internacion.indicacion_alta;
							document.getElementById("internaciones_actual_cantidad").value = data.internacion.numero+"/"+data.internacion.numero;
							document.getElementById("internaciones_id").value = data.internacion.id;							
							document.getElementById("internaciones_numero").value = data.internacion.numero;						
							cargarFotoInternaciones(data.internacion.id);
						}
					} else {
						if(data.response_data.internaciones == 0) 
							document.getElementById("ant_personales_internaciones_no").checked = true;  			  			
					}

					if(data.response_data.alergias == 1) {
						document.getElementById("ant_personales_alergias_si").checked = true;						
						document.getElementById("ant_personales_alergias_si_detalle").hidden = false;
						document.getElementById("ant_personales_alergias_si_detalle").value = data.response_data.alergia_detalle;
					} else {
						if(data.response_data.alergias == 0) 
							document.getElementById("ant_personales_alergias_no").checked = true;  			  							
					}

					if(data.response_data.qx == 1) {
						document.getElementById("ant_personales_qx_si").checked = true;						
						document.getElementById("ant_personales_qx_si_detalle").hidden = false;
						document.getElementById("ant_personales_qx_si_detalle").value = data.response_data.qx_detalle;
					} else {
						if(data.response_data.qx == 0) 
							document.getElementById("ant_personales_qx_no").checked = true;  			  							
					}

					if(data.response_data.traumatismos == 1) {
						document.getElementById("ant_personales_traumatismos_si").checked = true;						
						document.getElementById("ant_personales_traumatismos_si_detalle").hidden = false;
						document.getElementById("ant_personales_traumatismos_si_detalle").value = data.response_data.traumatismos_detalle;
					} else {
						if(data.response_data.traumatismos == 0) 
							document.getElementById("ant_personales_traumatismos_no").checked = true;  			  						
					}

					if(data.response_data.transfusiones == 1) {
						document.getElementById("ant_personales_transfusiones_si").checked = true;						
						document.getElementById("ant_personales_transfusiones_si_detalle").hidden = false;
						document.getElementById("ant_personales_transfusiones_si_detalle").value = data.response_data.transfusiones_detalle;
					} else {
						if(data.response_data.transfusiones == 0) 
							document.getElementById("ant_personales_transfusiones_no").checked = true;  			  						
					}

					if(data.response_data.otro == 1) {
						document.getElementById("ant_personales_otro_si").checked = true;						
						document.getElementById("ant_personales_otro_si_detalle").hidden = false;
						document.getElementById("ant_personales_otro_si_detalle").value = data.response_data.otro_detalle;
					} else {
						if(data.response_data.otro == 0) 
							document.getElementById("ant_personales_otro_no").checked = true;  			  						
					}
					
            	} else {
            		//mostrarSanckbar("ANTECEDENTES PERSONALES FALLO");                        		
            		document.getElementById("ant_personales_actual_cantidad").value = "0/0";  		
            	}
            	habilitarBotonesAntPersonales(0);
            }
        });	
	}

	function habilitarFotosInternacion(valor) {
		document.getElementById("internaciones_foto_1").disabled = valor;
		document.getElementById("agregarBotonNuevaFotoInternacionesId").disabled = valor;
		document.getElementById("botonGuardarFotoInternacionId").disabled = valor;		
	}

	function nuevoInternaciones() {
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;	
		var internacion_id = -1;//document.getElementById("internaciones_id").value;	
		var antecedentes_personales_id = document.getElementById("antecedentes_personales_id").value;			
		var internaciones_numero = document.getElementById("internaciones_numero").value;
		antecedentesPersonalesReadOnly(false);	
		habilitarFotosInternacion(false);
		//alert(internaciones_numero);
		$.ajax({
	           type:'POST',
	           dataType:'JSON',
	           url:'/nuevo_internaciones',
	           data:{consulta:consulta, paciente:paciente, internacion_id:internacion_id, antecedentes_personales_id:antecedentes_personales_id, _token: '{{csrf_token()}}'},
	           	success:function(data) {               	
	           		if(data.response == 1) {           			           		           		
	           			if(data.internaciones != null) {            				           					           				
	           				document.getElementById("internaciones_id").value = "-1";
	           				document.getElementById("internaciones_numero").value = data.numero;
	           				document.getElementById("ant_personales_internaciones_si_motivo").value = "";
	           				document.getElementById("ant_personales_internaciones_si_lugar").value = "";           
	           				document.getElementById("ant_personales_internaciones_si_duracion").value = "";           
	           				document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = "";           
	           				$("#internaciones_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		                	document.getElementById("internaciones_actual_cantidad_fotos").value = "0/0";
	           			}
	            	}
	            }
	        });
	}

	function guardarInternaciones(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var antecedentes_personales_id = document.getElementById("antecedentes_personales_id").value;			
		var internaciones_id = document.getElementById("internaciones_id").value;			
		var internaciones_numero = document.getElementById("internaciones_numero").value;			
		var motivo = document.getElementById("ant_personales_internaciones_si_motivo").value;			
		var lugar = document.getElementById("ant_personales_internaciones_si_lugar").value;			
		var duracion = document.getElementById("ant_personales_internaciones_si_duracion").value;			
		var indicacion_alta = document.getElementById("ant_personales_internaciones_si_indicacion_alta").value;			
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_internaciones',
           data:{consulta:consulta, paciente:paciente, internaciones_id:internaciones_id,internaciones_numero:internaciones_numero, motivo:motivo, lugar:lugar, duracion:duracion, indicacion_alta:indicacion_alta, antecedentes_personales_id:antecedentes_personales_id, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1){
           			var valor = data.internaciones.numero+"/"+data.cantidad;
           			document.getElementById("internaciones_actual_cantidad").value = valor;
         			document.getElementById("internaciones_id").value = data.internaciones.id;  			         			
           			mostrarSnackbar("INTERNACION GUARDADA");
            	} else {
            		mostrarSnackbar("INTERNACION FALLO");
            	}
            }
        });	
  	}

	function agregarBotonNuevaFotoInt(){
	    var cantidadFotos = document.getElementById("internaciones_cantidad_fotos").value;  //1      
	    var viejoValor = parseInt(cantidadFotos); // 1
	    var nuevoValor = parseInt(cantidadFotos) + 1;      	    
	  //  var btn = document.getElementById("foto-"+viejoValor);
	    var ultimoFile = document.getElementById("internaciones_foto_"+viejoValor);	    
	    var f = ultimoFile.value;
	    if(f.localeCompare('') != 0){
	      $('#internaciones_cantidad_fotos').val(nuevoValor);
	      var seccion = document.getElementById("seccion_internaciones_fotos");                  
	      var input = document.createElement("INPUT");
	      input.type = 'file';
	      input.id = 'internaciones_foto_'+nuevoValor;
	      input.name = 'internaciones_foto_'+nuevoValor;
	      seccion.appendChild(input);
	    }
  	}

  	function initAntecedentesPersonales(){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		var activo = 2;
		antecedentesPersonalesReadOnly(true);
		$.ajax({
	           type:'POST',
	           dataType:'JSON',
	           url:'/guardar_antecedentes_personales',
	           data:{consulta:consulta, paciente:paciente, activo:activo, _token: '{{csrf_token()}}'},
	           	success:function(data) {               	
	           		if(data.response == 1) {           			           			
	           			if(data.antecedentesPersonales != null) {        	           				
							$('#internaciones_cantidad_fotos').val(1);
				  			$('#antecedentes_personales_id').val(data.antecedentesPersonales.id); 
				  			//$('#foto_internaciones_id').val(data.antecedentesPersonales.id); 				  			
	           			}
	            	}
	            }
	        });	
  	}

  	function internacionesAnteriorSiguiente(opcion){
		var internaciones_id = document.getElementById("internaciones_id").value;
		var paciente = document.getElementById("paciente_id").value;	
		var consulta = document.getElementById("consulta_id").value;
		var antecedentes_personales_id = document.getElementById("antecedentes_personales_id").value;			 
		var numero_actual = document.getElementById("internaciones_numero").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_internaciones_ant_sig',
           data:{paciente:paciente ,numero:numero, internaciones_id:internaciones_id, consulta:consulta, antecedentes_personales_id:antecedentes_personales_id ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			
           			document.getElementById("internaciones_numero").value = data.internaciones.numero;
           			if(data.internaciones.numero != 0){
           				document.getElementById("internaciones_id").value = data.internaciones.id;
           				document.getElementById("internaciones_numero").value = data.internaciones.numero;
	        			document.getElementById("ant_personales_internaciones_si_motivo").value = data.internaciones.motivo;			
						document.getElementById("ant_personales_internaciones_si_lugar").value = data.internaciones.lugar;		
						document.getElementById("ant_personales_internaciones_si_duracion").value = data.internaciones.duracion;
						document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = data.internaciones.indicacion_alta;	        		
						var valor = data.internaciones.numero+"/"+data.numero;
	           			document.getElementById("internaciones_actual_cantidad").value = valor;		   				     
           		
           				cargarFotoInternaciones(data.internaciones.id);
           			}
           		}
            }
        });
	}

	function cargarFotoInternaciones(internaciones_id){
  		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/get_fotos_internaciones',
           data:{consulta:consulta, paciente:paciente, internaciones_id:internaciones_id, _token: '{{csrf_token()}}'},
           	success:function(data) {              	           		
           		if(data.response == 1) {           		           		      			           			
           			// medico1/examenes_complementarios/7JddMlunI1SPXoVSZcv3sloguzyRpkgAWsbFk66c.jpeg           			
           			if(data.fotosInternaciones!=null && data.fotosInternaciones.foto.localeCompare('') != 0) {	
           			 	       		                
		                var imagen = document.getElementById("internaciones_foto");
		                $("#internaciones_foto").attr("src", "img/"+data.fotosInternaciones.foto);
						
		                imagen.onclick = function() {                                                  
		                  onClickVerMI(data.fotosInternaciones.foto, 4);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                } 	
		                document.getElementById("internaciones_numero_foto").value = data.fotosInternaciones.numero;
		                var valorFoto = data.fotosInternaciones.numero+"/"+data.fotosInternaciones.numero;
	           			document.getElementById("internaciones_actual_cantidad_fotos").value = valorFoto;		                       		                
	           			document.getElementById("cantidad_fotos_modal_int").value = valorFoto;		                       		                
		              } else {		              			                		                
		                $("#internaciones_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		                document.getElementById("internaciones_actual_cantidad_fotos").value = "0/0";
		                document.getElementById("cantidad_fotos_modal_int").value = "0/0";	
		              }
            	}            	
            }
        });		
  	}

	function internacionesAnteriorSiguienteFoto(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var internaciones_id = document.getElementById("internaciones_id").value;			
		var numero_actual = document.getElementById("internaciones_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_internaciones_ant_sig_foto',
           data:{paciente:paciente, internaciones_id:internaciones_id ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.internacionesFoto.numero != 0){           				
           				document.getElementById("internaciones_numero_foto").value = data.internacionesFoto.numero;
	        			        				        								
						var valor = data.internacionesFoto.numero+"/"+data.tope;
	           			document.getElementById("internaciones_actual_cantidad_fotos").value = valor;		   			
           			}
           			if(data.internacionesFoto.foto.localeCompare('') != 0) { 
           			               		                
		                var imagen = document.getElementById("internaciones_foto");
		                 $("#internaciones_foto").attr("src", "img/"+data.internacionesFoto.foto);					
						imagen.onclick = function() {                                                  
		                  onClickVerMI(data.internacionesFoto.foto, 4);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }

		              } else {		              	
		                $("#internaciones_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		              }
           		}
            }
        });
	}

	function internacionesAnteriorSiguienteFotoModal(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var internaciones_id = document.getElementById("internaciones_id").value;			
		var numero_actual = document.getElementById("internaciones_numero_foto").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_internaciones_ant_sig_foto',
           data:{paciente:paciente, internaciones_id:internaciones_id ,numero:numero ,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           			           			
           			if(data.internacionesFoto.numero != 0){           				
           				document.getElementById("internaciones_numero_foto").value = data.internacionesFoto.numero;
	        			        				        								
						var valor = data.internacionesFoto.numero+"/"+data.tope;
	           			document.getElementById("internaciones_actual_cantidad_fotos").value = valor;	
	           			document.getElementById("cantidad_fotos_modal_int").value = valor;			   				   			
           			}
           			if(data.internacionesFoto.foto.localeCompare('') != 0) { 
           			               		                
		                var imagen = document.getElementById("internaciones_foto");		                 
						imagen.onclick = function() {                                                  
		                  onClickVerMI(data.internacionesFoto.foto, 4);
		                  document.getElementById("panelAvanzarMI").hidden = false;
		                  document.getElementById("panelCantidadMI").hidden = false;
		                }
		                $("#img_src").attr("src", "img/"+data.internacionesFoto.foto); 	
		                $("#internaciones_foto").attr("src", "img/"+data.internacionesFoto.foto);  
		              } else {		              	
		              	$("#img_src").attr("src", "img/iconos/sin_imagen.jpg"); 	              	
		                $("#internaciones_foto").attr("src", "img/iconos/sin_imagen.jpg");                
		              }
           		}
            }
        });
	}

	function antPersonalesAnteriorSiguiente(opcion){
		var paciente = document.getElementById("paciente_id").value;
		var consulta = document.getElementById("consulta_id").value;		
		var numero_actual = document.getElementById("ant_personales_numero").value;
		if(opcion == 1) // avanzo
			var numero = parseInt(numero_actual) + 1;
		else
			var numero = parseInt(numero_actual) - 1;
			borrarCamposAntecedentesPersonales();			
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_ant_personales_ant_sig',
           data:{paciente:paciente, numero:numero, consulta:consulta ,_token: '{{csrf_token()}}'},
           	success:function(data){                  		
           		if(data.response_data != null && data.response == 1) {         
					document.getElementById("antecedentes_personales_id").value = data.response_data.id;
					document.getElementById("ant_personales_numero").value = data.response_data.numero;
					document.getElementById("ant_personales_actual_cantidad").value = data.response_data.numero+"/"+data.tope;           			
         			document.getElementById("ant_personales_enfermedad_actual").value = data.response_data.enfermedad_actual;		
					if(data.response_data.internaciones == 1) {
						$('#internaciones_cantidad_fotos').val(1);						
						document.getElementById("ant_personales_internaciones_si").checked = true;
						document.getElementById("seccion_ant_personales_si").hidden = false;
						if(data.internacion != null){													
							document.getElementById("ant_personales_internaciones_si_motivo").value = data.internacion.motivo;
							document.getElementById("ant_personales_internaciones_si_lugar").value = data.internacion.lugar;
							document.getElementById("ant_personales_internaciones_si_duracion").value = data.internacion.duracion;
							document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = data.internacion.indicacion_alta;
							document.getElementById("internaciones_actual_cantidad").value = data.internacion.numero+"/"+data.internacion.numero;
							document.getElementById("internaciones_id").value = data.internacion.id;							
							document.getElementById("internaciones_numero").value = data.internacion.numero;						
							cargarFotoInternaciones(data.internacion.id);
						} else {							
							borrarCamposInternacion();
						}
					} else {
							document.getElementById("seccion_ant_personales_si").hidden = true;
							borrarCamposInternacion();
						if(data.response_data.internaciones == 0){ 
							document.getElementById("ant_personales_internaciones_no").checked = true;							
						} else {
							document.getElementById("ant_personales_internaciones_si").checked = false;							
							document.getElementById("ant_personales_internaciones_no").checked = false;							
						}
					}

					if(data.response_data.alergias == 1) {
						document.getElementById("ant_personales_alergias_si").checked = true;						
						document.getElementById("ant_personales_alergias_si_detalle").hidden = false;
						document.getElementById("ant_personales_alergias_si_detalle").value = data.response_data.alergia_detalle;
					} else {
						if(data.response_data.alergias == 0) 
							document.getElementById("ant_personales_alergias_no").checked = true;  			  							
					}

					if(data.response_data.qx == 1) {
						document.getElementById("ant_personales_qx_si").checked = true;						
						document.getElementById("ant_personales_qx_si_detalle").hidden = false;
						document.getElementById("ant_personales_qx_si_detalle").value = data.response_data.qx_detalle;
					} else {
						if(data.response_data.qx == 0) 
							document.getElementById("ant_personales_qx_no").checked = true;  			  							
					}

					if(data.response_data.traumatismos == 1) {
						document.getElementById("ant_personales_traumatismos_si").checked = true;						
						document.getElementById("ant_personales_traumatismos_si_detalle").hidden = false;
						document.getElementById("ant_personales_traumatismos_si_detalle").value = data.response_data.traumatismos_detalle;
					} else {
						if(data.response_data.traumatismos == 0) 
							document.getElementById("ant_personales_traumatismos_no").checked = true;  			  						
					}

					if(data.response_data.transfusiones == 1) {
						document.getElementById("ant_personales_transfusiones_si").checked = true;						
						document.getElementById("ant_personales_transfusiones_si_detalle").hidden = false;
						document.getElementById("ant_personales_transfusiones_si_detalle").value = data.response_data.transfusiones_detalle;
					} else {
						if(data.response_data.transfusiones == 0) 
							document.getElementById("ant_personales_transfusiones_no").checked = true;  			  						
					}

					if(data.response_data.otro == 1) {
						document.getElementById("ant_personales_otro_si").checked = true;						
						document.getElementById("ant_personales_otro_si_detalle").hidden = false;
						document.getElementById("ant_personales_otro_si_detalle").value = data.response_data.otro_detalle;
					} else {
						if(data.response_data.otro == 0) 
							document.getElementById("ant_personales_otro_no").checked = true;  			  						
					}
            }
        }
        });
	}

	function antPersonalesNuevo(){
		var paciente = document.getElementById("paciente_id").value;		
		borrarCamposAntecedentesPersonales();
		initAntecedentesPersonales();
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/ant_personales_nuevo',
           data:{paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){           		    
           			document.getElementById("ant_personales_numero").value = data.nuevo;
           			document.getElementById("ant_personales_actual_cantidad").value = data.nuevo+"/"+data.nuevo;
           			habilitarBotonesAntPersonales(1);
           			antecedentesPersonalesReadOnly(false);
           		}
            }
        });
	}

	function inicializarAntecedentesPersonalesCargarFoto(){		
		borrarCamposAntecedentesPersonales();		
		document.getElementById("ant_personales_sig_btn").hidden = true;
		document.getElementById("ant_personales_actual_cantidad").hidden = true;
		document.getElementById("ant_personales_ant_btn").hidden = true;
		document.getElementById("ant_personales_nuevo_btn").hidden = true;
		document.getElementById("ant_personales_guardar_btn").hidden = true;
		document.getElementById("seccion_ant_personales_si").hidden = true;
		document.getElementById("guardarInternacionesButtonId").hidden = true;
		document.getElementById("nuevoInternacionesButtonId").hidden = true;
		antecedentesPersonalesReadOnly(true);
		cargarAntecedentesPersonales();
	}

	function borrarCamposAntecedentesPersonales(){
		document.getElementById("ant_personales_enfermedad_actual").value = '';		
		document.getElementById("ant_personales_internaciones_no").checked = false;
		document.getElementById("ant_personales_internaciones_si").checked = false;
		document.getElementById("ant_personales_alergias_si").checked = false;
		document.getElementById("ant_personales_alergias_no").checked = false;
		document.getElementById("ant_personales_alergias_si_detalle").value = '';
		document.getElementById("ant_personales_alergias_si_detalle").hidden = true;
		document.getElementById("ant_personales_qx_no").checked = false;
		document.getElementById("ant_personales_qx_si").checked = false;
		document.getElementById("ant_personales_qx_si_detalle").value = '';
		document.getElementById("ant_personales_qx_si_detalle").hidden = true;
		document.getElementById("ant_personales_traumatismos_no").checked = false;
		document.getElementById("ant_personales_traumatismos_si").checked = false;
		document.getElementById("ant_personales_traumatismos_si_detalle").value = '';
		document.getElementById("ant_personales_traumatismos_si_detalle").hidden = true;
		document.getElementById("ant_personales_transfusiones_no").checked = false;
		document.getElementById("ant_personales_transfusiones_si").checked = false;
		document.getElementById("ant_personales_transfusiones_si_detalle").value = '';
		document.getElementById("ant_personales_transfusiones_si_detalle").hidden = true;
		document.getElementById("ant_personales_otro_no").checked = false;
		document.getElementById("ant_personales_otro_si").checked = false;
		document.getElementById("ant_personales_otro_si_detalle").value = '';
		document.getElementById("ant_personales_otro_si_detalle").hidden = true;
		
		borrarCamposInternacion();
	}

	function borrarCamposInternacion(){
		document.getElementById("ant_personales_internaciones_si_motivo").value = '';
		document.getElementById("ant_personales_internaciones_si_lugar").value = '';
		document.getElementById("ant_personales_internaciones_si_duracion").value = '';
		document.getElementById("ant_personales_internaciones_si_indicacion_alta").value = '';
		document.getElementById("internaciones_actual_cantidad").value = "0/0";
		document.getElementById("internaciones_id").value = "-1";							
		document.getElementById("internaciones_numero").value = "1";						
		cargarFotoInternaciones(null);
	}

	function antecedentesPersonalesReadOnly(valor){
		document.getElementById("ant_personales_enfermedad_actual").readOnly = valor;		
		document.getElementById("ant_personales_internaciones_no").disabled = valor;
		document.getElementById("ant_personales_internaciones_si").disabled = valor;
		document.getElementById("ant_personales_alergias_si").disabled = valor;
		document.getElementById("ant_personales_alergias_no").disabled = valor;
		document.getElementById("ant_personales_alergias_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_alergias_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_qx_no").disabled = valor;
		document.getElementById("ant_personales_qx_si").disabled = valor;
		document.getElementById("ant_personales_qx_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_qx_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_traumatismos_no").disabled = valor;
		document.getElementById("ant_personales_traumatismos_si").disabled = valor;
		document.getElementById("ant_personales_traumatismos_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_traumatismos_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_transfusiones_no").disabled = valor;
		document.getElementById("ant_personales_transfusiones_si").disabled = valor;
		document.getElementById("ant_personales_transfusiones_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_transfusiones_si_detalle").readOnly = valor;

		document.getElementById("ant_personales_otro_no").disabled = valor;
		document.getElementById("ant_personales_otro_si").disabled = valor;
		document.getElementById("ant_personales_otro_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_otro_si_detalle").readOnly = valor;
		document.getElementById("ant_personales_otro_si_detalle").readOnly = valor;

		document.getElementById("ant_personales_internaciones_si_motivo").readOnly = valor;
		document.getElementById("ant_personales_internaciones_si_lugar").readOnly = valor;
		document.getElementById("ant_personales_internaciones_si_duracion").readOnly = valor;
		document.getElementById("ant_personales_internaciones_si_indicacion_alta").readOnly = valor;
	}

</script>
