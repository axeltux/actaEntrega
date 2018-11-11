@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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
                                <th>ID</th>
                                <th>RFC</th>
                                <th>Nombre</th>
                                <th>E-Mail</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $element)
                                <tr>
                                    <td>{{ $element->id }}</td>
                                    <td>{{ $element->username }}</td>
                                    <td>{{ $element->name }}</td>
                                    <td>{{ $element->email }}</td>
                                    <td>
                                        <form style="display: inline" method="POST" action="{{ route('borraUser', $element->id) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit" class="btn btn-danger">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>                
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar</a>
        </div>
    </div>
</div>
@endsection