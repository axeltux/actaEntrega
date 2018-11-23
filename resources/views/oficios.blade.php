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
                        <table id="tabla-formateada" width="100%" class="display" data-order='[[ 1, "desc" ]]' data-page-length="5">
                            <thead>
                                <tr>
                                    <th><center>ID</center></th>
                                    <th><center>#Oficio</center></th>
                                    <th><center>Cerys</center></th>
                                    <th><center>#Lote</center></th>
                                    <th><center>Creado</center></th>
                                    <th><center>Estado del Oficio</center></th>
                                    <th><center>Firmado</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oficios as $element)
                                    <tr>
                                        <td>{{ $element->id }}</td>
                                        <td>{{ $element->oficio }}</td>
                                        <td> {{ ToolsPHP::NomCerys($element->cerys) }} </td>
                                        <td><b>{{ $element->lotes }}</b></td>
                                        <td>{{ ToolsPHP::FechaHumanos($element->creadoEl) }}</td>
                                        <td>
                                            <center>
                                                @if($element->status == 1)
                                                    <b style="color:blue;">Aceptado</b>
                                                @elseif($element->status == 2)
                                                    <b style="color:red;">Rechazado</b>
                                                @else
                                                    <b>Pendiente</b>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                @if($element->firmado)
                                                    <b style="color:green;">Si</b>
                                                @else
                                                    <b style="color:red;">No</b>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <form style="display: inline" action="{{ route('listaLotes', [$element->lotes, $element->oficio, $element->cerys]) }}">
                                                    <button type="submit" class="btn btn-primary" title="Ver contenido del oficio">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </form>
                                                @if($element->status == 1)
                                                    @if($element->firmado == 1)
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
                                                        @if(Auth::user()->username !== 'Admin' && $element->firmado == 0 && $element->status == 1)
                                                            <form style="display: inline" action="{{ route('firma', $element->oficio) }}">
                                                                <button type="submit" class="btn btn-success" title="Firmar oficio">
                                                                    <i class="fa fa-file-signature"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if(Auth::user()->username !== 'Admin' && $element->status == 0)
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
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection