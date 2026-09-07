
@extends('plantillas/plantilla_medico')

@section('title_header','Resumen Historia Clinica')

@section('contenedor')

<style type="text/css">
    @media print {
       .class_p{
       	color:red;
       }
    }
</style>

<form class="card background_panel_consulta_actual contenedor3">	
	<div class="row">
		<h4><b></b></h4>
	</div>	
	<div id="seccion_conductas">						
		<br>
		<br>
		<div class="row margin_left_5px contenido3">	
			<input type="text" id="paciente_id" name="paciente_id" value="{{$paciente->id}}" hidden/>		
			<input type="text" id="consulta_id" name="consulta_id" value="{{$consulta->id}}" hidden/>		
			<input type="text" id="dni" name="dni" value="{{$paciente->dni}}" hidden/>		
			<input type="text" id="apellido" name="apellido" value="{{$paciente->apellido}}" hidden/>		
			<input type="text" id="nombre" name="nombre" value="{{$paciente->nombre}}" hidden/>		
			<input type="text" id="fecha" name="fecha" value="{{$fecha}}" hidden/>		

			<div class="input_width_850px">
				<div id="seccion_resumen">

				</div>

				<div id="seccion_resumen_imprimir" hidden>

				</div>
				
				<p>Observaciones:</p>

				<textarea class="input_width_850px " id="certificado_observacion" name="certificado_observacion" rows="6">	              
				
				</textarea>
				<br><br><br><br>
				<div class="row" id="fecha_firma">
					<div class="input_width_250px margin_left_100px">
					<!--<p style="border-bottom: 1px dashed" class="text-center">{{$fecha}}</p>					-->
					<p style="padding-top: 4px">........................................................</p>
					<p id="fecha_imprimir" class="text-center" >Fecha</p>
					</div>
					<div class="input_width_250px margin_left_200px">						
						<p style="padding-top: 4px">........................................................</p>
						<p class="text-center">Firma y sello del médico</p>
					</div>					
				</div>
			</div>	
		</div>

		<br>
	</div>		
	<div class="row contenedor3">
    	<button onclick="imprimirCertificado()" class="rodri_button contenido3">IMPRIMIR</button>
	</div>
	<br>
	
</form>

