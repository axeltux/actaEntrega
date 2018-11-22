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
                        <table id="tabla-formateada" width="100%" class="display" data-order='[[ 0, "asc" ]]' data-page-length="5">
                            <thead>
                                <tr>
                                    <th><center>ID</center></th>
                                    <th><center>#Empleado</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Unidad</center></th>
                                    <th><center>Cerys</center></th>
                                    <th><center>Lote</center></th>
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
                                        <td> Lote-{{ $element->Lote }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @if($firmado == 0 && $aceptado == 1)
                    @if(Auth::user()->username !== 'Admin')
                        <form style="display: inline" action="{{ route('firma', $oficio) }}">
                            <button type="submit" class="btn btn-success" title="Firmar oficio">
                                <i class="fa fa-file-signature"> Firmar oficio</i>
                            </button>
                        </form><br><br>
                    @endif
                @elseif ($firmado == 1 && $aceptado == 1)
                    <form style="display: inline" action="{{ route('pdf', [$oficio, 2]) }}">
                        <button type="submit" class="btn btn-warning" title="Descargar documento">
                            <i class="fa fa-file-download"> Descargar documento</i>
                        </button>
                    </form><br><br>
                @endif
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>

@endsection