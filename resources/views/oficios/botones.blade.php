{!! Form::open(['route' => ['listaLotes', $lotes, $oficio, $numCerys], 'method' => 'GET', 'style' => "display: inline"]) !!}
    <button type="submit" class="btn btn-primary" title="Ver contenido del oficio">
        <i class="fa fa-eye"></i>
    </button>
{!! Form::close() !!}
@if($estado === 'Aceptado')
    @if($firmado === 'Si')
        {!! Form::open(['route' => ['pdf', $oficio, 1], 'method' => 'GET', 'style' => "display: inline", 'target' => "_blank"]) !!}
            <button type="submit" class="btn btn-default" title="Ver PDF">
                <i class="far fa-file-pdf"></i>
            </button>
        {!! Form::close() !!}
        {!! Form::open(['route' => ['pdf', $oficio, 2], 'method' => 'GET', 'style' => "display: inline"]) !!}
            <button type="submit" class="btn btn-warning" title="Descargar documento">
                <i class="fa fa-download"></i>
            </button>
        {!! Form::close() !!}
    @else
        @if($usuario !== 'Admin' && $firmado === 'No' && $estado === 'Aceptado')
            {!! Form::open(['route' => ['firmaLotes', $lotes, $oficio, $numCerys], 'method' => 'GET', 'style' => "display: inline"]) !!}
                <button type="submit" class="btn btn-success" title="Firmar oficio">
                    <i class="fa fa-file-signature"></i>
                </button>
            {!! Form::close() !!}
        @endif
    @endif
@else
    @if($usuario !== 'Admin' && $estado === 'Pendiente')
        {!! Form::open(['route' => ['home'], 'method' => 'GET', 'style' => "display: inline"]) !!}
            <a href="javascript:;" OnClick="aceptar({{ $id }}, '{{ $oficio }}', '{{ $cerys }}');" class="btn btn-success" title="Aceptar oficio">
                <i class="fa fa-check"></i>
            </a>
        {!! Form::close() !!}
        {!! Form::open(['route' => ['home'], 'method' => 'GET', 'style' => "display: inline"]) !!}
            <a href="javascript:;" OnClick="rechazar({{ $id }});" class="btn btn-danger" title="Rechazar oficio" data-toggle="modal" data-target="#rechazar">
                <i class="fa fa-times"></i>
            </a>
        {!! Form::close() !!}
    @endif
@endif