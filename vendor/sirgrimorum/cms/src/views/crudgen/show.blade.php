@if (Session::has('message'))
<div class="alert alert-info">{{ Session::pull('message') }}</div>
@endif
@if (count($errors->all())>0)
<div class="alert alert-danger">
{{ HTML::ul($errors->all()) }}
</div>
@endif
<?php
$tabla = $config['tabla'];
$campos = $config['campos'];
$botones = $config['botones'];
$nombre = $config['nombre'];
if (isset($config['relaciones'])) {
    $relaciones = $config['relaciones'];
}
$identificador = $config['id'];

if (isset($config['render'])){
    $selects = array('column_name as field', 'column_type as type', 'is_nullable as null', 'column_key as key', 'column_default as default', 'extra as extra');
    $table_describes = DB::table('information_schema.columns')
            ->where('table_name', '=', $tabla)
            ->get($selects);
    foreach ($table_describes as $k => $v) {
        if (($kt = array_search($v, $table_describes)) !== false and $k != $kt) {
            unset($table_describes[$kt]);
        }
    }
}
?>
<div class="jumbotron text-center">
    <h2>{{ $registro->{$nombre} }}</h2>
    <p>
    @if (isset($config['render']))
        @foreach($table_describes as $key => $columna)
        @if (isset($relaciones[$columna->field]))
        <strong>{{ ucfirst($relaciones[$columna->field]['modelo']) }}: </strong>
            @if (count($registro->{$relaciones[$columna->field]['modelo']}))
            {{ $registro->{$relaciones[$columna->field]['modelo']}->{$relaciones[$columna->field]['nombre']} }}
            @endif
        <br>
        @else
        <strong>{{ ucfirst($columna->field) }}:</strong> {{ $registro->{$columna->field} }}<br>
        @endif
        @endforeach
    @else
        @foreach($campos as $columna => $datos)
            <strong>{{ ucfirst($datos['label']) }}: </strong>
            @if (isset($datos["pre"]))
                {{ $datos["pre"] }}
            @endif
            @if ($datos['tipo']=="relationship")
                @if (count($registro->{$datos['modelo']}))
                    @if(array_key_exists('enlace',$datos))
                        <a href="{{ str_replace("{ID}",$registro->{$datos['modelo']}->{$datos['id']},str_replace(urlencode ("{ID}"),$registro->{$datos['modelo']}->{$datos['id']},$datos['enlace'])) }}">
                    @endif
                    {{ $registro->{$datos['modelo']}->{$datos['campo']} }}
                    @if(array_key_exists('enlace',$datos))
                        </a>
                    @endif
                @elseif (count($registro->{$columna}))
                    @if(array_key_exists('enlace',$datos))
                        <a href="{{ str_replace("{ID}",$registro->{$columna}->{$datos['id']},str_replace(urlencode ("{ID}"),$registro->{$columna}->{$datos['id']},$datos['enlace'])) }}">
                    @endif
                    {{ $registro->{$columna}->{$datos['campo']} }}
                    @if(array_key_exists('enlace',$datos))
                        </a>
                    @endif
                @endif
            @elseif ($datos['tipo']=="relationships")
                @if (count($registro->{$columna}()->get())>0)
                    @foreach($registro->{$columna}()->get() as $sub)
                        @if(array_key_exists('enlace',$datos))
                            <a href="{{ str_replace("{ID}",$sub->{$datos['id']},str_replace(urlencode ("{ID}"),$sub->{$datos['id']},$datos['enlace'])) }}">
                        @endif
                        {{ $sub->{$datos['campo']} }}
                        @if(array_key_exists('enlace',$datos))
                            </a>
                        @endif
                        ,
                    @endforeach
                @else
                @endif
            @elseif ($datos['tipo']=="select")
                @if (array_key_exists($registro->{$columna},$datos['opciones']))
                {{ $datos['opciones'][$registro->{$columna}] }}
                @endif
            @elseif ($datos['tipo']=="function")
                    @if (isset($datos['format']))
                        @if (is_array($datos['format']))
                            {{ number_format($registro->{$columna}(),$datos['format'][0],$datos['format'][1],$datos['format'][2]) }}
                        @else
                            {{ number_format($registro->{$columna}()) }}
                        @endif
                    @else            
                        {{ $registro->{$columna}() }}
                    @endif
            @elseif ($datos['tipo']=="url")
                <a href='{{ $registro->{$columna} }}' target='_blank'>{{ $registro->{$columna} }}</a>
            @elseif ($datos['tipo']=="file" && isset($datos['pathImage']))
                <div class="container">
                    @if ($registro->{$columna} == "" )
                        -
                    @else
                        @if (isset($datos['enlace']))
                            <a href='{{ str_replace("{value}", $registro->{$columna}, $datos['enlace'] ) }}' target="_blank">
                        @endif
                        @if (preg_match('/(\.jpg|\.png|\.bmp)$/', $registro->{$columna}))
                            <image class="img-thumbnail" src="{{ asset('/images/' . $datos['pathImage'] . $registro->{$columna} ) }}" alt="{{ $columna }}"/>
                        @else
                            <image class="img-thumbnail" src="{{ asset('/images/img/file.png' ) }}" alt="{{ $columna }}"/>
                        @endif
                        @if (isset($datos['enlace']))
                            </a>
                        @endif
                    @endif
                </div>
            @elseif ($datos['tipo']=="file")
                @if ($registro->{$columna} == "" )
                    -
                @else
                    @if (isset($datos['enlace']))
                        <a href='{{ str_replace("{value}", $registro->{$columna}, $datos['enlace'] ) }}' target="_blank">
                    @endif
                        {{ $registro->{$columna} }}
                    @if (isset($datos['enlace']))
                        </a>
                    @endif
                @endif
            @elseif ($datos['tipo']=="number" && isset($datos['format']))
                @if (is_array($datos['format']))
                    {{ number_format($registro->{$columna},$datos['format'][0],$datos['format'][1],$datos['format'][2]) }}
                @else
                    {{ number_format($registro->{$columna}) }}
                @endif
            @else
                {{ $registro->{$columna} }}
            @endif
            @if (isset($datos["post"]))
                {{ " " . $datos["post"] }}
            @endif
            <br/>
        @endforeach
    @endif
    </p>
</div>
