
@extends('plantillas/plantilla_medico')

@section('title_header', 'Nombre Paciente')

@section('contenedor')

<div class="container">
<input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}"/> 
<input type="hidden" id="paciente_id" name="paciente_id" value="{{ $paciente_id }}"/> 

  <div class="table-responsive" style="height:700px; overflow-y: scroll;">
  <table class="table table-striped" id="laravel_datatable"> 
               <thead class="fondoNav text-white">
                  <tr>
                    <th class="editText letra_size_1rem">Id</th>  
                    <th class="editText letra_size_1rem">Fecha</th>                            
                    <th class="editText letra_size_1rem">Ver</th>                                       
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   <script>
   
   $(document).ready( function () {
    var paciente = document.getElementById("paciente_id").value;

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
           ajax: "{{ url('paciente_consultas_listado') }}",
           order: [ [0, 'desc'] ],
           columns: [
                    { data: 'consulta_id', name: 'consulta_id', class:'letra_size_1rem' },
                    { data: 'fecha', name: 'fecha', class:'letra_size_1rem' },                                                      
                    { data: 'action', name: 'action', orderable: false, searchable: false}                                       
                 ]

        });
     });

  function navegarConsulta(consulta_id){  
     $.ajax({
             type:'POST',
             dataType:'JSON',
             url:'/guardar_consulta_medico_info',
             data:{consulta_id:consulta_id, _token: '{{csrf_token()}}'},
             success:function(data) { 
              if(data.response == 1)
                  window.location.href = "/navegar_consulta_seleccionada";             
              else
                alert("se produjo un error");
              }
          });     
                        
   }

  </script>

@endsection