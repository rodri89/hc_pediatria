
@extends('plantillas/plantilla_medico')

@section('title_header','Seleccionar Paciente')

@section('contenedor')

@include('medico.caf_seleccionar_paciente')

<script type="text/javascript">
	
  function navegarCertificado(paciente_id){
      $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/navegar_certificado_aptitud_fisico',
             data:{paciente_id:paciente_id, _token: '{{csrf_token()}}'},
             success:function(data) {                 
                window.location.href = "/certficado_aptitud_fisico";              
              }
          });
   }

</script>

@endsection