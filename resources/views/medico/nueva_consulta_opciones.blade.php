
@extends('plantillas/plantilla_medico')

@section('title_header','Seleccionar Tipo Consulta')

@section('contenedor')

<div class="row contenedor3 margin_top_150px">
  <div class="row contenido3 ">
  <input hidden text id="paciente_id" value="{{$paciente->id}}"/>   
    <form method="post" action="{{ route('crearnuevaconsulta') }}">                   
        @csrf  
        <input hidden text id="paciente_idd" name="paciente_id" value="{{$paciente->id}}"/>   
        <input hidden text id="tipo_consulta" name="tipo_consulta" value="1"/>   
        <input hidden text id="consulta_foto" name="consulta_foto" value="0"/>   
        <button  class="btn btn-primary-outline img-responsive img_opcion">
          <img type="submit" class="card-img-top " src="/img/iconos/control_salud.png" alt="">
        </button>
        <div class="card-body">
          <h6 class="fontImage" align="center">Control Salud</h6>      
        </div>        
    </form>

    <form method="post" action="{{ route('crearnuevaconsulta') }}">                   
        @csrf  
        <input hidden text id="paciente_idd" name="paciente_id" value="{{$paciente->id}}"/>   
        <input hidden text id="tipo_consulta" name="tipo_consulta" value="2"/>   
        <input hidden text id="consulta_foto" name="consulta_foto" value="0"/>           
        <button type="submit" class="btn btn-primary-outline img-responsive img_opcion">
          <img class="card-img-top " src="/img/iconos/enfermedad.png" alt="">
        </button>
        <div class="card-body">
          <h6 class="fontImage" align="center">Enfermedad</h6>      
        </div>        
    </form>

    <form method="post" action="{{ route('crearnuevaconsulta') }}">                   
        @csrf  
        <input hidden text id="paciente_idd" name="paciente_id" value="{{$paciente->id}}"/>   
        <input hidden text id="tipo_consulta" name="tipo_consulta" value="4"/>   
        <input hidden text id="consulta_foto" name="consulta_foto" value="0"/>           
        <button type="submit" class="btn btn-primary-outline img-responsive img_opcion">
          <img class="card-img-top " src="/img/iconos/telemedicina.png" alt="">
        </button>
        <div class="card-body">
          <h6 class="fontImage" align="center">Telemedicina</h6>      
        </div>        
    </form>
    @if($medico_id == 2 || $medico_id == 15)
    <form method="post" action="{{ route('crearnuevaconsulta') }}">                   
        @csrf  
        <input hidden text id="paciente_idd" name="paciente_id" value="{{$paciente->id}}"/>   
        <input hidden text id="tipo_consulta" name="tipo_consulta" value="6"/>   
        <input hidden text id="consulta_foto" name="consulta_foto" value="0"/>           
        <button type="submit" class="btn btn-primary-outline img-responsive img_opcion">
          <img class="card-img-top " src="/img/iconos/lactancia.png" alt="">
        </button>
        <div class="card-body">
          <h6 class="fontImage" align="center">Lactancia</h6>      
        </div>        
    </form>

    @endif
    @if($medico_id == 2)
    <form method="post" action="{{ route('crearnuevaconsulta') }}">                   
        @csrf  
        <input hidden text id="paciente_idd" name="paciente_id" value="{{$paciente->id}}"/>   
        <input hidden text id="tipo_consulta" name="tipo_consulta" value="5"/>   
        <input hidden text id="consulta_foto" name="consulta_foto" value="0"/>           
        <button type="submit" class="btn btn-primary-outline img-responsive img_opcion">
          <img class="card-img-top " src="/img/iconos/consulta_prenatal.png" alt="">
        </button>
        <div class="card-body">
          <h6 class="fontImage" align="center">Consulta Prenatal</h6>      
        </div>        
    </form>
    @endif
    
</div>
</div>

<script type="text/javascript">
   window.onload=function() {
    mostrarPanelPaciente(true);
      mostrarPanelHistoriaClinica(false);
   }
</script>

@endsection