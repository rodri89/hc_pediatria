
@extends('plantillas/plantilla_medico')

@section('title_header','Seleccionar Paciente')

@section('contenedor')

@include('medico.consulta_seleccionar_paciente')

<script type="text/javascript">	
	function navegarNuevaConsulta(paciente_id){
		 $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/navegar_consultas',
             data:{paciente_id:paciente_id, _token: '{{csrf_token()}}'},
             success:function(data) {                 
              	window.location.href = "/paciente_consultas/"+data.paciente_id;             
              }
          });          
		                    
   }
</script>

@endsection