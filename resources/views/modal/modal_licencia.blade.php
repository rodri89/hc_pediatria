<div class="modal fade" id="licenciaModal" tabindex="-1" role="dialog" aria-labelledby="licenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="licenciaModalLabel">Licencia</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>        
        <div class="modal-body"><p id="licencia_valida_dia"></p></div>        
        <br>
      </div>
    </div>
 </div>

 <div class="modal fade" id="avisoLicenciaModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-header">      	
        <h4 class="modal-title"         
        id="modalTitleMensaje">Licencia</h4>
      </div>      
      
      <div class="modal-body">   
     <div class="modal-body">
    	<p>Su licencia esta proxima a expirar.</p>
    	<p id="aviso_fecha"></p>
    	<p>Para renovar la licencia pongase en contacto con el administrador.</p>
    	<p>Muchas Gracias</p>
      </div>
      
      <div class="modal-footer">
        <button id="modal_aviso_continuar" type="button" 
           class="rodri_button_aceptar" 
           onclick="avisoLicenciaContinuar()" 
           data-dismiss="modal">Continuar</button>           
      </div>
    </div>
  </div>
</div>
</div>

  <script type="text/javascript">
  	
  	function mostrarLicenciaExpira(){
  		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/ver_licencia_expira',
           data:{_token: '{{csrf_token()}}'},
           	success:function(data){              
           		var fecha_aux = data.licencia.fecha_expiracion_licencia;
           		var fechaMostrar_aux = fecha_aux.split("-");
           		var fechaMostrar = fechaMostrar_aux[2]+"/"+fechaMostrar_aux[1]+"/"+fechaMostrar_aux[0];
           		document.getElementById("licencia_valida_dia").innerHTML = "Su licencia es válida hasta el día "+fechaMostrar;
           		 $('#licenciaModal').modal();  
            }
        });	
  	}

	function mostrarAvisoLicenciaExpira(){
  		$.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/ver_licencia_expira',
           data:{_token: '{{csrf_token()}}'},
           	success:function(data){           		    		           		             
           		var fecha_aux = data.licencia.fecha_expiracion_licencia;
           		var fechaMostrar_aux = fecha_aux.split("-");
           		var fechaMostrar = fechaMostrar_aux[2]+"/"+fechaMostrar_aux[1]+"/"+fechaMostrar_aux[0];
           		document.getElementById("aviso_fecha").innerHTML = "Fecha expiración: "+fechaMostrar;
           		$('#avisoLicenciaModal').modal();             		
            }
        });	
  	}  	

  	function avisoLicenciaContinuar(){
  		location.href = '/medico_home';
  	}

  </script>