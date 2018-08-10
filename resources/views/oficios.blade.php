@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE OFICIOS</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(!$oficios)
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
                                                <button type="submit" class="btn btn-primary">Ver</button>
                                            </form>
                                            @if($element->firmado)
                                                <form style="display: inline" action="{{ route('pdf', [$element->oficio, 1]) }}" target="_blank">
                                                    <button type="submit" class="btn btn-warning">PDF</button>
                                                </form>
                                            @else
                                                <form style="display: inline" action="{{ route('firma', $element->oficio) }}">
                                                    <button type="submit" class="btn btn-success">Firmar</button>
                                                </form>
                                            @endif
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>                
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar</a>
        </div>
    </div>
</div>
@endsection