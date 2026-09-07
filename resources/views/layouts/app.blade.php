<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title >{{'Historia Clinica Digital'}}</title>  
    <!--<link rel="shortcut icon" type="image/x-icon" href="/images/iconos/turnosonlinebb_icon.png" /> -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" type="image/x-icon" href="/img/iconos/icono_hcpediatrica.png" />


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
     <link href="{{ asset('css/rodri_style6.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Para mediaquery debo agregar estos dos. Mediaquery es para que se ve bien en el telefono -->
    <link rel="stylesheet" media="(max-width: 360px)" href="css/rodri_style6.css">     
  
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fontNav fondoNav">
            <div class="container">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/medico_home') }}">
                    <div class="sidebar-brand-icon">
                      <img class="img-profile rounded-circle img_icon_size_login" src="/img/iconos/icono_hcpediatrica.png">                      
                    </div>                    
                </a>
                <a class="textheader navbar-brand text-white margin_left_login_10px" href="{{ url('/medico_home') }}">
                    {{'Historia Clinica Digital'}}
                </a>
                <button class="navbar-toggler buttonMenuSizeMarco" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon buttonMenuSize"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a hidden class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}" hidden="false">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(Auth::user() == null)
                @yield('content')
            @else
            <div class="contenedor3">
                <div class="contenido3">
                <h1 >Ya te encuentras logueado!</h1>
                <a class="textheader navbar-brand margin_left_login_10px" href="{{ url('/medico_home') }}">
                    Click aquí para continuar
                </a>
                </div>
            </div>
            @endif
        </main>
    </div>
</body>
</html>
