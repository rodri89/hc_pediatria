<div class="container">
<input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}"/> 

  <div class="table-responsive" style="height:700px; overflow-y: scroll;">
  <table class="table table-striped" id="laravel_datatable"> 
               <thead class="fondoNav text-white">
                  <tr>
                    <th class="editText">Apellido</th>
                    <th class="editText">Nombre</th>
                    <th class="editText">DNI</th>
                    <th class="editText">Telefono</th>                      
                    <th class="editText">Obra Social</th>
                    <th class="editText">N°Afiliado</th>
                    <th class="editText">Plan</th>
                    <th class="editText">Acción</th>                                       
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   <script>
   
   $(document).ready( function () {
    $('#laravel_datatable').DataTable({
           language: {
              "decimal": "",
              "emptyTable": "No hay información",
              "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
              "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
              "infoFiltered": "(Filtrado de _MAX_ total entradas)",
              "infoPostFix": "",
              "thousands": ",",
              "lengthMenu": "Mostrar _MENU_ Entradas",
              "loadingRecords": "Cargando...",
              "processing": "Procesando...",
              "search": "Buscar:",
              "zeroRecords": "Sin resultados encontrados",
              "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"
              },
           },
           processing: false,
           serverSide: false,           
           ajax: "{{ url('paciente_listado_buscar') }}",
           columns: [
                    { data: 'apellido', name: 'apellido' },
                    { data: 'nombre', name: 'nombre' },
                    { data: 'dni', name: 'dni' },
                    { data: 'telefono', name: 'telefono' },                    
                    { data: 'obra_social', name: 'obra_social' },
                    { data: 'numero_afiliado', name: 'numero_afiliado' },
                    { data: 'obra_social_plan', name: 'obra_social_plan' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}                                       
                 ]
        });
     });
    
    function optionModal(dni){     
      var user_id = document.getElementById("user_id").value; 

      $.ajax({
        type:'POST',
        dataType:'JSON',
        url:'/consultar_paciente',
        data:{dni_paciente :dni, user_id:user_id, _token: '{{csrf_token()}}'},
        success:function(data){      
          var paciente = data.paciente.apellido+", "+data.paciente.nombre;
          $('#pacienteSeleccionado').val(paciente);  
          $('#dniPacienteSeleccionado').val(data.paciente.dni);
          $('#pacienteAct').val(data.paciente.dni);
          $('#pacienteActId').val(data.paciente.id);
          $('#pacienteAsg').val(data.paciente.dni);
          $('#pacienteAsgId').val(data.paciente.id);          
          $('#optionsModal').modal();  
        }
      });
    }

  </script>

 

<div class="modal fade" id="optionsModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">        
        <h4 class="modal-title"         
        id="modalTitleMensaje">Opciones:</h4>
      </div>      
       <div class="modal-body">
           <h6><b>Paciente: </b><input class="sinBackground" type="text" id="pacienteSeleccionado"></input></h6>
           <h6><b>DNI: </b><input class="sinBackground" type="text" id="dniPacienteSeleccionado"></input></h6><br>
          <div class="row contenedor3">
            @if(Auth::user()->usuario_tipo == 2)
            <form class="contenido3" method="POST" action="{{route('medicoactualizarpacientebuscar') }}">
            @else
            <form class="contenido3" method="POST" action="{{route('secretariaactualizarpacientebuscar') }}">              
            @endif
              @csrf
              <input type="hidden" id="medico_id" name="medico_id" value="{{$medico_id}} "/>
              <input type="hidden" id="consultorioAct" name="user_id" value="{{ Auth::user()->id }}"/>
              <input type="hidden" id="pacienteAct" name="paciente_dni"/>
              <input type="hidden" id="pacienteActId" name="paciente_id"/>    
              <button class="btn btn-primary-outline">
                <div class="circulo img_turno_apl">
                  <h1 class="turno_text_size_apl">Actualizar</h1>
                </div>
              </button>
            </form>
            <form class="contenido3" method="POST" action="{{route('consultarpaciente') }}">
             @csrf
              <input type="hidden" id="pacienteAsg" name="paciente_dni"/>                  
              <input type="hidden" id="pacienteAsgId" name="paciente_id"/>   
              <button class="btn btn-primary-outline">
                <div class="circulo img_turno_apl">
                  <h1 class="turno_text_size_apl">Historia Clinica</h1>
                </div>
              </button>
            </form>
          </div>        
       </div>
      <div class="modal-footer">
        <button class="rodri_button_volver" data-dismiss="modal">Vovler</button>            
      </div>
    </div>
  </div>
</div>