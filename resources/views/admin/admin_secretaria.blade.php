@extends('plantillas/plantilla_admin')

@section('title_header','Admin Secretarias')

@section('body_titulo','')

@section('contenedor')

<div class="row">
      <div class="col-md-6">  
      <h2> Vincular secretaria con Medico:</h2>
      <div class="col-md-6">  
        <form method="POST" action="{{ route('vincularsecretariamedico') }}">
          @csrf          
          <div class="form-group">
          <label for="sel1">Secretaria:</label>
          <select class="form-control" id="sel1" name="secretaria">
            <option>N/A</option>            
            @foreach($secretarias as $secretaria)
            <option>{{$secretaria->id.'-'.$secretaria->name}}</option>            
            @endforeach
          </select>
          </div>
          
          <div class="form-group">
          <label for="sel1">Medico:</label>
          <select class="form-control" id="sel1" name="medico">
            <option>N/A</option>            
            @foreach($medicos as $medico)
            <option>{{$medico->id.'-'.$medico->name}}</option>            
            @endforeach
          </select>
          </div>
          <div>
            <button class="rodri_button">Vincular</button>
            <!--<a href="turno_registrado" class="btn btn-primary">Registrar</a>-->
          </div>
        </form>
      </div>
    </div>
</div>

@endsection