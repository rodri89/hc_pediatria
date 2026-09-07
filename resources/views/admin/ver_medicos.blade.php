@include('modal.snackbar')
<div id="seccion_tabla_buscar_paciente" class="table-responsive margin_top_20px">
      <table class="table table-condensed" id="tabla_pacientes" name="tabla_pacientes">
       <thead class="fondoNav text-white">
          <tr>
            <th class="editText" scope="col">#</th>          	
            <th class="editText">Licencia Id</th>
            <th class="editText">Medico</th>
            <th class="editText">Licencia</th>
            <th class="editText">Aviso Licencia</th>
            <th class="editText">Importe</th>
            <th class="editText">Activo</th>                        
          </tr>
        </thead>
        <tbody id="pacientes-list" name="pacientes-list">
      	    <?php $indice = 1;?>
      	    @foreach($medicos as $medico)                             	
            	<tr>
                	<th class="editText" scope="row">{{$indice++}}</th>
                	<td class="editText">{{$medico->mlid}}</td>
	                <td class="editText">{{$medico->name}}</td>                  	                
	                
	                <td class="editText"><input onchange="actualizarExpLicencia('{{$medico->mlid}}')" type="text" id="fecha_expiracion_licencia_{{$medico->mlid}}" class="input_width_150px" value="{{$medico->fecha_expiracion_licencia}}"></td>
	                
	                <td class="editText"><input onchange="actualizarAvisoLicencia('{{$medico->mlid}}')" type="text" id="fecha_aviso_expiracion_{{$medico->mlid}}" class="input_width_150px" value="{{$medico->fecha_aviso_expiracion}}"></td>

	                <td class="editText"><input onchange="actualizarImporte('{{$medico->mlid}}')" type="text" id="importe_{{$medico->mlid}}" class="input_width_150px" value="{{$medico->importe}}"></td>

	                <td class="editText"><input onchange="actualizarActivo('{{$medico->mlid}}')" type="text" id="ml_activo_{{$medico->mlid}}" class="input_width_150px" value="{{$medico->mlactivo}}"></td>	                
              	</tr>
            @endforeach
      </tbody>
    </table>
</div>

<script type="text/javascript">
	
	function actualizarExpLicencia(id){
		var nuevaFecha = document.getElementById("fecha_expiracion_licencia_"+id).value;
		$.ajax({
	       type:'POST',
	       dataType:'JSON',
	       url:'/update_exp_licencia',
	       data:{ nuevaFecha:nuevaFecha, id:id, _token: '{{csrf_token()}}'},
	        success:function(data) {   
	        	mostrarSnackbar("Exp Licencia Actualizado");               
	        }
	    });
	}

	function actualizarAvisoLicencia(id){
		var nuevaFecha = document.getElementById("fecha_aviso_expiracion_"+id).value;
		$.ajax({
	       type:'POST',
	       dataType:'JSON',
	       url:'/update_aviso_licencia',
	       data:{ nuevaFecha:nuevaFecha, id:id, _token: '{{csrf_token()}}'},
	        success:function(data) {                  
	        	mostrarSnackbar("Aviso Licencia Actualizado");
	        }
	    });	
	}

	function actualizarImporte(id){
		var importe = document.getElementById("importe_"+id).value;
		$.ajax({
	       type:'POST',
	       dataType:'JSON',
	       url:'/update_importe',
	       data:{ importe:importe, id:id, _token: '{{csrf_token()}}'},
	        success:function(data) {  
	        	mostrarSnackbar("Importe Actualizado");                
	        }
	    });
	}

	function actualizarActivo(id){
		var activo = document.getElementById("ml_activo_"+id).value;
		$.ajax({
	       type:'POST',
	       dataType:'JSON',
	       url:'/update_activo',
	       data:{ activo:activo, id:id, _token: '{{csrf_token()}}'},
	        success:function(data) {                  
	        	mostrarSnackbar("Activo Actualizado");
	        }
	    });
	}

</script>