<script type="text/javascript">
  
  function cargarResumen(consulta_id, paciente_id){
    $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/cargar_resumen_consulta',
         data:{paciente_id:paciente_id, consulta_id:consulta_id, _token: '{{csrf_token()}}'},
         success:function(data) {           
            var seccion_resumen_imprimir = document.getElementById('seccion_resumen_imprimir');
            var seccion_resumen = document.getElementById('seccion_resumen');
            var br = document.createElement("BR");
                var myNode = document.getElementById("seccion_resumen");
				while (myNode.firstChild) {
				   myNode.removeChild(myNode.firstChild);
				}
				var myNodeR = document.getElementById("seccion_resumen_imprimir");
				while (myNodeR.firstChild) {
				   myNodeR.removeChild(myNodeR.firstChild);
				}

			if(data.response_paciente != null) {
				var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Paciente";
                seccion_resumen.appendChild(title);
                var infoPaciente = '';
                if(data.response_paciente.nombre.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Nombre", data.response_paciente.nombre, seccion_resumen);                                  
                  infoPaciente = "<b>Nombre: </b>"+data.response_paciente.nombre;
                }
                if(data.response_paciente.apellido.localeCompare('')!=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("Apellido", data.response_paciente.apellido, seccion_resumen);                                  
                  infoPaciente += "<b> Apellido: </b>"+data.response_paciente.apellido;
                }
                if(data.response_paciente.dni !=1 ){
                  //bajarRenglon(seccion_resumen);            
                  addText("DNI", data.response_paciente.dni, seccion_resumen);                                  
                  infoPaciente += "<b> DNI: </b>"+data.response_paciente.dni;
                }
                if(data.response_paciente_edad != null ){
                  //bajarRenglon(seccion_resumen);
                  var edad = calcularEdad2(data.response_paciente.fecha_nacimiento);            
                  addText("Edad", edad, seccion_resumen);                                  
                  infoPaciente += "<b> Edad: </b>"+edad;
                }                
                if(data.response_paciente.fecha_nacimiento.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);
                  var fechaMostrarAux = data.response_paciente.fecha_nacimiento.split("-");
                  var fechaMostar = fechaMostrarAux[2]+"/"+fechaMostrarAux[1]+"/"+fechaMostrarAux[0];            
                  addText("Fecha Nacimiento", fechaMostar, seccion_resumen);                                  
                  infoPaciente += "<br><b> Fecha Nacimiento: </b>"+fechaMostar;
                }
                if(data.response_paciente.obra_social.localeCompare('')!=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("Obra Social", data.response_paciente.obra_social, seccion_resumen);                                  
                  infoPaciente += "<b> Obra Social: </b>"+data.response_paciente.obra_social;
                }
                if(data.response_paciente.numero_afiliado.localeCompare('')!=0 && data.response_paciente.obra_social.localeCompare("PARTICULAR")!=0 ){
                  //bajarRenglon(seccion_resumen);            
                  addText("Numero Afiliado", data.response_paciente.numero_afiliado, seccion_resumen);                                  
                  infoPaciente += "<b> Numero Afiliado: </b>"+data.response_paciente.numero_afiliado;
                }
                var p_paciente = document.createElement("P");
                p_paciente.id = 'paciente_imprimir';
                p_paciente.innerHTML = infoPaciente;
                seccion_resumen_imprimir.appendChild(p_paciente);
			}

			if(data.response_antecedentes_perinatales != null) {
				bajarRenglon(seccion_resumen);
				var brap = document.createElement("BR");
                seccion_resumen.appendChild(brap);

				var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Antecedentes Perinatales";
                seccion_resumen.appendChild(title);
                var infoAntecendentesPerinatales = '';
				if(data.response_antecedentes_perinatales.embarazo.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Embarazo", data.response_antecedentes_perinatales.embarazo, seccion_resumen);                                  
                  infoAntecendentesPerinatales = "<b>Embarazo:</b> "+data.response_antecedentes_perinatales.embarazo;
                }
                if(data.response_antecedentes_perinatales.embarazo_controles !=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("Cantidad Controles", data.response_antecedentes_perinatales.embarazo_controles, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Cantidad Controles:</b> "+data.response_antecedentes_perinatales.embarazo_controles+"<br>";
                }
                if(data.response_antecedentes_perinatales.patologias !=0 && data.response_antecedentes_perinatales.patologias_detalle.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Patologias", data.response_antecedentes_perinatales.patologias_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Patologias:</b> "+data.response_antecedentes_perinatales.patologias_detalle+"<br>";
                }
                if(data.response_antecedentes_perinatales.hisop_sbha == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Hisop SBHA", "Positivo. "+data.response_antecedentes_perinatales.hisop_sbha_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Hisop SBHA: </b>Positivo. "+data.response_antecedentes_perinatales.hisop_sbha_detalle+"<br>";
                } else {
                  if(data.response_antecedentes_perinatales.hisop_sbha == 0){
                    bajarRenglon(seccion_resumen);            
                    addText("Hisop SBHA", "Negativo.", seccion_resumen);
                    infoAntecendentesPerinatales += " <b> Hisop SBHA: </b>Negativo.<br>";
                  } 
                }
                if(data.response_antecedentes_perinatales.serologia1 == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Serología 1° Trim", "Positivo. "+data.response_antecedentes_perinatales.serologia1_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Serología 1° Trim: </b> Positivo. "+data.response_antecedentes_perinatales.serologia1_detalle+"<br>";
                } else {
                  if(data.response_antecedentes_perinatales.serologia1 == 0){
                    bajarRenglon(seccion_resumen);            
                    addText("Serología 1° Trim", "Negativo.", seccion_resumen);
                    infoAntecendentesPerinatales += " <b> Serología 1° Trim: </b> Negativo. <br>";
                  }
                }

                if(data.response_antecedentes_perinatales.serologia3 == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Serología 3° Trim", "Positivo. "+data.response_antecedentes_perinatales.serologia3_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Serología 3° Trim: </b> Positivo. "+data.response_antecedentes_perinatales.serologia3_detalle+"<br>";
                } else {
                  if(data.response_antecedentes_perinatales.serologia3 == 0){
                    bajarRenglon(seccion_resumen);            
                    addText("Serología 3° Trim", "Negativo.", seccion_resumen);
                    infoAntecendentesPerinatales += " <b> Serología 3° Trim: </b> Negativo. <br>";
                  }
                }

                if(data.response_antecedentes_perinatales.parto !=0 && data.response_antecedentes_perinatales.parto_detalle.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Parto", data.response_antecedentes_perinatales.parto+". "+data.response_antecedentes_perinatales.parto_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Parto:</b> "+data.response_antecedentes_perinatales.parto+". "+data.response_antecedentes_perinatales.parto_detalle+"<br>";
                }
                if(data.response_antecedentes_perinatales.eg.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("EG", data.response_antecedentes_perinatales.eg+" semanas", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> EG:</b> "+data.response_antecedentes_perinatales.eg+" semanas";
                }
                if(data.response_antecedentes_perinatales.peso.localeCompare('')!=0){
                 // bajarRenglon(seccion_resumen);            
                  addText("Peso", data.response_antecedentes_perinatales.peso+" Kg.", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Peso:</b> "+data.response_antecedentes_perinatales.peso+" Kg.";
                }
                if(data.response_antecedentes_perinatales.talla.localeCompare('')!=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("Talla", data.response_antecedentes_perinatales.talla+" Cm.", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Talla:</b> "+data.response_antecedentes_perinatales.talla+" Cm.";
                }
                if(data.response_antecedentes_perinatales.pc.localeCompare('')!=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("PC", data.response_antecedentes_perinatales.pc+" Cm.", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> PC:</b> "+data.response_antecedentes_perinatales.pc+" Cm.<br>";
                }
                if(data.response_antecedentes_perinatales.apgar.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Apgar", data.response_antecedentes_perinatales.apgar, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Apgar:</b> "+data.response_antecedentes_perinatales.apgar+"<br>";
                }
                if(data.response_antecedentes_perinatales.caida_cordon !=0){
                  //bajarRenglon(seccion_resumen);            
                  addText("Caida Cordon", data.response_antecedentes_perinatales.caida_cordon+" días", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Caida Cordon:</b> "+data.response_antecedentes_perinatales.caida_cordon+" días<br>";
                }
                if(data.response_antecedentes_perinatales.meconio !=0){
                  bajarRenglon(seccion_resumen);            
                  addText("Meconio", data.response_antecedentes_perinatales.meconio+" días", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Meconio:</b> "+data.response_antecedentes_perinatales.meconio+" días<br>";
                }
                if(data.response_antecedentes_perinatales.gyf.localeCompare('')!=0){
                  bajarRenglon(seccion_resumen);            
                  addText("GyF", data.response_antecedentes_perinatales.gyf, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> GyF:</b> "+data.response_antecedentes_perinatales.gyf+"<br>";
                }
                if(data.response_antecedentes_perinatales.fei == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("FEI", "Normal", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> FEI:</b> Normal <br>";
                }
                if(data.response_antecedentes_perinatales.fei == 2){
                  bajarRenglon(seccion_resumen);            
                  addText("FEI", "Anormal. "+data.response_antecedentes_perinatales.fei_anormal_detalle, seccion_resumen);
                 infoAntecendentesPerinatales += " <b> FEI:</b> Anormal. "+data.response_antecedentes_perinatales.fei_anormal_detalle+"<br>"; 
                }
                if(data.response_antecedentes_perinatales.vdrl == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("VDRL", "Positivo. "+data.response_antecedentes_perinatales.vdrl_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> VDRL: </b> Positivo. "+data.response_antecedentes_perinatales.vdrl_detalle+"<br>";
                } else {
                  if(data.response_antecedentes_perinatales.vdrl == 0){
                    bajarRenglon(seccion_resumen);            
                    addText("VDRL", "Negativo.", seccion_resumen);
                    infoAntecendentesPerinatales += " <b> VDRL: </b> Negativo. <br>";
                  }
                }
                if(data.response_antecedentes_perinatales.chagas == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Chagas", "Positivo. "+data.response_antecedentes_perinatales.chagas_detalle, seccion_resumen);
                  infoAntecendentesPerinatales += " <b> Chagas: </b> Positivo. "+data.response_antecedentes_perinatales.chagas_detalle+"<br>";
                } else {
                  if(data.response_antecedentes_perinatales.chagas == 0){
                    bajarRenglon(seccion_resumen);            
                    addText("Chagas", "Negativo.", seccion_resumen);
                    infoAntecendentesPerinatales += " <b> Chagas: </b> Negativo. <br>";
                  }
                }
                if(data.response_antecedentes_perinatales.oea == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("OEA", "Presentes", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> OEA:</b> Presentes <br>";
                }
                if(data.response_antecedentes_perinatales.oea == 2){
                  bajarRenglon(seccion_resumen);            
                  addText("OEA", "Ausentes", seccion_resumen);
                  infoAntecendentesPerinatales += " <b> OEA: </b> Ausentes <br>";
                }

                var p_antecedentes_perinatales = document.createElement("P");
                p_antecedentes_perinatales.id = 'antecedentes_perinatales_imprimir';
                p_antecedentes_perinatales.innerHTML = infoAntecendentesPerinatales;
                seccion_resumen_imprimir.appendChild(p_antecedentes_perinatales);
			}

      if(data.response_antecedentes_neonatales != null && data.response_antecedentes_neonatales.nota.localeCompare('')!=0){         
                bajarRenglon(seccion_resumen);                     
                var infoAntNeonatales = '';
                var br1 = document.createElement("BR");
                seccion_resumen.appendChild(br1);           
                addTituloDescripcion("Antecedentes Neonatales", data.response_antecedentes_neonatales.nota, seccion_resumen);                
                infoAntNeonatales += data.response_antecedentes_neonatales.nota;
                
                var p_ant_neonatales = document.createElement("P");
                p_ant_neonatales.id = 'ant_neonatales_imprimir';
                p_ant_neonatales.innerHTML = infoAntNeonatales;
                seccion_resumen_imprimir.appendChild(p_ant_neonatales);                
            }

          if(data.modulo_vacunas == 2){
            var infoVacunas = '';
            if(data.vacunas_response != null && data.vacunas_response.descripcion.localeCompare('')!=0){
                bajarRenglon(seccion_resumen);
                //var brv = document.createElement("BR");
                //seccion_resumen.appendChild(brv);           
                addTituloDescripcion("Vacunas", data.vacunas_response.descripcion, seccion_resumen);
                infoVacunas += data.vacunas_response.descripcion;
                
                var p_vacunas = document.createElement("P");
                p_vacunas.id = 'vacunas_imprimir';
                p_vacunas.innerHTML = infoVacunas;
                seccion_resumen_imprimir.appendChild(p_vacunas);                
            }
          } else {
              var infoVacunas = '';
              if(data.vacunas_response.length>0){
                bajarRenglon(seccion_resumen);
                addTituloDescripcion("Vacunas", "", seccion_resumen);
                var iv = 0; 
                var otro = "";
                for(iv; iv<data.vacunas_response.length; iv++){
                  if(data.vacunas_response[iv].vacuna_id == 21){
                    otro = data.vacunas_response[iv].nombre;
                  } else {
                    if(data.vacunas_response[iv].vac_count == 1){
                      addText3(data.vacunas_response[iv].nombre, seccion_resumen);
                      infoVacunas += ''+ data.vacunas_response[iv].nombre+'<br>';
                    } else{
                        if(data.vacunas_response[iv].vac_count == 2){
                          addText3(data.vacunas_response[iv].nombre + ": 1° y 2° dosis", seccion_resumen);
                          infoVacunas += data.vacunas_response[iv].nombre + ': 1° y 2° dosis<br>';
                        }
                        if(data.vacunas_response[iv].vac_count == 3){
                          addText3(data.vacunas_response[iv].nombre + ": 1°, 2° y 3° dosis", seccion_resumen);
                          infoVacunas += data.vacunas_response[iv].nombre + ': 1°, 2° y 3° dosis<br>';
                        }
                    }
                  }
                }
                if(otro.localeCompare("")!=0){
                    addText3("Otras: " + otro, seccion_resumen);
                    infoVacunas += "Otras: " + otro+ '<br>';
                }
                var p_vacunas = document.createElement("P");
                p_vacunas.id = 'vacunas_imprimir';
                p_vacunas.innerHTML = infoVacunas;
                seccion_resumen_imprimir.appendChild(p_vacunas); 
              }
          }

            if(data.response_alimentacion != null && data.alimentacion_mostrar == 1) {
            	  var infoAlimentacion = '';
                bajarRenglon(seccion_resumen);
				        var bra = document.createElement("BR");
                seccion_resumen.appendChild(bra);

                var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Alimentación";
                seccion_resumen.appendChild(title);
                
                if(data.response_alimentacion.pecho == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Pecho", data.response_alimentacion.pecho_detalle, seccion_resumen);                                  
                  infoAlimentacion += '<b> Pecho: </b> '+data.response_alimentacion.pecho_detalle+'<br>';
                }
                if(data.response_alimentacion.leche_maternizada == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Lecha Maternizada", data.response_alimentacion.leche_maternizada_detalle, seccion_resumen);                                  
                  infoAlimentacion += '<b> Leche Maternizada: </b> '+data.response_alimentacion.leche_maternizada_detalle+'<br>';
                }
                if(data.response_alimentacion.leche_vaca == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Lecha Vaca", data.response_alimentacion.leche_vaca_detalle, seccion_resumen);                  
                  infoAlimentacion += '<b> Leche Vaca: </b> '+data.response_alimentacion.leche_vaca_detalle+'<br>';
                }
                if(data.response_alimentacion.dieta_tipo.localeCompare('')!=0 || data.response_alimentacion.dieta_comidas.localeCompare('')!=0){
                    var ptitle_a4 = document.createElement("P");
                    ptitle_a4.setAttribute('class', 'rodri_bold margin_left_10px');                                   
                    ptitle_a4.innerHTML = "Dieta: ";
                    infoAlimentacion += '<b> Dieta: </b> <br>';   
                    seccion_resumen.appendChild(ptitle_a4);
                    seccion_resumen.appendChild(br);                                  
                  if(data.response_alimentacion.dieta_tipo.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Tipo", data.response_alimentacion.dieta_tipo, seccion_resumen);                    
                    infoAlimentacion += '<b> Tipo: </b> '+data.response_alimentacion.dieta_tipo+'<br>';
                  }
                   if(data.response_alimentacion.dieta_comidas.localeCompare('')!=0){
                    bajarRenglon(seccion_resumen);            
                    addText("Comidas/Día", data.response_alimentacion.dieta_comidas, seccion_resumen);                    
                    infoAlimentacion += '<b> Comidas/Día: </b> '+data.response_alimentacion.dieta_comidas+'<br>';
                  }
                }
                if(data.response_alimentacion.hierro == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Hierro", data.response_alimentacion.hierro_dosis, seccion_resumen);                    
                  infoAlimentacion += '<b> Hierro: </b> '+data.response_alimentacion.hierro_dosis+'<br>';
                }                  
                if(data.response_alimentacion.vitamina == 1){
                  bajarRenglon(seccion_resumen);            
                  addText("Vitaminas", data.response_alimentacion.vitamina_dosis, seccion_resumen);
                  infoAlimentacion += '<b> Vitaminas: </b> '+data.response_alimentacion.vitamina_dosis+'<br>';
                }                                              
                
                seccion_resumen.appendChild(br);        

                var p_alimentacion = document.createElement("P");
                p_alimentacion.id = 'alimentacion_imprimir';
                p_alimentacion.innerHTML = infoAlimentacion;
                seccion_resumen_imprimir.appendChild(p_alimentacion);                
            }

            if(data.response_escolaridad != null && data.response_escolaridad.descripcion.localeCompare('')!=0){                              
                var infoEscolaridad = '';
                var br1 = document.createElement("BR");
                seccion_resumen.appendChild(br1);           
                addTituloDescripcion("Escolaridad", data.response_escolaridad.descripcion, seccion_resumen);                
                infoEscolaridad += data.response_escolaridad.descripcion;
                
                var p_escolaridad = document.createElement("P");
                p_escolaridad.id = 'escolaridad_imprimir';
                p_escolaridad.innerHTML = infoEscolaridad;
                seccion_resumen_imprimir.appendChild(p_escolaridad);                
            }
                  
            if(data.response_menarca != null && data.response_menarca.descripcion.localeCompare('')!=0){
                var infoMenarca = '';
                addTituloDescripcion("Menarca", data.response_menarca.descripcion, seccion_resumen);                 
                infoMenarca += data.response_menarca.descripcion;
                
                var p_menarca = document.createElement("P");
                p_menarca.id = 'menarca_imprimir';
                p_menarca.innerHTML = infoMenarca;
                seccion_resumen_imprimir.appendChild(p_menarca);                
            } 
                      

            if(data.response_examen_fisicos != null && data.response_examen_fisico_completo == 1) {                 
                var infoExamenFisico = '';
                var title = document.createElement("P");                
                title.setAttribute('class', 'rodri_bold font_size_resumen');
                title.innerHTML = "Exámen Físico";                
                seccion_resumen.appendChild(title);                               
                
                if(data.response_examen_fisicos.peso != null && data.response_examen_fisicos.peso.localeCompare("")!=0){                    
                  bajarRenglon(seccion_resumen);            
                  addText("Peso", data.response_examen_fisicos.peso+" kg.", seccion_resumen);                                  
                  infoExamenFisico += '<b> Peso: </b> '+data.response_examen_fisicos.peso+" kg.";
                  if(data.response_examen_fisicos.peso_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.peso_percentil, seccion_resumen);
                    infoExamenFisico += '<b> Percentil: </b> '+data.response_examen_fisicos.peso_percentil+'<br>';
                  }
                }

                if(data.response_examen_fisicos.talla != null && data.response_examen_fisicos.talla.localeCompare("")!=0){                        
                  bajarRenglon(seccion_resumen);            
                  addText("Talla", data.response_examen_fisicos.talla+" mts.", seccion_resumen);                                  
                  infoExamenFisico += '<b> Talla: </b> '+data.response_examen_fisicos.talla+" mts";
                  if(data.response_examen_fisicos.talla_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.talla_percentil, seccion_resumen);
                    infoExamenFisico += '<b> Percentil: </b> '+data.response_examen_fisicos.talla_percentil+'<br>';
                  }
                }

                if(data.response_examen_fisicos.pc != null && data.response_examen_fisicos.pc.localeCompare("")!=0){                      
                  bajarRenglon(seccion_resumen);            
                  addText("PC", data.response_examen_fisicos.pc+" cm.", seccion_resumen);                                  
                  infoExamenFisico += '<b> PC: </b> '+data.response_examen_fisicos.pc+" cm.";
                  if(data.response_examen_fisicos.pc_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.pc_percentil, seccion_resumen);                                                      
                    infoExamenFisico += '<b> Percentil: </b> '+data.response_examen_fisicos.pc_percentil+'<br>';
                  }
                }            

                if(data.response_examen_fisicos.ipd != null && data.response_examen_fisicos.ipd.localeCompare("")!=0){                          
                  bajarRenglon(seccion_resumen);            
                  addText("IPD", data.response_examen_fisicos.ipd + " gr/dia.", seccion_resumen);                                                    
                  infoExamenFisico += '<b> IPD: </b> '+data.response_examen_fisicos.ipd+' gr/dia.<br>';
                }

                if(data.response_examen_fisicos.ta != null && data.response_examen_fisicos.ta.localeCompare("")!=0){                    
                  bajarRenglon(seccion_resumen);            
                  addText("TA", data.response_examen_fisicos.ta+" mm HG.", seccion_resumen);                                                    
                  infoExamenFisico += '<b> TA: </b> '+data.response_examen_fisicos.ta+' mm HG.<br>';
                }

                if(data.response_examen_fisicos.imc != null && data.response_examen_fisicos.imc.localeCompare("")!=0){                        
                  bajarRenglon(seccion_resumen);            
                  addText("IMC", data.response_examen_fisicos.imc, seccion_resumen);                                  
                  infoExamenFisico += '<b> IMC: </b> '+data.response_examen_fisicos.imc;
                  if(data.response_examen_fisicos.imc_percentil != null){
                    addText("Percentil", data.response_examen_fisicos.imc_percentil, seccion_resumen);                                                      
                    infoExamenFisico += '<b> Percentil: </b> '+data.response_examen_fisicos.imc_percentil+'<br>';
                  }
                }

              if(data.response_examen_fisicos.nota != null && data.response_examen_fisicos.nota.localeCompare("")!=0){
                  bajarRenglon(seccion_resumen);            
                  addText2("Examen Físico", data.response_examen_fisicos.nota, seccion_resumen);                                                    
                  infoExamenFisico += '<b> Examen Físico: </b> '+data.response_examen_fisicos.nota;
                }
                
                  bajarRenglon(seccion_resumen);
                  var brds = document.createElement("BR");
                  seccion_resumen.appendChild(brds);

                  var p_examen_fisico = document.createElement("P");
                  p_examen_fisico.id = 'examen_fisico_imprimir';
                  p_examen_fisico.innerHTML = infoExamenFisico;
                  seccion_resumen_imprimir.appendChild(p_examen_fisico);                
                
            }    

            if(data.response_examenes_complementarios != null && data.response_examenes_complementarios.length > 0) {
              var infoExamenesComplementarios = '';
              var title = document.createElement("P");                
              title.setAttribute('class', 'rodri_bold font_size_resumen');
              title.innerHTML = "Examenes Complementarios";                
              seccion_resumen.appendChild(title);                               

              var tope = data.response_examenes_complementarios.length; 
              for(i=0; i<tope; i++) {
                addText("Solicito", data.response_examenes_complementarios[i].solicito, seccion_resumen);
                infoExamenesComplementarios += '<b> Solicito: </b>'+ data.response_examenes_complementarios[i].solicito+'<br>';
                bajarRenglon(seccion_resumen);
                addText("Respuesta", data.response_examenes_complementarios[i].respuesta, seccion_resumen);
                infoExamenesComplementarios += '<b> Respuesta: </b><br>'+ data.response_examenes_complementarios[i].respuesta+'<br><br>';
                bajarRenglon(seccion_resumen);
                var newBr = document.createElement("BR");          
                seccion_resumen.appendChild(newBr);          
              }
                var p_examenes_complementarios = document.createElement("P");
                p_examenes_complementarios.id = 'examenes_complementarios_imprimir';
                p_examenes_complementarios.innerHTML = infoExamenesComplementarios;
                seccion_resumen_imprimir.appendChild(p_examenes_complementarios);                
            } else {
                var br2 = document.createElement("BR");
                seccion_resumen.appendChild(br2);
            }                    

            if(data.response_interconsultas != null && data.response_interconsultas.length > 0) {
              var infoInterconsultas = '';
              var title = document.createElement("P");                
              title.setAttribute('class', 'rodri_bold font_size_resumen');
              title.innerHTML = "Interconsultas";                
              seccion_resumen.appendChild(title);                               

              var tope = data.response_interconsultas.length; 
              for(i=0; i<tope; i++) {
                addText("Especialista", data.response_interconsultas[i].especialista, seccion_resumen);
                infoInterconsultas += '<b> Especialista: </b>'+ data.response_interconsultas[i].especialista+'<br>';
                bajarRenglon(seccion_resumen);
                addText("Solicito", data.response_interconsultas[i].solicito, seccion_resumen);
                infoInterconsultas += '<b> Solicito: </b>'+ data.response_interconsultas[i].solicito+'<br>';
                bajarRenglon(seccion_resumen);
                addText("Respuesta", data.response_interconsultas[i].respuesta, seccion_resumen);
                infoInterconsultas += '<b> Respuesta: </b><br>'+ data.response_interconsultas[i].respuesta+'<br><br>';
                bajarRenglon(seccion_resumen);
                var newBr = document.createElement("BR");          
                seccion_resumen.appendChild(newBr);          
              }     
              var p_interconsultas = document.createElement("P");
              p_interconsultas.id = 'interconsultas_imprimir';
              p_interconsultas.innerHTML = infoInterconsultas;
              seccion_resumen_imprimir.appendChild(p_interconsultas);                           
            } else {
                var br2 = document.createElement("BR");
                seccion_resumen.appendChild(br2);
            }

          }

      }); 
  }

  function bajarRenglon(seccion){
      var ptitle_aux = document.createElement("P");
      ptitle_aux.setAttribute('class', 'rodri_bold');                 
      ptitle_aux.innerHTML = "";
      seccion.appendChild(ptitle_aux);
  }

    // text1: text2  + bajada de renlgon
  function addText(text1, text2, seccion_resumen){                    
      var ptitle_a1 = document.createElement("P");
      ptitle_a1.setAttribute('class', 'rodri_bold rodri_inline_margin');                 
      ptitle_a1.innerHTML = text1+": "; 
      seccion_resumen.appendChild(ptitle_a1);                                  
      var p_a1 = document.createElement("P");
      p_a1.innerHTML = text2;
      p_a1.setAttribute('class', 'rodri_resumen_p rodri_inline');                                   
      seccion_resumen.appendChild(p_a1);
  }

  // text1: 
  // text2  
  function addText2(text1, text2, seccion_resumen){                    
      var ptitle_a1 = document.createElement("P");
      var br = document.createElement("BR");
      ptitle_a1.setAttribute('class', 'rodri_bold margin_left_10px');                 
      ptitle_a1.innerHTML = text1+": "; 
      seccion_resumen.appendChild(ptitle_a1);                                                                          
      var p_a1 = document.createElement("P");
      p_a1.innerHTML = text2;
      p_a1.setAttribute('class', 'rodri_resumen_p margin_left_10px');                                   
      seccion_resumen.appendChild(p_a1);      
  }

  // text1 + bajada de renlgon
  function addText3(text1, seccion_resumen){                          
      var ptitle_a1 = document.createElement("P");      
      ptitle_a1.setAttribute('class', 'margin_left_10px');                 
      ptitle_a1.innerHTML = text1; 
      seccion_resumen.appendChild(ptitle_a1);                                                                                
  }



  function addTituloDescripcion(text1, text2, seccion_resumen){                    
    var title = document.createElement("P");
    var br = document.createElement("BR");
    var p = document.createElement("P");
    title.setAttribute('class', 'rodri_bold font_size_resumen');
    title.innerHTML = text1;
    p.innerHTML = text2;
    p.setAttribute('class', 'rodri_resumen_p margin_left_10px'); 
    seccion_resumen.appendChild(title);
    seccion_resumen.appendChild(p);       
    seccion_resumen.appendChild(br);       
  }


  function imprimirCertificado() {
	  
	 // var ventana = window.open('', 'PRINT', 'height=400,width=600');
	  var ventana = window.open(' ', 'popimpr');
	  //var texto_general = document.getElementById("texto_general");	  
	  var certificado_observacion = document.getElementById("certificado_observacion");
	  var fecha_firma = document.getElementById("fecha_imprimir");	  	   
	  var fecha = document.getElementById("fecha");	  	   

	  ventana.document.write( '<br><br>' );	
	  //ventana.document.write('<p style="font-size:1.2rem;">'+texto_general.innerHTML+'</p>');
	  ventana.document.write('<p style="font-size:1.2rem;">PACIENTE</p>');
	  var infoPaciente = document.getElementById("paciente_imprimir").innerHTML;
	  ventana.document.write( '<p style="font-size:1.2rem;">'+infoPaciente+'</p>' );	  

	  var infoAntecendentesPerinatales = document.getElementById("antecedentes_perinatales_imprimir");
	  if(infoAntecendentesPerinatales != null){
		  ventana.document.write('<p style="font-size:1.2rem;">ANTECEDENTES PERINATALES</p>');		  
		  ventana.document.write( '<p style="font-size:1.2rem;">'+infoAntecendentesPerinatales.innerHTML+'</p>' );	  
	   }

     var infoAntecendentesNeonatales = document.getElementById("ant_neonatales_imprimir");
    if(infoAntecendentesNeonatales != null){
      ventana.document.write('<p style="font-size:1.2rem;">ANTECEDENTES NEONATALES</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoAntecendentesNeonatales.innerHTML+'</p>' );    
     }

     var infoVacunas = document.getElementById("vacunas_imprimir");
    if(infoVacunas != null){
      ventana.document.write('<p style="font-size:1.2rem;">VACUNAS</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoVacunas.innerHTML+'</p>' );    
     }

     var infoAlimentacion = document.getElementById("alimentacion_imprimir");
    if(infoAlimentacion != null){
      ventana.document.write('<p style="font-size:1.2rem;">ALIMENTACION</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoAlimentacion.innerHTML+'</p>' );    
     }

     var infoEscolaridad = document.getElementById("escolaridad_imprimir");
    if(infoEscolaridad != null){
      ventana.document.write('<p style="font-size:1.2rem;">ESCOLARIDAD</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoEscolaridad.innerHTML+'</p>' );    
     }

     var infoMenarca = document.getElementById("menarca_imprimir");
    if(infoMenarca != null){
      ventana.document.write('<p style="font-size:1.2rem;">MENARCA</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoMenarca.innerHTML+'</p>' );    
     }

     var infoExamenFisico = document.getElementById("examen_fisico_imprimir");
    if(infoExamenFisico != null){
      ventana.document.write('<p style="font-size:1.2rem;">EXAMEN FISICO</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoExamenFisico.innerHTML+'</p>' );    
     }

     var infoExamenesComplementarios = document.getElementById("examenes_complementarios_imprimir");
    if(infoExamenesComplementarios != null){
      ventana.document.write('<p style="font-size:1.2rem;">EXAMENES COMPLEMENTARIOS</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoExamenesComplementarios.innerHTML+'</p>' );    
     }

     var infoInterconsultas = document.getElementById("interconsultas_imprimir");
    if(infoInterconsultas != null){
      ventana.document.write('<p style="font-size:1.2rem;">INTERCONSULTAS</p>');      
      ventana.document.write( '<p style="font-size:1.2rem;">'+infoInterconsultas.innerHTML+'</p>' );    
     }

	  ventana.document.write( '<p style="font-size:1.2rem;">Observaciones:</p>' );	  	  
	  ventana.document.write('<p style="font-size:1.2rem; padding-bottom:80px">'+certificado_observacion.value+'</p>');
	  ventana.document.write( '<br>' );	  
	  //ventana.document.write('<p style="margin-left:150px;">'+fecha.value+'</p>');
	  ventana.document.write('<p style="margin-left:100px; display: inline;">.......................................</p><p style="margin-left:250px; display: inline;">.......................................</p>');
	  ventana.document.write( '<br>' );	  
	  ventana.document.write('<p style="margin-left:150px; display: inline;">Fecha</p><p style="margin-left:330px; display: inline;">Firma y Sello Médico</p>');
	  ventana.document.close();
	  ventana.print( );
	  ventana.close();
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

        if(edad == 0){
          if(meses == 1)
            return "1 mes";
          else
            return meses+" meses";
        } else {
          if(edad == 1)
            return "1 año";
          else
            return edad + " años";
        }
    }

  window.onload=function() {
  	var consulta_id = document.getElementById("consulta_id").value;
  	var paciente_id = document.getElementById("paciente_id").value;
    mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
	  cargarResumen(consulta_id, paciente_id);  	

  }

</script>

@endsection

