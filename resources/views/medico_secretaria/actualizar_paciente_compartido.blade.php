<form> 
  <div class="row">
      <div class="col-md-1 margin_left_30px"></div>
      <div class="col-md-5">
        <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }} "/>
        <input type="hidden" id="medico_id" name="medico_id" value="{{$medico_id}} "/>

        <label for="text" class="col-sm-0 control-label">* DNI</label>
          @if($paciente==null) 
          <input type="text" class="form-control input_width_150px" id="dni" name="dni" placeholder="DNI" value="" required/>
          {!! $errors->first('dni','<small>:message</small><br>') !!}                     
          <input type="hidden" id="paciente_id" name="paciente_id" value=""/>
          @else
          <input type="text" class="form-control input_width_150px" id="dni" name="dni" value="{{$paciente->dni}}"  required/>  
          <input hidden type="text" id="paciente_id" name="paciente_id" value="{{$paciente->id}} " onchange="validarPacienteExisteId()"/>                          
          @endif    
    
        <label for="text" class="col-sm-0 control-label">* Nombre</label>      
        <input type="text" class="form-control input_width_250px" id="nombre" name="nombre"  placeholder="Nombre Paciente" value="" required />

        <label for="text" class="col-sm-0 control-label">* Apellido</label>      
        <input type="text" class="form-control input_width_250px" id="apellido" name="apellido"  placeholder="Apellido Paciente" required />

         <label for="text" class="col-sm-0 control-label">Fecha Nacimiento</label><br>
        <div class="row">
           <div class="col-md-2">
            <input type="text" maxlength="2" class="form-control" id="fecha_nacimiento_dia" name="fecha_nacimiento"  placeholder="dd" required />
          </div>
           <label for="text" class="col-sm-0 control-label">/</label>
          <div class="col-md-2">
            <input type="text" maxlength="2" class="form-control" id="fecha_nacimiento_mes" name="fecha_nacimiento"  placeholder="mm" required />
          </div>
          <label for="text" class="col-sm-0 control-label">/</label>
          <div class="col-md-3">   
            <input type="text" maxlength="4" class="form-control" id="fecha_nacimiento_anio" name="fecha_nacimiento"  placeholder="YYYY" required />
          </div>
       </div>

      <label for="text" class="col-sm-0 control-label">Sexo</label>   
        <div class="margin_left_5px">
          <div class="row margin_left_5px">
            <div class="custom-control custom-radio">
              <input type="radio" id="sexo_m" name="sexo" class="custom-control-input">
              <label class="custom-control-label" for="sexo_m">M</label>
            </div>
            <div class="custom-control custom-radio">
              <input type="radio" id="sexo_f" name="sexo" class="custom-control-input">
              <label class="custom-control-label margin_left_5px" for="sexo_f">F</label>
            </div>
          </div>        
        </div>

         <label for="text" class="col-sm-0 control-label">Hermanos</label>
          <div>          
              <select class="form-control input_width_60px margin_left_5px" id="cantidad_hermanos" name="cantidad_hermanos">            
                <option>0</option>
                <option>1</option><option>2</option><option>3</option>            
                <option>4</option><option>5</option><option>6</option>        
                <option>7</option><option>8</option><option>9</option>        
            </select>
          </div>               

        <label for="text" class="col-sm-0 control-label">Localidad</label>      
        <input type="text" class="form-control input_width_350px" id="localidad" name="localidad"  placeholder="Localidad"  />

        <label for="text" class="col-sm-0 control-label">Domicilio</label>      
        <input type="text" class="form-control input_width_350px" id="domicilio" name="domicilio"  placeholder="Domicilio"  />
      
        <br>                 
    </div>

    <div class="col-md-5">
            <label for="text" class="col-sm-0 control-label">Telefono</label>      
            <input type="text" class="form-control input_width_250px" id="telefono" name="telefono"  placeholder="Solo Numeros"  />

            <label for="text" class="col-sm-0 control-label">Mail</label>      
            <input type="text" class="form-control input_width_350px" id="mail" name="mail" placeholder="Mail"  />

            <label for="text" class="col-sm-0 control-label">Nombre Madre</label>      
            <input type="text" class="form-control input_width_250px" id="nombre_madre" name="nombre_madre" placeholder="Nombre Madre"  />

            <label for="text" class="col-sm-0 control-label">Nombre Padre</label>      
            <input type="text" class="form-control input_width_250px" id="nombre_padre" name="nombre_padre" placeholder="Nombre Padre"  />
            <br>
           
            <label for="text" class="col-sm-0 control-label">Obra Social</label>      
            <input type="text" class="form-control input_width_250px" id="obrasocial" name="obra_social" placeholder="Obra Social"  />

            <label for="text" class="col-sm-0 control-label">N° Afiliado</label>      
            <input type="text" class="form-control input_width_250px" id="numero_afiliado" name="numero_afiliado" placeholder="N° Afiliado"  />
            
            <label for="text" class="col-sm-0 control-label">Plan</label>      
            <input type="text" class="form-control input_width_250px" id="plan_obra_social" name="plan_obra_social" placeholder="Plan Obra Social"  />
               <br>
             <label for="text" class="col-sm-0 control-label">(*) Campos Requeridos</label>              
    </div>  
  </div>
  <br>
  <div class="row contenedor3">            
    <button onclick="actualizarPaciente()" type="button" id="btnContinuar" class="rodri_button contenido3">Actualizar</button>
  </div>
  <br>
