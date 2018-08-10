@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b>LISTADO DE CERYS</b></div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <form class="form-horizontal" method="POST" action="{{ route('oficios') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <div class="btn-group" role="group">
                                <label for="cerys" class="btn-group">Cerys:&nbsp;&nbsp;</label>
                                <select class="btn btn-default dropdown-toggle" name="cerys" id="cerys" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <option value="" />Seleccione...</option>
                                    @foreach($cerys as $listas)
                                      <option value="{{$listas->numero}}">{{$listas->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary" id="btn-cerys">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection