<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <link rel="shortcut icon" type="image/x-icon" href="/img/iconos/icono_hcpediatrica.png" />
  <meta name="author" content="Rodrigo Banegas">

  <title>Historia Clinica Digital</title>

  <!-- Custom fonts for this template-->
  {{ Html::style('vendor/fontawesome-free/css/all.min.css') }}
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Para que funcione el datatable-->
  {{ Html::style('datatable/jquery.dataTables.min.css') }}
  
  <!-- Custom styles for this template-->
  {{ Html::style('css/sb-admin-2.min.css') }}
  {{ Html::style('css/rodri_style6.css') }}
  <link rel="stylesheet" media="(max-width: 360px)" href="css/rodri_style6.css">

  <!-- Para que funcione ajax-->
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{asset('js/jquery.min.js')}}"> </script>
  
  <!-- Para que funcione ajax fin-->
    @include('modal.modal_licencia')
</head>

<body id="page-top">

  <input hidden id="medico_id" name="medico_id" value="{{ Auth::user()->id }}"/>  
  <input hidden id="paciente_seleccionado_id" name="paciente_seleccionado_id" value="0"/>  
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion fondoNav" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/licencia_expirada">
        <div class="sidebar-brand-icon rotate-n-0">
          <img class="img-profile rounded-circle img_icon_size" src="/img/iconos/icono_hcpediatrica.png">
        </div>
        <div class="sidebar-brand-text mx-3"></div>
      </a>
    </ul>
      <!-- Divider -->
      
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <h1 id="static_header" class="title_header_cel">@yield('title_header','Licencia expirada')</h1>            
          <h1 hidden id="dinamic_header" class="h3 mb-0 text-gray-800"></h1>            

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
    
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }} </span>
                <img class="img-profile rounded-circle" src="/img/medicos/{{ Auth::user()->foto }}">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <!--  <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Configuración
                </a> 
                <div class="dropdown-divider"></div> -->
                <a onclick="mostrarLicenciaExpira()" type="button" class="dropdown-item">
                  <i class="fas fa-file-signature fa-sm fa-fw mr-2 text-gray-400"></i>
                  Licencia
                </a>
                
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Cerrar Sesión
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          
          <h4>Pongase en contacto con el administrador para renovar su licencia.</h4>
          <h4>Muchas Gracias</h4>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Historia Clinica Digital - Rodrigo Banegas</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Esta Seguro?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Click en "Cerrar Sesión" para dejar el sitio.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" type="button" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
            </a>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins 
  <script src="vendor/chart.js/Chart.min.js"></script>

   Page level custom scripts 
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
 -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="{{asset('datatable/jquery.dataTables.min.js')}}"></script>
</body>

<script type="text/javascript">
/*  function mostrarPanelPaciente(value){
    document.getElementById("paciente_opciones").hidden = value;
  }

  function mostrarPanelHistoriaClinica(value){
    document.getElementById("historia_clinica_opciones").hidden = value;
  }*/
</script>

</html>
