@extends('layouts.app')

@section('content')

@routes

<script>
    let timer = setInterval(function() {
                if (document.getElementById('btn-send') !== null) {
                    console.log('Se cargó el Widget de Firmado');
                    //Añadirle un evento
                    document.getElementById('btn-send').onclick = btnEnviarFIELOnClick;
                    clearInterval(timer);
                }
            }, 200);

    function btnEnviarFIELOnClick() {
        let hoy = new Date();
        let fecha = hoy.getFullYear() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getDate();
        let hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
        let token = $('#token').val();
        let rfcSession = '{{ Auth::user()->username }}';
        let cadenaOriginalEntrada = rfcSession +'|'+ token +'|'+ fecha +'|'+ hora;
        let firma = '11111111AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXAAAAAAAAAAAZZZZZZZZZZ==';
        let sello = '||'+ rfcSession +'|20001000000300007558|1529087682109|'+ cadenaOriginalEntrada +'||';
        let nombre = '{{ Auth::user()->name }}';
        let oficio = '{{ $oficio }}';
        let firmado = '{{ $firmado }}';
        let cerys = '{{ $cerys }}';
        let url = route('sello');
        let param = {
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
        alertify.confirm("<center><h3>¿Confirma la generación del sello y firma digital del oficio: <b>"+ oficio +"</b> del Cerys: <b>" + cerys + "</b>?</h3></center><br>", function (e) {
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
            }
        });
    }
</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Firma digital SAT</b></div>
                {{ csrf_field() }}
                <center>
                    <div><h3>e.firma</h3></div>
                </center>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="cer" class="col-md-4 control-label">Certificado (cer)</label>
                        <div>
                            <div class="col-md-6">
                                <input id="cer" type="text" class="form-control" name="cer"  value="archivo.cer" required autofocus>
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-primary" id="buscar1">Buscar</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="llave" class="col-md-4 control-label">Clave privada (key)</label>
                        <div>
                            <div class="col-md-6">
                                <input id="llave" type="text" class="form-control" name="llave" value="archivo.key" required autofocus>
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-primary" id="buscar2">Buscar</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">Contraseña de clave privada</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" value="123456" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rfc" class="col-md-4 control-label">RFC</label>
                        <div class="col-md-6">
                            <input id="rfc" type="text" class="form-control" name="rfc" value="{{ Auth::user()->username }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary" id="btn-send">
                                Enviar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>
@endsection
