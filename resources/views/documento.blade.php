@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>ACUSE DE ENTREGA FIRMADO</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif                    
                    <p><h3>Se ha generado el acuse de recibo del oficio: <b>{{ $oficio }}</b> del Cerys: <b>{{ $cerys }}</b> firmado por: <b>{{ $oficios[0]->firmadoPor }}</b> con RFC: <b>{{ $oficios[0]->RFC }}</b>.</h3></p>
                    <p><h4>A continuacion se muestran las opciones para poder visualizar o descargar el documento en su equipo de computo:</h4></p>
                    <br><br>
                    <form style="display: inline" action="{{ route('pdf', [$oficio, 1]) }}" target="_blank">
                        <button type="submit" class="btn btn-primary">Ver PDF</button>
                    </form>
                    <form style="display: inline" action="{{ route('pdf', [$oficio, 2]) }}">
                        <button type="submit" class="btn btn-danger">Descargar</button>
                    </form>
                </div>
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar</a>
        </div>
    </div>
</div>
@endsection
