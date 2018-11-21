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
        <link href="{{ asset('fonts/fontawesome/css/all.css') }}" rel="stylesheet" />
        <link href="{{ asset('alertify.js/themes/alertify.core.css') }}" rel="stylesheet" />
        <link href="{{ asset('js/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet" />
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
            /*textarea:invalid {
                background:red;
            }*/
            .caja_inline {
                display: inline-block;
                width: 360px;
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
        @include('rechazar')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
        <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('alertify.js/lib/alertify.min.js')}}"></script>
        <script type="text/javascript" src="https://ptscdev.siat.sat.gob.mx/PTSC/fwidget/resources/js/m2.firmado.sat.dev.js"></script>
        <!--<script type="text/javascript" src="https://wwwuat.siat.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        <script type="text/javascript" src="https://aplicaciones.sat.gob.mx/PTSC/fwidget/restServices/m.firmado.sat.general.js"></script>
        -->
        <script >
            //Carga Datatable
            $(document).ready(function() {
                $('#tabla-formateada').DataTable({
                    stateSave: true,
                    "language": {
                        "url": '{{ asset('js/Spanish.json') }}'
                    },
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
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

            //Valida si se selecciono un cerys para ver los oficios
            $('#btn-cerys').click(function(event) {
                let cerys = $('select[id=cerys]').val();
                if(cerys===''){
                    alertify.alert("<center><h3>Seleccione un Cerys.</h3></center><br>");
                    return false;
                }
            });

            //Procesa la solicitud de aceptación de oficio en el Cerys
            let aceptar = function(id, of, cer){
                let token   = $('#token').val();
                let url     = route('statusOficio');
                let param   = {
                                '_token': token,
                                'id': id,
                                'tipo': 1,
                                'comment': '',
                                'oficio': of
                            };
                alertify.confirm("<center><h3>¿Confirma la aceptación del oficio: <b>"+ of +"</b> del Cerys: <b>" + cer + "</b>?</h3></center><br>", function (e) {
                    if (e) {
                        $.ajax({
                            url:        url,
                            data:       param,
                            type:       'post',
                            dataType:   'json',
                            success: function(result){
                                if(result.valor == "OK"){
                                    location.reload(true);
                                }else if(result.valor == "ER"){
                                    alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                                    return false;
                                }else{
                                    alertify.alert("<center><h3>Ocurrio un error al aceptar el oficio.</h3></center><br>");
                                    return false;
                                }
                            }
                        });
                    } else {
                        alertify.error("<h3>Se cancelo la operación</h3>");
                        return false;
                    }
                });
            };

            //Envia valores a ventana modal
            let rechazar = function(id){
                $('#idMotivo').val(id);
                $('#motivo').val('');
            };

            //Funcion para cerrar modal, recibe nombre del modal
            let cerrarModal = function(nameModal){
                let nombre = '#' + nameModal;
                //ocultamos el modal
                $(nombre).modal('hide');
                //eliminamos la clase del body para poder hacer scroll
                $('body').removeClass('modal-open');
                //eliminamos el backdrop del modal
                $('.modal-backdrop').remove();
            };

            //Envia mensaje de cancelacion de operacion
            $('#btn-cancela').click(function(event) {
                alertify.error("<h3>Se cancelo la operación</h3>");
            });

            //Evento click del boton aceptar del modal de rechazar oficio
            $('#btn-acepta').click(function(event) {
                let motivo = $('#motivo').val();
                let id = $('#idMotivo').val();
                if(motivo === '' || motivo === null){
                    $('#motivo').focus();
                    $('#motivo').effect('highlight', {color:'#ff0000'}, 6000);
                    alertify.error("<h4>El motivo no puede ser nulo</h4>");
                    return false;
                }else if(motivo.length < 10){
                    $('#motivo').focus();
                    $('#motivo').effect('highlight', {color:'#ff0000'}, 6000);
                    alertify.error("<h4>La descripción del motivo es muy corta</h4>");
                    return false;
                }else{
                    let token   = $('#token').val();
                    let url     = route('statusOficio');
                    let param   = {
                                    '_token': token,
                                    'id': id,
                                    'tipo': 2,
                                    'comment': motivo,
                                };
                    alertify.confirm("<center><h3>¿Confirma el rechazo del oficio?</h3></center><br>", function (e) {
                        if (e) {
                            $.ajax({
                                url:        url,
                                data:       param,
                                type:       'post',
                                dataType:   'json',
                                beforeSend: function () {
                                    cerrarModal('rechazar');
                                },
                                success: function(result){
                                    if(result.valor == "OK"){
                                        location.reload(true);
                                    }else if(result.valor == "ER"){
                                        alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                                        return false;
                                    }else{
                                        alertify.alert("<center><h3>Ocurrio un error al rechazar el oficio.</h3></center><br>");
                                        return false;
                                    }
                                }
                            });
                        } else {
                            cerrarModal('rechazar');
                            alertify.error("<h3>Se cancelo la operación</h3>");
                            return false;
                        }
                    });
                }
            });
        </script>
    </body>
</html>
