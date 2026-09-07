<form class="card background_panel">
	<div class="row">
		<div>
			<label class="margin_left_5px" for="ant_perinatales_embarazo">Embarazo:</label>
            <select class="form-control input_width_250px margin_left_5px" id="ant_perinatales_embarazo" name="ant_perinatales_embarazo">            
	            <option>CONTROLADO</option>    
	            <option>POCO CONTROLADO</option>    
	            <option>NO CONTROLADO</option>    
	        </select>
    	</div>

    	<div>
			<label class="margin_left_20px_cel margin_left_10px_cel" for="ant_perinatales_embarazo_numero_controles">N°Controles:</label>
	        <input type="number" class="form-control input_width_80px margin_left_20px_cel" id="ant_perinatales_embarazo_numero_controles" name="ant_perinatales_embarazo_numero_controles"  placeholder="0"/>
		</div>
	</div>

	<div class="row margin_top_5px">		
	 	<div>
			<label class="margin_left_5px">Patologías:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickPatologiaSi()" type="radio" id="ant_perinatles_patologia_si" name="patologia_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_patologia_si">Si</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickPatologiaNo()" type="radio" id="ant_perinatles_patologia_no" name="patologia_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_patologia_no">No</label>
				</div>
				<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_perinatles_patologia_si_detalle" name="ant_perinatles_patologia_si_detalle"/>								
			</div>			
		</div>
	</div>

	<div class="row margin_top_5px">		
	 	<div>
			<label class="margin_left_5px">Hisop SBHB:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickHisopSBHAsi()" type="radio" id="ant_perinatles_hisopsbha_si" name="hisopsbha_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_hisopsbha_si">+</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickHisopSBHAno()" type="radio" id="ant_perinatles_hisopsbha_no" name="hisopsbha_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_hisopsbha_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="ant_perinatles_hisopsbha_si_detalle" name="ant_perinatles_hisopsbha_si_detalle"></textarea>								
			</div>			
		</div>

		<div>
			<label class="margin_left_60px">Serología:</label>   
			<br><label class="margin_left_60px">1° Trim</label>
			<div class="row margin_left_60px">
			  	<div class="custom-control custom-radio margin_left_5px">
			  		<input onclick="clickSerologiaSi(1)" type="radio" id="ant_perinatles_serologia1_si" name="serologia1_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_serologia1_si">+</label>
				</div>
				<div class="custom-control custom-radio">
					 <input onclick="clickSerologiaNo(1)" type="radio" id="ant_perinatles_serologia1_no" name="serologia1_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_serologia1_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="ant_perinatles_serologia1_si_detalle" name="ant_perinatles_serologia1_si_detalle"></textarea>								
			</div>	
			<br><label class="margin_left_60px">3° Trim</label>
			<div class="row margin_left_60px">							  	
			  	<div class="custom-control custom-radio margin_left_5px ">			  		
			  		<input onclick="clickSerologiaSi(3)" type="radio" id="ant_perinatles_serologia3_si" name="serologia3_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_serologia3_si">+</label>
				</div>				 
				<div class="custom-control custom-radio ">					 
					 <input onclick="clickSerologiaNo(3)" type="radio" id="ant_perinatles_serologia3_no" name="serologia3_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_serologia3_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="ant_perinatles_serologia3_si_detalle" name="ant_perinatles_serologia3_si_detalle"></textarea>								
			</div>				
		</div>
	</div>

	<div class="row">
		<div>
			<label class="margin_left_5px margin_top_5px" for="ant_perinatales_parto">Parto:</label>
            <select class="form-control input_width_250px margin_left_5px" id="ant_perinatales_parto" name="ant_perinatales_parto">            
	            <option>CESÁREA</option>    
	            <option>EUTÓCICO</option>    
	            <option>DISTÓCICO</option>    
	        </select>
    	</div>

    	<div>			
    		<label class="margin_top_5px_cel"></label>
	        <input type="text" class="form-control input_width_350px margin_left_20px_cel margin_top_12px" id="ant_perinatales_parto_detalle" name="ant_perinatales_parto_detalle"/>
		</div>
	</div>

	<div class="row margin_top_12px">		
		
			<label class="margin_left_5px margin_top_5px input_width_80px_cel" for="eg">EG:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="ant_perinaltes_eg" name="ant_perinaltes_eg"  placeholder="0"/>		
			<label class="margin_left_5px margin_top_5px input_width_appx_cel" for="talla">Semanas.</label>	
	        
	        <label class="margin_left_20px_cel margin_top_5px input_width_80px_cel" for="peso">Peso:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px margin_top_5pxcel" id="ant_perinaltes_peso" name="ant_perinaltes_peso"  placeholder="0"/>
			<label class="margin_left_5px margin_top_5px input_width_appx_cel2" for="peso">Kg.</label>	

			<label class="margin_left_20px_cel margin_top_5px input_width_80px_cel" for="talla">Talla:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px margin_top_5pxcel" id="ant_perinaltes_talla" name="ant_perinaltes_talla"  placeholder="0"/>
	        <label class="margin_left_5px margin_top_5px input_width_appx_cel2" for="talla">Cm.</label>	

	        <label class="margin_left_20px_cel margin_top_5px input_width_80px_cel" for="pc">PC:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px margin_top_5pxcel" id="ant_perinaltes_pc" name="ant_perinaltes_pc"  placeholder="0"/>
	        <label class="margin_left_5px margin_top_5px input_width_appx_cel2" for="pc">Cm.</label>	

	        <label class="margin_left_20px_cel margin_top_5px input_width_80px_cel" for="apgar">Apgar:</label>
	        <input type="text" class="form-control input_width_80px margin_left_5px margin_top_5pxcel" id="ant_perinaltes_apgar" name="ant_perinaltes_apgar"  placeholder="0"/>	            
    	   
	</div>

	<div class="row margin_top_12px">		
			<label class="margin_left_5px margin_top_5px" for="ant_perinaltes_caida_cordon">Caída Cordón:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="ant_perinaltes_caida_cordon" name="ant_perinaltes_caida_cordon"  placeholder="0"/> 
	        <label class="margin_left_5px margin_top_5px" for="ant_perinaltes_caida_cordon">días.</label>
	</div>

	<div class="row margin_top_12px">		
			<label class="margin_left_5px margin_top_5px" for="ant_perinaltes_meconio">Meconio:</label>
	        <input type="number" class="form-control input_width_80px margin_left_5px" id="ant_perinaltes_meconio" name="ant_perinaltes_meconio"  placeholder="0"/> 
	        <label class="margin_left_5px margin_top_5px" for="ant_perinaltes_meconio">días.</label>
	</div>

	<div class="row margin_top_12px">		
			<label class="margin_left_5px margin_top_5px" for="ant_perinaltes_gyf">G y F:</label>
	        <input type="text" class="form-control input_width_60px margin_left_5px" id="ant_perinaltes_gyf" name="ant_perinaltes_gyf"  placeholder=""/> 	        
	</div>

	<div class="row margin_top_5px">		
	 	<div>
			<label class="margin_left_5px">FEI:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input onclick="clickFeiNormal()" type="radio" id="ant_perinatales_fei_normal" name="fei_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatales_fei_normal">Normal</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input onclick="clickFeiAnormal()" type="radio" id="ant_perinatales_fei_anormal" name="fei_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatales_fei_anormal">Anormal</label>
				</div>
				<input hidden type="text" class="form-control input_width_350px margin_left_5px" id="ant_perinatales_fei_anormal_detalle" name="ant_perinatales_fei_anormal_detalle"/>								
			</div>			
		</div>

		<div>			
			<label class="margin_left_60px">VDRL</label>
			<div class="row margin_left_60px">
			  	<div class="custom-control custom-radio margin_left_5px">
			  		<input onclick="clickVdrlSi(1)" type="radio" id="ant_perinatles_vdrl_si" name="vdrl_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_vdrl_si">+</label>
				</div>
				<div class="custom-control custom-radio">
					 <input onclick="clickVdrlNo(1)" type="radio" id="ant_perinatles_vdrl_no" name="vdrl_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_vdrl_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="ant_perinatles_vdrl_si_detalle" name="ant_perinatles_vdrl_si_detalle"></textarea>								
			</div>	
			<br><label class="margin_left_60px">Chagas</label>
			<div class="row margin_left_60px">							  	
			  	<div class="custom-control custom-radio margin_left_5px ">			  		
			  		<input onclick="clickChagasSi(3)" type="radio" id="ant_perinatles_chagas_si" name="chagas_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatles_chagas_si">+</label>
				</div>				 
				<div class="custom-control custom-radio ">					 
					 <input onclick="clickChagasNo(3)" type="radio" id="ant_perinatles_chagas_no" name="chagas_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatles_chagas_no">-</label>
				</div>
				<textarea hidden class="width350px_cel form-control margin_left_5px" rows="2" id="ant_perinatles_chagas_si_detalle" name="ant_perinatles_chagas_si_detalle"></textarea>								
			</div>				
		</div>
	</div>

	<div class="row margin_top_5px">		
	 	<div>
			<label class="margin_left_5px">OEA:</label>      
			<div class="row margin_left_5px">
			  	<div class="custom-control custom-radio margin_left_5px margin_top_5px">
			  		<input type="radio" id="ant_perinatales_oea_presente" name="oea_group" class="custom-control-input">
			  		<label class="custom-control-label" for="ant_perinatales_oea_presente">Presentes</label>
				</div>
				<div class="custom-control custom-radio margin_top_5px">
					 <input type="radio" id="ant_perinatales_oea_ausente" name="oea_group" class="custom-control-input">
					 <label class="custom-control-label margin_left_5px" for="ant_perinatales_oea_ausente">Ausentes</label>
				</div>									
			</div>			
		</div>
	</div>

