@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE USUARIOS</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table id="tabla-formateada" width="100%" class="table table-striped table-bordered" data-order='[[ 0, "desc" ]]' data-page-length="10">
                        <thead>
                            <tr>
                                <th><center>ID</center></th>
                                <th><center>RFC</center></th>
                                <th><center>Nombre</center></th>
                                <th><center>E-Mail</center></th>
                                <th><center>Cerys</center></th>
                                <th><center>Acciones</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $element)
                                <tr>
                                    <td>{{ $element->id }}</td>
                                    <td>{{ $element->username }}</td>
                                    <td>{{ $element->name }}</td>
                                    <td>{{ $element->email }}</td>
                                    <td> {{ ToolsPHP::NomCerys($element->cerys) }} </td>
                                    <td>
                                        <center>
                                            <form style="display: inline" method="POST" action="{{ route('borraUser', $element->id) }}">
                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}
                                                <button type="submit" class="btn btn-danger" title="Borrar usuario">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>

@endsection