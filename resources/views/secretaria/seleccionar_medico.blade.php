
@extends('plantillas/plantilla_secretaria')

@section('title_header','Seleccionar Medico')

@section('contenedor')

<div class="row">
  @foreach($medicos as $medico)
	<!--<div class="col-md-2 mb-3 ">-->
    <form method="POST" action="{{ route('continuarnavegacion') }}">
        @csrf
        <input type="hidden" name="opcion" value="{{$opcion}}"  /> 
        <input type="hidden" name="medico_id" value="{{$medico->medico_id}}"  />   		    
    	<button class="btn btn-primary-outline img-responsive img_home">
         <img class="card-img-top " src="img/medicos/{{$medico->foto}}" alt="">
        </button>
		<div class="card-body">
  			<h6 class="fontImage" align="center">{{$medico->name}}</h6>      
		</div>    	  
    </form>
    <!--</div> -->
    @endforeach
</div>

@endsection