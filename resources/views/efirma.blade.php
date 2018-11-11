@extends('layouts.app')

@section('content')

@routes

<script>
    var timer = setInterval(function() {
                console.log('No se ha cargado el Widget...');
                if (document.getElementById('btnEnviarForm') !== null) {
                    console.log('Se cargó el Widget de Firmado');
                    //Añadirle un evento
                    document.getElementById('btnEnviarForm').onclick = btnEnviarFIELOnClick;
                    clearInterval(timer);
                }
            }, 200);

    function btnEnviarFIELOnClick() {
        try {
            var hoy = new Date();
            var fecha = hoy.getFullYear() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getDate();
            var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
            var token = $('#token').val();
            var rfcSession = '{{ Auth::user()->username }}';
            var cadenaOriginalEntrada = rfcSession+'|'+token+'|'+fecha+'|'+hora;
            //Método que genera el firmado
            generaFirmaIndividual([{cadenaOriginal:cadenaOriginalEntrada}],
                {validarRFCSession:rfcSession},
                    function (error, resultado) {
                        if (error && error!==0) {
                            console.log("Error", error);
                            // Mostrar error al usuario.
                            alert(catalogoErrores[error].msg_vista);
                        } else {
                            console.log("Success");
                            //Utilizar resultados
                            console.log(resultado[0].firmaDigital);
                            console.log(resultado[0].cadenaOriginalGenerada);
                            //Llamada a la pagina que buscara los acuses para envio
                            var firma = resultado[0].firmaDigital;
                            var sello = resultado[0].cadenaOriginalGenerada;
                            var nombre = '{{ Auth::user()->name }}';
                            var oficio = '{{ $oficio }}';
                            var firmado = '{{ $firmado }}';
                            var cerys = '{{ $cerys }}';
                            var url = route('sello');
                            var param = {
                                          '_token': token,
                                          'nombre': nombre,
                                          'firma':  firma,
                                          'sello':  sello,
                                          'oficio': oficio,
                                          'rfc':    rfcSession,
                                          'fecha':  fecha,
                                          'hora':   hora
                                        };
                            if(firmado == 1){
                                alertify.alert("<center><h3>El documento ya fue firmado.</h3></center><br>");
                                return false;
                            }
                            alertify.confirm("<h3>¿Confirma la generación del sello y firma digital del oficio: <b>"+ oficio +"</b> del Cerys: <b>" + cerys + "</b>?</h3><br>", function (e) {
                                if (e) {
                                    $.ajax({
                                        url:        url,
                                        data:       param,
                                        type:       'post',
                                        dataType:   'json',
                                        success: function(result){
                                            if(result.valor == "OK"){
                                                window.location = route("documento", result.oficio);
                                            }else if(result.valor == "ER"){
                                                alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                                                return false;
                                            }else{
                                                alertify.alert("<center><h3>Ocurrio un error al firmar el documento.</h3></center><br>");
                                                return false;
                                            }
                                        }
                                    });
                                } else {
                                    alertify.error("<h3>Se cancelo la operación</h3>");
                                    return false;
                                }
                            });
                        }
                    }
            );
        } catch (error) {
            console.log("Error de widget");
            alertify.error("<center><h3>Error de widget: "+ error +"</h3></center>");
            return false;
        }
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Firma digital SAT</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
					<div id="firmado-widget-container"></div>
                </div>
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar</a>
        </div>
    </div>
</div>
@endsection