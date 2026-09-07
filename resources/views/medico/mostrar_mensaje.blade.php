
@extends('plantillas/plantilla_medico')

@section('title_header','Consultas Paciente')

@section('contenedor')

<div class="row contenedor3 margin_top_150px">
  <div class="row contenido3 ">
    <h1>{{$mensaje}}</h1>
  </div>
</div>

<script type="text/javascript">
   window.onload=function() {
    mostrarPanelPaciente(true);
      mostrarPanelHistoriaClinica(false);
   }
</script>

@endsection