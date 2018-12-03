@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Error!!!</b></div>
                {{ csrf_field() }}
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="content">
                        <div class="title m-b-md">
                            Error 404
                        </div>
                        <h3>No pudimos encontrar la página solicitada.</h3>
                    </div>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Regresar al inicio</a><br><br>
        </div>
    </div>
</div>

@endsection