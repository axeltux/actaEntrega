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
                    <div class="form-group{{ $errors->has('cerys') ? ' has-error' : '' }}">
                        <label for="cerys" class="col-md-4 control-label">Seleccione el Cerys</label>
                        <div class="col-md-6">
                            <select class="form-control" name="cerys" id="cerys" value="{{ old('cerys') }}" required>
                                <option value="" />Seleccione un cerys</option>
                                @foreach($cerys as $listas)
                                  <option value="{{$listas->Numero}}">{{$listas->Nombre}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('cerys'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cerys') }}</strong>
                                </span>
                            @endif
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