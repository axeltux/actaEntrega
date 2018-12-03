<!-- Boton editar usuario -->
{!! Form::open(['route' => ['editUser', $id], 'method' => 'GET', 'style' => "display: inline"]) !!}
    <button type="submit" class="btn btn-primary" title="Editar usuario">
        <i class="fa fa-edit"></i>
    </button>
{!! Form::close() !!}
<!-- Boton borrar usuario -->
{!! Form::open(['route' => ['listusers'], 'method' => 'GET', 'style' => "display: inline"]) !!}
    <a href="javascript:;" OnClick="borrarUsuario({{ $id }});" class="btn btn-danger" title="Borrar usuario">
        <i class="fa fa-trash-alt"></i>
    </a>
{!! Form::close() !!}