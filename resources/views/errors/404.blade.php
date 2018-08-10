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
					<h1>No pudimos encontar esta página</h1>    				
                </div>                
            </div>
            <a href="{{ route('home') }}" class="enlaceboton2">Regresar al inicio</a>
        </div>
    </div>
</div>
@endsection