</form>

<script type="text/javascript">
	function clickSerologiaSi(trimestre) {
		if(trimestre == 1){
			document.getElementById("ant_perinatles_serologia1_si_detalle").hidden = false;
		} else {
			document.getElementById("ant_perinatles_serologia3_si_detalle").hidden = false;
		}
	}

	function clickSerologiaNo(trimestre) {
		if(trimestre == 1){
			document.getElementById("ant_perinatles_serologia1_si_detalle").hidden = true; 	
			document.getElementById("ant_perinatles_serologia1_si_detalle").value = ''; 
		} else {
			document.getElementById("ant_perinatles_serologia3_si_detalle").hidden = true; 	
			document.getElementById("ant_perinatles_serologia3_si_detalle").value = ''; 
		}
	}

	function clickPatologiaSi(){
		document.getElementById("ant_perinatles_patologia_si_detalle").hidden = false;
	}

	function clickPatologiaNo(){
		document.getElementById("ant_perinatles_patologia_si_detalle").hidden = true; 	
		document.getElementById("ant_perinatles_patologia_si_detalle").value = ''; 
	}
	function clickHisopSBHAsi(){
		document.getElementById("ant_perinatles_hisopsbha_si_detalle").hidden = false;
	}

	function clickHisopSBHAno(){
		document.getElementById("ant_perinatles_hisopsbha_si_detalle").hidden = true; 	
		document.getElementById("ant_perinatles_hisopsbha_si_detalle").value = ''; 
	}
	function clickFeiNormal(){
		document.getElementById("ant_perinatales_fei_anormal_detalle").hidden = true; 	
		document.getElementById("ant_perinatales_fei_anormal_detalle").value = ''; 
	}

	function clickFeiAnormal(){
		document.getElementById("ant_perinatales_fei_anormal_detalle").hidden = false; 	
	}
	function clickVdrlSi(){
		document.getElementById("ant_perinatles_vdrl_si_detalle").hidden = false;
	}

	function clickVdrlNo(){
		document.getElementById("ant_perinatles_vdrl_si_detalle").hidden = true; 	
		document.getElementById("ant_perinatles_vdrl_si_detalle").value = ''; 
	}
	function clickChagasSi(){
		document.getElementById("ant_perinatles_chagas_si_detalle").hidden = false;
	}

	function clickChagasNo(){
		document.getElementById("ant_perinatles_chagas_si_detalle").hidden = true; 	
		document.getElementById("ant_perinatles_chagas_si_detalle").value = ''; 
	}

	function guardarAntecedentesPerinatales(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;
		var embarazo = document.getElementById("ant_perinatales_embarazo").value;
		var embarazo_numero_controles = document.getElementById("ant_perinatales_embarazo_numero_controles").value;
		var patologias_check_si = document.getElementById("ant_perinatles_patologia_si").checked;
		var patologias_check_no = document.getElementById("ant_perinatles_patologia_no").checked;
		var hisop_sbha_check_si = document.getElementById("ant_perinatles_hisopsbha_si").checked;
		var hisop_sbha_check_no = document.getElementById("ant_perinatles_hisopsbha_no").checked;

		var serologia1_check_si = document.getElementById("ant_perinatles_serologia1_si").checked;
		var serologia1_check_no = document.getElementById("ant_perinatles_serologia1_no").checked;
		var serologia3_check_si = document.getElementById("ant_perinatles_serologia3_si").checked;
		var serologia3_check_no = document.getElementById("ant_perinatles_serologia3_no").checked;

		var vdrl_check_si = document.getElementById("ant_perinatles_vdrl_si").checked;
		var vdrl_check_no = document.getElementById("ant_perinatles_vdrl_no").checked;
		var chagas_check_si = document.getElementById("ant_perinatles_chagas_si").checked;
		var chagas_check_no = document.getElementById("ant_perinatles_chagas_no").checked;

		var patologia = 2;
		var patologias_detalle = "";
		if(patologias_check_si){
			patologia = 1;
			patologias_detalle = document.getElementById("ant_perinatles_patologia_si_detalle").value;
		}
		if(patologias_check_no){
			patologia = 0;
		}
		var hisop_sbha = 2;
		var hisop_sbha_detalle = "";
		if(hisop_sbha_check_si){
			hisop_sbha = 1;
			hisop_sbha_detalle = document.getElementById("ant_perinatles_hisopsbha_si_detalle").value;
		}
		if(hisop_sbha_check_no){
			hisop_sbha = 0;
		}

		var serologia1 = 2;
		var serologia1_detalle = "";
		if(serologia1_check_si){
			serologia1 = 1;
			serologia1_detalle = document.getElementById("ant_perinatles_serologia1_si_detalle").value;
		}
		if(serologia1_check_no){
			serologia1 = 0;
		}

		var serologia3 = 2;
		var serologia3_detalle = "";
		if(serologia3_check_si){
			serologia3 = 1;
			serologia3_detalle = document.getElementById("ant_perinatles_serologia3_si_detalle").value;
		}
		if(serologia3_check_no){
			serologia3 = 0;
		}

		var vdrl = 2;
		var vdrl_detalle = "";
		if(vdrl_check_si){
			vdrl = 1;
			vdrl_detalle = document.getElementById("ant_perinatles_vdrl_si_detalle").value;
		}
		if(vdrl_check_no){
			vdrl = 0;
		}

		var chagas = 2;
		var chagas_detalle = "";
		if(chagas_check_si){
			chagas = 1;
			chagas_detalle = document.getElementById("ant_perinatles_chagas_si_detalle").value;
		}
		if(chagas_check_no){
			chagas = 0;
		}
		
		var parto = document.getElementById("ant_perinatales_parto").value;
		var parto_detalle = document.getElementById("ant_perinatales_parto_detalle").value;
		var eg = document.getElementById("ant_perinaltes_eg").value;
		var peso = document.getElementById("ant_perinaltes_peso").value;
		var talla = document.getElementById("ant_perinaltes_talla").value;
		var pc = document.getElementById("ant_perinaltes_pc").value;
		var apgar = document.getElementById("ant_perinaltes_apgar").value;
		var caida_cordon = document.getElementById("ant_perinaltes_caida_cordon").value;
		var meconio = document.getElementById("ant_perinaltes_meconio").value;
		var gyf = document.getElementById("ant_perinaltes_gyf").value;
		var fei = 2;		
		var fei_anormal_detalle = "";
		var fei_normal = document.getElementById("ant_perinatales_fei_normal").checked;
		var fei_anormal = document.getElementById("ant_perinatales_fei_anormal").checked;
		if(fei_normal){
			fei = 1;			
		}
		if(fei_anormal){
			fei = 0;
			fei_anormal_detalle = document.getElementById("ant_perinatales_fei_anormal_detalle").value;
		}
		var oea = 2;
		var oea_presente = document.getElementById("ant_perinatales_oea_presente").checked;
		var oea_ausente = document.getElementById("ant_perinatales_oea_ausente").checked;
		if(oea_presente)
			oea = 1;
		if(oea_ausente)
			oea = 0;
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/guardar_antecedentes_perinatales',
           data:{consulta:consulta, paciente:paciente, embarazo :embarazo,embarazo_numero_controles:embarazo_numero_controles,patologia:patologia,patologias_detalle:patologias_detalle, parto:parto, parto_detalle:parto_detalle, eg:eg, peso:peso, talla:talla, pc:pc, apgar:apgar, caida_cordon:caida_cordon, meconio:meconio, gyf:gyf, fei:fei, fei_anormal_detalle:fei_anormal_detalle, oea:oea,hisop_sbha:hisop_sbha,hisop_sbha_detalle:hisop_sbha_detalle, serologia1:serologia1, serologia1_detalle:serologia1_detalle, serologia3:serologia3, serologia3_detalle:serologia3_detalle, vdrl:vdrl, vdrl_detalle:vdrl_detalle, chagas:chagas, chagas_detalle:chagas_detalle,_token: '{{csrf_token()}}'},
           	success:function(data){              
           		if(data.response == 1){
           			//alert("Guardado con exito actividades extra escolares");
            	} else {
            		mostrarSanckbar("ANTECEDENTES PERINATALES FALLO");
            		//alert("Fallo al guardar");
            	}
            }
        });
	}

	function cargarAntecedentesPerinatales(){
		var consulta = document.getElementById("consulta_id").value;
		var paciente = document.getElementById("paciente_id").value;		
		
		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/cargar_antecedentes_perinatales',
           data:{consulta:consulta, paciente:paciente,_token: '{{csrf_token()}}'},
           	success:function(data){                         		
           		if(data.response_data!=null && data.response == 1){         
         			document.getElementById("ant_perinatales_embarazo").value = data.response_data.embarazo;
					document.getElementById("ant_perinatales_embarazo_numero_controles").value = data.response_data.embarazo_controles;
					if(data.response_data.patologias == 1) {
						document.getElementById("ant_perinatles_patologia_si").checked = true;
						document.getElementById("ant_perinatles_patologia_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_patologia_si_detalle").value = data.response_data.patologias_detalle;
					} else {
						if(data.response_data.patologias == 0)
							document.getElementById("ant_perinatles_patologia_no").checked = true;  			  			
					}

					if(data.response_data.hisop_sbha == 1) {
						document.getElementById("ant_perinatles_hisopsbha_si").checked = true;
						document.getElementById("ant_perinatles_hisopsbha_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_hisopsbha_si_detalle").value = data.response_data.hisop_sbha_detalle;
					} else {
						if(data.response_data.hisop_sbha == 0)
							document.getElementById("ant_perinatles_hisopsbha_no").checked = true;  			  			
					}

					if(data.response_data.serologia1 == 1) {
						document.getElementById("ant_perinatles_serologia1_si").checked = true;
						document.getElementById("ant_perinatles_serologia1_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_serologia1_si_detalle").value = data.response_data.serologia1_detalle;
					} else {
						if(data.response_data.serologia1 == 0)
							document.getElementById("ant_perinatles_serologia1_no").checked = true;  			  			
					}

					if(data.response_data.serologia3 == 1) {
						document.getElementById("ant_perinatles_serologia3_si").checked = true;
						document.getElementById("ant_perinatles_serologia3_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_serologia3_si_detalle").value = data.response_data.serologia3_detalle;
					} else {
						if(data.response_data.serologia3 == 0)
							document.getElementById("ant_perinatles_serologia3_no").checked = true;  			  			
					}

					if(data.response_data.vdrl == 1) {
						document.getElementById("ant_perinatles_vdrl_si").checked = true;
						document.getElementById("ant_perinatles_vdrl_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_vdrl_si_detalle").value = data.response_data.vdrl_detalle;
					} else {
						if(data.response_data.vdrl == 0)
							document.getElementById("ant_perinatles_vdrl_no").checked = true;  			  			
					}

					if(data.response_data.chagas == 1) {
						document.getElementById("ant_perinatles_chagas_si").checked = true;
						document.getElementById("ant_perinatles_chagas_si_detalle").hidden = false;
						document.getElementById("ant_perinatles_chagas_si_detalle").value = data.response_data.chagas_detalle;
					} else {
						if(data.response_data.chagas == 0)
							document.getElementById("ant_perinatles_chagas_no").checked = true;  			  			
					}

					document.getElementById("ant_perinatales_parto").value = data.response_data.parto;
					document.getElementById("ant_perinatales_parto_detalle").value = data.response_data.parto_detalle;
           			document.getElementById("ant_perinaltes_eg").value = data.response_data.eg;
					document.getElementById("ant_perinaltes_peso").value = data.response_data.peso;
					document.getElementById("ant_perinaltes_talla").value = data.response_data.talla;
					document.getElementById("ant_perinaltes_pc").value = data.response_data.pc;
					document.getElementById("ant_perinaltes_apgar").value = data.response_data.apgar;
					document.getElementById("ant_perinaltes_caida_cordon").value = data.response_data.caida_cordon;
					document.getElementById("ant_perinaltes_meconio").value = data.response_data.meconio;
					document.getElementById("ant_perinaltes_gyf").value = data.response_data.gyf;

					if(data.response_data.fei == 1) {
						document.getElementById("ant_perinatales_fei_normal").checked = true;						
					} else {
						if(data.response_data.fei == 0) {
							document.getElementById("ant_perinatales_fei_anormal").checked = true;  			  			
							document.getElementById("ant_perinatales_fei_anormal_detalle").hidden = false;
							document.getElementById("ant_perinatales_fei_anormal_detalle").value = data.response_data.fei_anormal_detalle;
						}
					}

					if(data.response_data.oea == 1) {
						document.getElementById("ant_perinatales_oea_presente").checked = true;						
					} else {
						if(data.response_data.oea == 0)
							document.getElementById("ant_perinatales_oea_ausente").checked = true;  			  			
					}
            	} else {
            		//mostrarSanckbar("ANTECEDENTES PERINATALES FALLO");            		
            	}
            }
        });	
	}

</script>