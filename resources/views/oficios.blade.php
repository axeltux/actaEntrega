@extends('layouts.app')

@section('content')

@routes

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE OFICIOS Cerys: </b> <strong>{{ $cerys }}</strong></div>
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
                        <table id="tabla-formateada" width="100%" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length="10">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>#Oficio</th>
                                    <th>#Lote</th>
                                    <th>Creado</th>
                                    <th>Firmado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oficios as $element)
                                    <tr>
                                        <td>{{ $element->id }}</td>
                                        <td>{{ $element->oficio }}</td>
                                        <td><b>{{ $element->lotes }}</b></td>
                                        <td>{{ $element->creadoEl }}</td>
                                        <td>@if($element->firmado)
                                                <b>Si</b>
                                            @else
                                                <b>No</b>
                                            @endif
                                        </td>
                                        <td>
                                            <form style="display: inline" action="{{ route('listaLotes', [$element->lotes, $element->oficio, $element->cerys]) }}">
                                                <button type="submit" class="btn btn-primary" title="Ver oficio">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </form>
                                            @if($element->status)
                                                @if($element->firmado)
                                                    <form style="display: inline" action="{{ route('pdf', [$element->oficio, 1]) }}" target="_blank">
                                                        <button type="submit" class="btn btn-default" title="Ver PDF">
                                                            <i class="far fa-file-pdf"></i>
                                                        </button>
                                                    </form>
                                                    <form style="display: inline" action="{{ route('pdf', [$element->oficio, 2]) }}">
                                                        <button type="submit" class="btn btn-warning" title="Descargar documento">
                                                            <i class="fa fa-download"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    @if(Auth::user()->username !== 'Admin')
                                                        <form style="display: inline" action="{{ route('firma', $element->oficio) }}">
                                                            <button type="submit" class="btn btn-success" title="Firmar oficio">
                                                                <i class="fa fa-file-signature"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            @else
                                                @if(Auth::user()->username !== 'Admin')
                                                    <form style="display: inline">
                                                        <a href="#" OnClick="aceptar({{ $element->id }}, '{{ $element->oficio }}', '{{ $cerys }}');" class="btn btn-success" title="Aceptar oficio">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    </form>
                                                    <form style="display: inline">
                                                            <a href="#" OnClick="rechazar({{ $element->id }});"class="btn btn-danger" title="Rechazar oficio" data-toggle="modal" data-target="#rechazar">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>
@endsection