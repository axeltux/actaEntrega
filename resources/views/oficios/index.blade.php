@extends('layouts.app')

@section('content')

@routes

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE OFICIOS CERYS: </b> <strong>{{ $cerys }}</strong></div>
                {{ csrf_field() }}
                <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(count($oficios)<1)
                        <h2>No existen oficios para este Cerys.</h2>
                    @else
                        <table id="tabla-oficios" width="100%" class="table table-bordered table-striped table-sm" data-order='[[ 1, "desc" ]]' data-page-length="5">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">ID</th>
                                    <th style="text-align: center;">#Oficio</th>
                                    <th style="text-align: center;">Cerys</th>
                                    <th style="text-align: center;">#Lote</th>
                                    <th style="text-align: center;">Creado</th>
                                    <th style="text-align: center;">Estado del Oficio</th>
                                    <th style="text-align: center;">Firmado</th>
                                    <th>usuario</th>
                                    <th>estadoOficio</th>
                                    <th>firmaOficio</th>
                                    <th>numCerys</th>
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    @endif
                </div>
                @include('oficios.rechazar')
            </div>
        </div>
    </div>
</div>

@endsection