
<script type="text/javascript" src="https://raw.githubusercontent.com/wilq32/jqueryrotate/master/jQueryRotate.js"></script>


<div class="modal" tabindex="-2" role="dialog" id="myModalImagenes" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div id="modal_body_id" class="modal-body"> 
				<div class="modal-header">       
					<h4 class="modal-title" id="modalTitleMensaje2">Imagen</h4>
				</div>
				<br>
				<div id="container" class="table-responsive">				
					<img id="img_src" src="" class="card-img-top">
				</div>
				<input hidden type="number" id="width_original" value="0">
				<input hidden type="number" id="height_original" value="0">
				<input hidden type="number" id="manipular_image_id">
			</div>
			<div class="flotante_modal_bottom_img">
			<div id="panelCantidadMI" class="margin_top_3px_cel">
					<input hidden id="cantidad_fotos_modal_anp" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
					<input hidden id="cantidad_fotos_modal_ec" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
					<input hidden id="cantidad_fotos_modal_ncf" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
					<input hidden id="cantidad_fotos_modal_int" disabled class="input_width_50px sinBackground letrasblancas margin_left_20px" value="0/0"></input>
				</div>				
				<div id="panelAvanzarMI" class="margin_top_3px_cel">					
					<button type="button" onclick="previousImage()" 
					class="rodri_button_aceptar_si"><</button>  
					<button type="button" onclick="siguienteImage()" 
					class="rodri_button_aceptar_si">></button>
				</div>
				<div id="panelZoomMI" class="margin_top_3px_cel cel_hidden">
					<button type="button" onclick="zoomin()" 
					class="rodri_button_aceptar_si">+</button>
					<button type="button" onclick="zoomout()"
					class="rodri_button_cancelar_no">-</button>   					
				</div>
				<div id="panelExtraMI" class="margin_top_3px_cel">
					<button type="button" onclick="original()" 
					class="rodri_button_aceptar_volver">O</button>
					<button type="button" onclick="girar()" 
					class="rodri_button_aceptar_volver">G</button>
				</div>
			</div>
			<div class="flotante_modal_top_img">				
				<button type="button" 			
				class="rodri_button_aceptar_volver" 
				data-dismiss="modal">X</button>             
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	// medico1/antecedentes_neonatales/ajgaTrme4OzTuvXIBWHdx2MRoJDJu8eSAYj4RDE1.jpeg
	function onClickVerMI(imagen, id){		
		var modalImg = document.getElementById("img_src");		
		document.getElementById("manipular_image_id").value = id;		
		modalImg.src = "img/"+imagen;
		original();
		if(id == 1)
			document.getElementById("cantidad_fotos_modal_anp").hidden = false;
		else
			document.getElementById("cantidad_fotos_modal_anp").hidden = true;

		if(id == 2)
			document.getElementById("cantidad_fotos_modal_ec").hidden = false;
		else
			document.getElementById("cantidad_fotos_modal_ec").hidden = true;

		if(id == 3)
			document.getElementById("cantidad_fotos_modal_ncf").hidden = false;
		else
			document.getElementById("cantidad_fotos_modal_ncf").hidden = true;

		if(id == 4)
			document.getElementById("cantidad_fotos_modal_int").hidden = false;
		else
			document.getElementById("cantidad_fotos_modal_int").hidden = true;

		$('#myModalImagenes').modal('show');
	}

	function original(){
		var GFG = document.getElementById("img_src");
		var originalHeight = document.getElementById("height_original").value;
		var originalWidth = document.getElementById("width_original").value;		
		GFG.style.height = (1033) + "px";
        GFG.style.width = (1066) + "px"; 
	}

	function zoomin() { 
            var GFG = document.getElementById("img_src"); 
            var currHeight = GFG.clientHeight;
            var currWidth = GFG.clientWidth; 
                GFG.style.height = (currHeight + 80) + "px";
                GFG.style.width = (currWidth + 80) + "px";             
    } 

    function zoomout() { 
        var GFG = document.getElementById("img_src"); 
        var currHeight = GFG.clientHeight;
        var currWidth = GFG.clientWidth;
            GFG.style.height = (currHeight - 80) + "px";
            GFG.style.width = (currWidth - 80) + "px";        
    }

    function previousImage() {    
    	//original();
    	giro = 0;	
    	$("#container").attr("class", "table-responsive derecha360");
    	var manipular_image_id = document.getElementById("manipular_image_id").value;    	
    	if(manipular_image_id == 1){
    		antecedentesNeonatalesAnteriorSiguienteFotoModal(2);
    	}
    	if(manipular_image_id == 2){
    		examenesComplementariosAnteriorSiguienteFotoModal(2);
    	}
    	if(manipular_image_id == 3){
    		nuevaConsultaFotoAnteriorSiguienteFotoModal(2);
    	}
    	if(manipular_image_id == 4){
    		internacionesAnteriorSiguienteFotoModal(2);
    	}
    }

    function siguienteImage() {
    	//original();
    	giro = 0;
    	$("#container").attr("class", "table-responsive derecha360");
    	var manipular_image_id = document.getElementById("manipular_image_id").value;
    	if(manipular_image_id == 1){
    		antecedentesNeonatalesAnteriorSiguienteFotoModal(1);
    	}
    	if(manipular_image_id == 2){
    		examenesComplementariosAnteriorSiguienteFotoModal(1);
    	}
    	if(manipular_image_id == 3){
    		nuevaConsultaFotoAnteriorSiguienteFotoModal(1);
    	}
    	if(manipular_image_id == 4){
    		internacionesAnteriorSiguienteFotoModal(1);
    	}
    }
    var giro = 0;
    function girar(){
    	giro++;
	    if(giro == 1){    	    	  					
			$("#container").attr("class", "table-responsive derecha90");
		}
		if(giro == 2){    	    	  					
			$("#container").attr("class", "table-responsive derecha180");
		}
		if(giro == 3){    	    	  					
			$("#container").attr("class", "table-responsive derecha270");
		}
		if(giro == 4){    	    	  					
			$("#container").attr("class", "table-responsive derecha360");
		}
		if(giro == 4){
			giro = 0;
		}		
    }

</script>
