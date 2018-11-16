@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Error de Acceso</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
					<h2>No tienes permisos para esta sección.</h2>
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Ir atras</a><br><br>
        </div>
    </div>
</div>

@endsection