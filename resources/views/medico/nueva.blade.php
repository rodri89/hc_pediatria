
@extends('plantillas/plantilla_medico')

@section('title_header','Seleccionar Paciente')

@section('contenedor')

@include('medico.consulta_seleccionar_paciente')

<script type="text/javascript">	
	function navegarNuevaConsulta(paciente_id){    
     var consulta_foto_check = document.getElementById("consulta_con_foto").checked;  
     var consulta_foto = 0;
     if(consulta_foto_check)
      consulta_foto = 1;
     $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/crear_nueva_consulta',
             data:{paciente_id:paciente_id, consulta_foto:consulta_foto, _token: '{{csrf_token()}}'},
             success:function(data) {              	
             window.location.href = "/navegar_consulta_seleccionada";
              //window.location.href = "/consulta/"+data.paciente_id+"/"+data.consulta_id;               
              }
          });
   }
</script>

@endsection