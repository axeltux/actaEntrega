@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE LOTES DEL OFICIO: {{ $oficio }} || CERYS: {{ $nom }}</b></div>
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
                        <table id="tabla-lotes" width="100%" class="table table-bordered table-striped table-sm" data-order='[[ 0, "asc" ]]' data-page-length="5">
                            <thead>
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th style="text-align: center">#Empleado</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Unidad</th>
                                    <th style="text-align: center">Cerys</th>
                                    <th style="text-align: center">Estado</th>
                                    <th style="text-align: center">Lote</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lotes as $element)
                                    <tr>
                                        <td> {{ $contador += 1 }} </td>
                                        <td> {{ $element->NumeroEmpleado }} </td>
                                        <td> {{ ToolsPHP::NomEmpleados($element->NumeroEmpleado) }} </td>
                                        <td> {{ $element->UnidadAdmin }} </td>
                                        <td> {{ $element->Cerys }} </td>
                                        <td>
                                            @if($element->Acepta == 1)
                                                <b style="color:blue;">Aceptado</b>
                                            @else
                                                <b style="color:red;">Rechazado</b>
                                            @endif
                                        </td>
                                        <td> Lote-{{ $element->Lote }} </td>
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