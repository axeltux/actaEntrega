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
        <link href="{{ asset('DataTables/DataTables-1.10.17/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <link href="{{ asset('fonts/fontawesome/css/all.css') }}" rel="stylesheet" />
        <link href="{{ asset('alertify.js/themes/alertify.core.css') }}" rel="stylesheet" />
        <link href="{{ asset('js/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet" />
        <link href="{{ asset('alertify.js/themes/alertify.bootstrap.css') }}" rel="stylesheet" />
        <style>
            .CentraColumna {
                text-align: center;
            }
            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
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
                        <div class="caja_inline">
                            <a class="navbar-brand" href="{{ url('/home') }}">
                                {{--Entrega eFirma--}}
                                <span>
                                    <img src="{{URL::to('/')}}/logo_sat.png" height="30" width="35"/>
                                </span>
                                <span>Entrega eFirma</span>
                            </a>
                        </div>
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
        </div>

        @yield('content')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
        <!-- DataTables-->
        <script src="{{ asset('DataTables/DataTables-1.10.17/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('DataTables/DataTables-1.10.17/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('alertify.js/lib/alertify.min.js')}}"></script>
        <script type="text/javascript" src="https://ptscdev.siat.sat.gob.mx/PTSC/fwidget/resources/js/m2.firmado.sat.dev.js"></script>
        <!--<script type="text/javascript" src="https://wwwuat.siat.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        <script type="text/javascript" src="https://aplicaciones.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        -->

        @extends('layouts.scripts')

    </body>
</html>
