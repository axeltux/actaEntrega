{!! Form::open(['route' => ['home'], 'method' => 'GET', 'style' => "display: inline"]) !!}
<a href="javascript:" OnClick="$(this).rechazar({{ $id }});" class="btn btn-danger" title="Rechazar oficio" data-toggle="modal" data-target="#rechazar">
    <i class="fa fa-times"></i>
</a>
{!! Form::close() !!}