@extends('layouts.app')

@section('content')

@routes

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE USUARIOS</b></div>
                {{ csrf_field() }}
                <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table id="tabla-users" width="100%" class="table table-bordered table-striped table-sm" data-order='[[ 0, "desc" ]]' data-page-length="5">
                        <thead>
                            <tr>
                                <th style="text-align: center;">ID</th>
                                <th style="text-align: center;">RFC</th>
                                <th style="text-align: center;">Nombre</th>
                                <th style="text-align: center;">E-Mail</th>
                                <th style="text-align: center;">Cerys</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>

@endsection