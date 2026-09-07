<div class="container">
<input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}"/> 

@if($cargar_foto_check == 1)
<div class="custom-control custom-checkbox">
  <input type="checkbox" id="consulta_con_foto" name="consulta_foto" class="custom-control-input">
  <label class="custom-control-label" for="consulta_con_foto">Cargar foto ficha</label>
</div>
@endif
  <div class="table-responsive  margin_top_5px" style="height:700px; overflow-y: scroll;">
  <table class="table table-striped" id="laravel_datatable"> 
               <thead class="fondoNav text-white">
                  <tr>
                    <th class="editText letra_size_1rem">Apellido</th>
                    <th class="editText letra_size_1rem">Nombre</th>
                    <th class="editText letra_size_1rem">DNI</th>
                    <th class="editText letra_size_1rem">Telefono</th>                              
                    <th class="editText letra_size_1rem">Consulta</th>                                       
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
           ajax: "{{ url('nueva_consulta_paciente_listado_buscar') }}",
           columns: [
                    { data: 'apellido', name: 'apellido', class:'letra_size_1rem' },
                    { data: 'nombre', name: 'nombre', class:'letra_size_1rem' },
                    { data: 'dni', name: 'dni' , class: 'letra_size_1rem'},
                    { data: 'telefono', name: 'telefono', class:'letra_size_1rem' },                                        
                    { data: 'action', name: 'action', orderable: false, searchable: false}                                       
                 ]
        });
     });

  </script>