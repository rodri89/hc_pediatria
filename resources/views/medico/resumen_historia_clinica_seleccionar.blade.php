
@extends('plantillas/plantilla_medico')

@section('title_header','Seleccionar Paciente')

@section('contenedor')

@include('medico.caf_seleccionar_paciente')

<script type="text/javascript">
	
  function navegarCertificado(paciente_id){
      $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/navegar_resumen_historia_clinica',
             data:{paciente_id:paciente_id, _token: '{{csrf_token()}}'},
             success:function(data) {                 
                window.location.href = "/resumen_historia_clinica";              
              }
          });
   }

</script>

@endsection