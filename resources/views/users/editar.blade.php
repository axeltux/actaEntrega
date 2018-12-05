@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Actualizar el usuario: {{ $user->name }}</div>

                <div class="panel-body">
                    {!! Form::open(['route' => ['updateUser', $user->id], 'method' => 'POST', 'class'=> "form-horizontal" ]) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Nombre</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Usuario</label>

                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}" required autofocus>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('cerys') ? ' has-error' : '' }}">
                        <label for="cerys" class="col-md-4 control-label">Seleccione el Cerys</label>

                        <div class="col-md-6">
                            <select class="form-control" name="cerys" id="cerys" value="old('cerys')" required>
                                <option value="" />Seleccione un cerys</option>
                                @foreach($cerys as $listas)
                                    @if($user->cerys == $listas->Numero)
                                        <option value="{{ $listas->Numero }}" selected="selected">{{ $listas->Nombre}}</option>
                                    @else
                                        <option value="{{ $listas->Numero }}">{{ $listas->Nombre}}</option>
                                    @endif
                                @endforeach
                            </select>

                            @if ($errors->has('cerys'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cerys') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">Dirección de E-Mail</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Actualizar
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <a href="{{ route('listusers') }}" class="btn btn-primary">Regresar</a><br><br>
        </div>
    </div>
</div>

@endsection
