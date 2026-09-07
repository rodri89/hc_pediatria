
@extends('plantillas/plantilla_medico')

@section('title_header','Certificado Aptitud Fisico')

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
			<input type="text" id="dni" name="dni" value="{{$paciente->dni}}" hidden/>		
			<input type="text" id="apellido" name="apellido" value="{{$paciente->apellido}}" hidden/>		
			<input type="text" id="nombre" name="nombre" value="{{$paciente->nombre}}" hidden/>		
			<input type="text" id="fecha" name="fecha" value="{{$fecha}}" hidden/>		

			<div class="input_width_850px">
				<p id="texto_general" class="class_p">{{$paciente->apellido.', '.$paciente->nombre}}, DNI: {{$paciente->dni}}, de {{$edad}} años de edad;
				ha sido evaluado clínicamente y se encuentra en condiciones de realizar actividades 
				físicas no competitivas y deportivas acordes a su edad, sexo, estadio madurativo y bajo supervisión de 
				personal idóneo.</p>
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
	

	function imprimirCertificado() {
	  
	 // var ventana = window.open('', 'PRINT', 'height=400,width=600');
	  var ventana = window.open(' ', 'popimpr');
	  var texto_general = document.getElementById("texto_general");	  
	  var certificado_observacion = document.getElementById("certificado_observacion");
	  var fecha_firma = document.getElementById("fecha_imprimir");	  	   
	  var fecha = document.getElementById("fecha");	  	   

	  ventana.document.write( '<br><br>' );	
	  ventana.document.write('<p style="font-size:1.2rem;">'+texto_general.innerHTML+'</p>');
	  
	  ventana.document.write( '<p style="font-size:1.2rem;">Observaciones:</p>' );	  
	  ventana.document.write('<p style="font-size:1.2rem; padding-bottom:20px">'+certificado_observacion.value+'</p>');
	  ventana.document.write( '<br>' );	  
	  //ventana.document.write('<p style="margin-left:150px;">'+fecha.value+'</p>');
	  ventana.document.write('<p style="margin-left:100px; display: inline;">.......................................</p><p style="margin-left:250px; display: inline;">.......................................</p>');
	  ventana.document.write( '<br>' );	  
	  ventana.document.write('<p style="margin-left:150px; display: inline;">Fecha</p><p style="margin-left:330px; display: inline;">Firma y Sello Médico</p>');
	  ventana.document.close();
	  ventana.print( );
	  ventana.close();
	}

	window.onload=function() {
	    mostrarPanelPaciente(true);
	    mostrarPanelHistoriaClinica(false);
    }

</script>

@endsection

