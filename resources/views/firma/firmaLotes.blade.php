@extends('layouts.app')

@section('content')

@routes

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading"><b>ACEPTAR LOTES DEL OFICIO: {{ $oficio }} || CERYS: {{ $nom }}</b></div>
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
                        <table id="tabla-lotesFirmar" width="100%" class="table table-bordered table-striped table-sm" data-order='[[ 0, "asc" ]]' data-page-length="5">
                            <thead>
                                <tr>
                                    <th style="text-align: center">#Empleado</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Unidad</th>
                                    <th style="text-align: center">Lote</th>
                                    <th style="text-align: center">Estado</th>
                                    <th style="text-align: center">Motivo</th>
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lotes as $element)
                                    <tr>
                                        <td> {{ $element->NumeroEmpleado }} </td>
                                        <td> {{ ToolsPHP::NomEmpleados($element->NumeroEmpleado) }} </td>
                                        <td> {{ $element->UnidadAdmin }} </td>
                                        <td> {{ $element->Lote }} </td>
                                        <td>
                                            @if($element->Acepta == 1)
                                                <b style="color:blue;">Aceptado</b>
                                            @else
                                                <b style="color:red;">Rechazado</b>
                                            @endif
                                        </td>
                                        <td> {{ ToolsPHP::nombreMotivo($element->MotivoRechazo) }} </td>
                                        <td>
                                            @if($firmado != 1)
                                                {!! Form::open(['route' => ['firmaLotes', $listaLotes, $oficio, $cerys], 'method' => 'GET', 'style' => "display: inline"]) !!}
                                                    <a href="javascript:;" OnClick="editarCredencial({{ $element->Id }});" class="btn btn-danger" title="Modificar estado" data-toggle="modal" data-target="#editarRechazar">
                                                        <i class="fa fa-edit"></i> Editar estado
                                                    </a>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if($firmado == 0 && $aceptado == 1)
                        @if(Auth::user()->username !== 'Admin')
                            {!! Form::open(['route' => ['firma', $oficio], 'method' => 'GET', 'style' => "display: inline"]) !!}
                            <button type="submit" class="btn btn-success" title="Firmar oficio">
                                <i class="fa fa-file-signature"></i> Firmar oficio
                            </button>
                            {!! Form::close() !!}
                            <br><br>
                        @endif
                    @elseif ($firmado == 1 && $aceptado == 1)
                        {!! Form::open(['route' => ['pdf', $oficio, 2], 'method' => 'GET', 'style' => "display: inline"]) !!}
                        <button type="submit" class="btn btn-warning" title="Descargar documento">
                            <i class="fa fa-file-download"></i> Descargar documento
                        </button>
                        {!! Form::close() !!}
                        <br><br>
                    @endif
                </div>
                @include('firma.editarCredencial')
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>

@endsection