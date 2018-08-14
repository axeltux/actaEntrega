@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE LOTES DEL OFICIO: {{ $oficio }} || Cerys: {{ $nom }}</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(!$lotes)
                        <h2>No existen Lotes para este Oficio.</h2>
                    @else
                        <table id="tabla-formateada" width="100%" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length="10">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>#Empleado</th>
                                    <th>Nombre</th>
                                    <th>Unidad</th>
                                    <th>Cerys</th>
                                    <th>Lote</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lotes as $element)
                                    <tr>
                                        <td> {{ $contador += 1 }} </td>
                                        <td> {{ $element->NumeroEmpleado }} </td>
                                        <td> {{ ToolsPHP::NomEmpleados($element->NumeroEmpleado) }} </td>
                                        <td> {{ $element->UniAdmin }} </td>
                                        <td> {{ $element->Cerys }} </td>
                                        <td> Lote-{{ $element->Lote }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @if($firmado == 0)
                    <form style="display: inline" action="{{ route('firma', $oficio) }}">
                        <button type="submit" class="btn btn-success">Firmar documento</button>
                    </form><br><br>
                @else
                    <form style="display: inline" action="{{ route('pdf', [$oficio, 2]) }}">
                        <button type="submit" class="btn btn-danger">Descargar</button>
                    </form><br><br>
                @endif
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar</a>
        </div>
    </div>
</div>
@endsection