</form>

@include('modal.snackbar')

<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function validarPacienteExiste(){
    var paciente_id = document.getElementById("paciente_id");     
    var dni = document.getElementById("dni").value;     
    var user_id = document.getElementById("user_id").value;     
    var medico_id = document.getElementById("medico_id").value;

    if(paciente_id.value.localeCompare('')==0) {
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/consultar_paciente',
         data:{dni_paciente :dni, user_id:user_id, medico_id:medico_id, _token: '{{csrf_token()}}'},
         success:function(data){      
            var nombre = document.getElementById("nombre");
            var apellido = document.getElementById("apellido");
            var dni = document.getElementById("dni");
            var telefono = document.getElementById("telefono");
            var domicilio = document.getElementById("domicilio");
            var mail = document.getElementById("mail");
            var obrasocial = document.getElementById("obrasocial");          
            var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia");
            var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes");
            var fecha_nacimiento_anio = document.getElementById("fecha_nacimiento_anio");          
            var plan_obra_social = document.getElementById("plan_obra_social");
            var numero_afiliado = document.getElementById("numero_afiliado");    

            var localidad = document.getElementById("localidad");
            var sexo_m = document.getElementById("sexo_m");
            var sexo_f = document.getElementById("sexo_f"); 
            var cantidad_hermanos = document.getElementById("cantidad_hermanos"); 
            var nombre_madre = document.getElementById("nombre_madre");
            var nombre_padre = document.getElementById("nombre_padre");
            var paciente_id = document.getElementById("paciente_id");                 

             if(data.paciente != null) {   
                
                nombre.value = data.paciente.nombre;
                apellido.value = data.paciente.apellido;
                dni.value = data.paciente.dni;
                telefono.value = data.paciente.telefono;
                domicilio.value = data.paciente.domicilio;
                mail.value = data.paciente.mail;
                obrasocial.value = data.paciente.obra_social;              
                numero_afiliado.value = data.paciente.numero_afiliado;              
                plan_obra_social.value = data.paciente.obra_social_plan;

                localidad.value = data.paciente.localidad;
                nombre_padre.value = data.paciente.nombre_padre;
                nombre_madre.value = data.paciente.nombre_madre;
                cantidad_hermanos.value = data.paciente.cantidad_hermanos;
                if(data.paciente.sexo == "M")
                  sexo_m.checked = true;
                else
                  sexo_f.checked = true;
                if(data.paciente.fecha_nacimiento.localeCompare('')!=0){
                  var arrayFechaNacimiento = data.paciente.fecha_nacimiento.split('-');
                   if(arrayFechaNacimiento[0].localeCompare("1000")==0){
                    fecha_nacimiento_anio.value = "";
                    fecha_nacimiento_mes.value = "";
                    fecha_nacimiento_dia.value = "";
                   } else {
                    fecha_nacimiento_anio.value = arrayFechaNacimiento[0];
                    fecha_nacimiento_mes.value = arrayFechaNacimiento[1];
                    fecha_nacimiento_dia.value = arrayFechaNacimiento[2];
                  }
                } 
              } else{ 
                nombre.value = '';
                apellido.value = '';              
                telefono.value = '';
                domicilio.value = '';
                mail.value = '';
                obrasocial.value = '';
                numero_afiliado.value = '';              
                plan_obra_social.value = '';              
                fecha_nacimiento_dia.value = '';  
                fecha_nacimiento_mes.value = '';             
                fecha_nacimiento_anio.value = ''; 

                sexo_m.checked = false;
                sexo_f.checked = false;
                nombre_madre.value = "";
                nombre_padre.value = "";
                localidad.value = "";
                cantidad_hermanos.value = 0;                
              }                        
           }
      });    
    } 
  }

  function actualizarPaciente(){     
    var paciente_id = document.getElementById("paciente_id").value;   
    var medico_id = document.getElementById("medico_id").value;  
    var user_id = document.getElementById("user_id").value;
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    var dni = document.getElementById("dni").value;
    
    var telefono = document.getElementById("telefono").value;
    var domicilio = document.getElementById("domicilio").value;
    var mail = document.getElementById("mail").value;
    var obrasocial = document.getElementById("obrasocial").value; 
    var numero_afiliado = document.getElementById("numero_afiliado").value; 
    var plan = document.getElementById("plan_obra_social").value;    

    var hermanos = document.getElementById("cantidad_hermanos").value;
    var localidad = document.getElementById("localidad").value;
    var nombre_padre = document.getElementById("nombre_padre").value;
    var nombre_madre = document.getElementById("nombre_madre").value;
    var sexo = 'F';
    if(document.getElementById("sexo_m").checked){
      sexo = 'M';
    }      
    
    var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;     
    var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;     
    var fecha_nacimiento_anio = document.getElementById("fecha_nacimiento_anio").value;
    
    if((fecha_nacimiento_dia == null)||(fecha_nacimiento_dia.localeCompare('')==0)
      ||(fecha_nacimiento_mes == null)||(fecha_nacimiento_mes.localeCompare('')==0)
      ||(fecha_nacimiento_anio == null)||(fecha_nacimiento_anio.localeCompare('')==0)){
      var fecha_nacimiento = null;
    } else {
      var fecha_nacimiento = fecha_nacimiento_anio+"/"+fecha_nacimiento_mes+"/"+fecha_nacimiento_dia;  
    }
    
     $.ajax({
           type:'POST',
           dataType:'JSON',
           url:'/actualizar_datos_paciente',
           data:{paciente_id:paciente_id,medico_id:medico_id, user_id:user_id, dni :dni,nombre:nombre,apellido:apellido,fecha_nacimiento:fecha_nacimiento,telefono:telefono,mail:mail,obra_social:obrasocial,numero_afiliado:numero_afiliado,obra_social_plan:plan, domicilio:domicilio, hermanos:hermanos, localidad:localidad, nombre_padre:nombre_padre, nombre_madre:nombre_madre, sexo:sexo, _token: '{{csrf_token()}}'},
           success:function(data){              
                       
            if(data.paciente!=null){
              if(data.huboUnCambio == 1){
                mostrarSnackbar("Los datos han sido actualizados");
              }
              document.getElementById("paciente_id").value = '';     
              document.getElementById("nombre").value = data.paciente.nombre;     
              document.getElementById("apellido").value = data.paciente.apellido;
              document.getElementById("dni").value = data.paciente.dni; 
              document.getElementById("telefono").value = data.paciente.telefono;
              document.getElementById("domicilio").value = data.paciente.domicilio;
              document.getElementById("mail").value = data.paciente.mail;
              document.getElementById("obrasocial").value = data.paciente.obra_social;
              document.getElementById("numero_afiliado").value = data.paciente.numero_afiliado;
              document.getElementById("plan_obra_social").value = data.paciente.obra_social_plan;
              var arrayFechaNacimiento = data.paciente.fecha_nacimiento.split('-');
             if(arrayFechaNacimiento[0].localeCompare("1000")==0){
                fecha_nacimiento_anio.value = "";
                fecha_nacimiento_mes.value = "";
                fecha_nacimiento_dia.value = "";
              } else {
                fecha_nacimiento_anio.value = arrayFechaNacimiento[0];
                fecha_nacimiento_mes.value = arrayFechaNacimiento[1];
                fecha_nacimiento_dia.value = arrayFechaNacimiento[2];
              }
            } else {
              document.getElementById("nombre").value = "";     
              document.getElementById("apellido").value = "";
              document.getElementById("dni").value = "";
              document.getElementById("fecha_nacimiento_dia").value = "";
              document.getElementById("fecha_nacimiento_mes").value = "";
              document.getElementById("fecha_nacimiento_anio").value = ""; 
              document.getElementById("telefono").value = "";
              document.getElementById("domicilio").value = "";
              document.getElementById("mail").value = "";
              document.getElementById("obrasocial").value = "";
              document.getElementById("numero_afiliado").value = "";
              document.getElementById("plan_obra_social").value = "";
              document.getElementById("localidad").value = "";
              document.getElementById("nombre_padre").value = "";
              document.getElementById("nombre_madre").value = "";
              document.getElementById("cantidad_hermanos").value = "0"; 
              document.getElementById("sexo_m").checked = false;
              document.getElementById("sexo_f").checked = false;
            }
           }
        });
  }

  function validarPacienteExisteId(){
    var paciente_id = document.getElementById("paciente_id").value;     
    var user_id = document.getElementById("user_id").value;     
    var medico_id = document.getElementById("medico_id").value;  
    if(dni != 1){
      $.ajax({
         type:'POST',
         dataType:'JSON',
         url:'/consultar_paciente',
         data:{paciente_id :paciente_id, user_id:user_id, medico_id:medico_id, _token: '{{csrf_token()}}'},
         success:function(data){      
            var nombre = document.getElementById("nombre");
            var apellido = document.getElementById("apellido");
            var dni = document.getElementById("dni");
            var telefono = document.getElementById("telefono");
            var domicilio = document.getElementById("domicilio");
            var mail = document.getElementById("mail");
            var obrasocial = document.getElementById("obrasocial");          
            var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia");
            var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes");
            var fecha_nacimiento_anio = document.getElementById("fecha_nacimiento_anio");          
            var plan_obra_social = document.getElementById("plan_obra_social");
            var numero_afiliado = document.getElementById("numero_afiliado");    

            var localidad = document.getElementById("localidad");
            var sexo_m = document.getElementById("sexo_m");
            var sexo_f = document.getElementById("sexo_f"); 
            var cantidad_hermanos = document.getElementById("cantidad_hermanos"); 
            var nombre_madre = document.getElementById("nombre_madre");
            var nombre_padre = document.getElementById("nombre_padre");                 

             if(data.paciente != null) {              
                nombre.value = data.paciente.nombre;
                apellido.value = data.paciente.apellido;
                dni.value = data.paciente.dni;
                telefono.value = data.paciente.telefono;
                domicilio.value = data.paciente.domicilio;
                mail.value = data.paciente.mail;
                obrasocial.value = data.paciente.obra_social;              
                numero_afiliado.value = data.paciente.numero_afiliado;              
                plan_obra_social.value = data.paciente.obra_social_plan;

                localidad.value = data.paciente.localidad;
                nombre_padre.value = data.paciente.nombre_padre;
                nombre_madre.value = data.paciente.nombre_madre;
                cantidad_hermanos.value = data.paciente.cantidad_hermanos;
                if(data.paciente.sexo == "M")
                  sexo_m.checked = true;
                else
                  sexo_f.checked = true;
                if(data.paciente.fecha_nacimiento.localeCompare('')!=0){
                  var arrayFechaNacimiento = data.paciente.fecha_nacimiento.split('-');
                   if(arrayFechaNacimiento[0].localeCompare("1000")==0){
                    fecha_nacimiento_anio.value = "";
                    fecha_nacimiento_mes.value = "";
                    fecha_nacimiento_dia.value = "";
                   } else {
                    fecha_nacimiento_anio.value = arrayFechaNacimiento[0];
                    fecha_nacimiento_mes.value = arrayFechaNacimiento[1];
                    fecha_nacimiento_dia.value = arrayFechaNacimiento[2];
                  }
                } 
              } else{ 
                nombre.value = '';
                apellido.value = '';              
                telefono.value = '';
                domicilio.value = '';
                mail.value = '';
                obrasocial.value = '';
                numero_afiliado.value = '';              
                plan_obra_social.value = '';              
                fecha_nacimiento_dia.value = '';  
                fecha_nacimiento_mes.value = '';             
                fecha_nacimiento_anio.value = ''; 

                sexo_m.checked = false;
                sexo_f.checked = false;
                nombre_madre.value = "";
                nombre_padre.value = "";
                localidad.value = "";
                cantidad_hermanos.value = 0;                
              }                        
           }
      });    
    }  
  }

  window.onload=function() {  
    var dni_existe = document.getElementById("dni").value;
    var _id_existe = document.getElementById("paciente_id");
    mostrarPanelPaciente(true);
    mostrarPanelHistoriaClinica(false);
    if(_id_existe != null){
      var id_existe = document.getElementById("paciente_id").value;
      if(dni_existe.localeCompare("")!=0)      
        validarPacienteExiste();
      if(id_existe != null)
        validarPacienteExisteId();
    }
  }

 </script>