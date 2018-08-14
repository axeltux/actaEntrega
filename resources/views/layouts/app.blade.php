<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Entrega Oficios') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('alertify.js/themes/alertify.core.css') }}" rel="stylesheet" />
        <!-- <link href="{{ asset('alertify.js/themes/alertify.default.css') }}" rel="stylesheet" id="toggleCSS"/> -->
        <link href="{{ asset('alertify.js/themes/alertify.bootstrap.css') }}" rel="stylesheet" />        
        <style type="text/css">
            .active{
                text-decoration: none;
                color: green;
            }
            .error{
                color: red;
                font-size: 12px;
            }
            .enlaceboton {    
                font-family: verdana, arial, sans-serif; 
                font-size: 10pt; 
                font-weight: bold; 
                padding: 4px; 
                background-color: #ffffcc; 
                color: #666666; 
                text-decoration: none; 
            } 
            .enlaceboton:link, 
            .enlaceboton:visited { 
               border-top: 1px solid #cccccc; 
               border-bottom: 2px solid #666666; 
               border-left: 1px solid #cccccc; 
               border-right: 2px solid #666666;
               text-decoration: none;
            } 
            .enlaceboton:hover { 
                border-bottom: 1px solid #cccccc; 
                border-top: 2px solid #666666; 
                border-right: 1px solid #cccccc; 
                border-left: 2px solid #666666;
                text-decoration: none;
            }
            .alertify-log-custom {
                background: blue;
            }
            .enlaceboton2 {    
                font-family: verdana, arial, sans-serif; 
                font-size: 10pt; 
                padding: 4px; 
                background-color: #5882FA; 
                color: #ffffff; 
                text-decoration: none; 
            } 
            .enlaceboton2:link, 
            .enlaceboton2:visited { 
               border-top: 1px solid #cccccc; 
               border-bottom: 2px solid #666666; 
               border-left: 1px solid #cccccc; 
               border-right: 2px solid #666666;
               color: #ffffff;
               text-decoration: none;
            } 
            .enlaceboton2:hover { 
                border-bottom: 1px solid #cccccc; 
                border-top: 2px solid #666666; 
                border-right: 1px solid #cccccc; 
                border-left: 2px solid #666666;
                color: #ffffff;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">

                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Branding Image -->
                        {{--<a class="navbar-brand" href="{{ url('/') }}">--}}
                        <a class="navbar-brand" href="">
                            {{--{{ config('app.name', 'Laravel') }}--}}Entrega eFirma
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            &nbsp;
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                                {{--<li><a href="{{ route('register') }}">Registrar</a></li>--}}
                            @else
                                @if(Auth::user()->username === 'Admin')
                                    <li><a href="{{ route('register') }}">Registrar</a></li>
                                    <li><a href="{{ route('listusers') }}">Usuarios</a></li>
                                @endif
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                Salir
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
        <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('alertify.js/lib/alertify.min.js')}}"></script>
        <script type="text/javascript" src="https://ptscdev.siat.sat.gob.mx/PTSC/fwidget/resources/js/m2.firmado.sat.dev.js"></script>
        <!--<script type="text/javascript" src="https://wwwuat.siat.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        <script type="text/javascript" src="https://aplicaciones.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <script >
            $(document).ready(function() {
                $('#tabla-formateada').DataTable({
                    stateSave: true,
                    "language": {
                        "url": '{{ asset('js/Spanish.json') }}'
                    },
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "pagingType": "full_numbers"
                });

                $.ajaxSetup({
                    headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
                });
            });

            alertify.set({ labels: {
                ok     : "Aceptar",
                cancel : "Cancelar"
            } });

            $('#btn-cerys').click(function(event) {
                var cerys = $('select[id=cerys]').val();
                if(cerys===''){
                    alertify.alert("<center><h3>Seleccione un Cerys.</h3></center><br>");
                    return false;
                }
            });
        </script>       
    </body>
</html>