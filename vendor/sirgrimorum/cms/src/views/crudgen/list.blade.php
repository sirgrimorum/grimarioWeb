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
$tablaid = $tabla . "_" . str_random(5);
$campos = $config['campos'];
if (isset($config['botones'])) {
    if ($config['botones'] != "") {
        $botones = $config['botones'];
    } else {
        $botones = [];
    }
}else{
    $botones = [];
}
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
<table class="table table-striped table-bordered" id='list_{{ $tablaid }}'>
    <thead>
        <tr>
        @if (isset($config['render']))
            @foreach($table_describes as $key => $columna)
            @if (isset($relaciones[$columna->field]))
            <th>{{ ucfirst($relaciones[$columna->field]['modelo']) }}</th>
            @elseif ($columna->field != "created_at" && $columna->field != "updated_at")
            <th>{{ $columna->field }}</th>
            @endif
            @endforeach
        @else
            @foreach($campos as $columna => $datos)
                <th>{{ ucfirst($datos['label']) }}</th>
            @endforeach
            @if (count($botones)>0)
                <th></th>
            @endif
        @endif
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $key => $value)
        <tr>
        @if (isset($config['render']))
            @foreach($table_describes as $columna)
                @if (isset($relaciones[$columna->field]))
                    @if (count($value->{$relaciones[$columna->field]['modelo']}))
                        <td>{{ $value->{$relaciones[$columna->field]['modelo']}->{$relaciones[$columna->field]['nombre']} }}</td>
                    @else
                        <td>-</td>
                    @endif
                @elseif ($columna->field != "created_at" && $columna->field != "updated_at")
                    <td>{{ $value->{$columna->field} }}</td>
                @endif
            @endforeach
        @else
            @foreach($campos as $columna => $datos)
                <td>
                    @if (isset($datos["pre"]))
                        {{ $datos["pre"] }}
                    @endif
                @if ($datos['tipo']=="relationship")
                    @if (count($value->{$datos['modelo']}))
                            @if(array_key_exists('enlace',$datos))
                            <a href="{{ str_replace("{ID}",$value->{$datos['modelo']}->{$datos['id']},str_replace(urlencode ("{ID}"),$value->{$datos['modelo']}->{$datos['id']},$datos['enlace'])) }}">
                            @endif
                            {{ $value->{$datos['modelo']}->{$datos['campo']} }}
                            @if(array_key_exists('enlace',$datos))
                            </a>
                            @endif
                    @elseif (count($value->{$columna}))
                            @if(array_key_exists('enlace',$datos))
                            <a href="{{ str_replace("{ID}",$value->{$columna}->{$datos['id']},str_replace(urlencode ("{ID}"),$value->{$columna}->{$datos['id']},$datos['enlace'])) }}">
                            @endif
                            {{ $value->{$columna}->{$datos['campo']} }}
                            @if(array_key_exists('enlace',$datos))
                            </a>
                            @endif
                    @else
                        -
                    @endif
                @elseif ($datos['tipo']=="relationships")
                    @if (count($value->{$datos['modelo']}()->get())>0)
                        @foreach($value->{$datos['modelo']}()->get() as $sub)
                                <p>
                                    @if(array_key_exists('enlace',$datos))
                                    <a href="{{ str_replace("{ID}",$sub->{$datos['id']},str_replace(urlencode ("{ID}"),$sub->{$datos['id']},$datos['enlace'])) }}">
                                    @endif
                                    {{ $sub->{$datos['campo']} }}
                                    @if(array_key_exists('enlace',$datos))
                                    </a>
                                    @endif
                                </p>
                        @endforeach
                    @elseif (count($value->{$columna}()->get())>0)
                        @foreach($value->{$columna}()->get() as $sub)
                                <p>
                                    @if(array_key_exists('enlace',$datos))
                                    <a href="{{ str_replace("{ID}",$sub->{$datos['id']},str_replace(urlencode ("{ID}"),$sub->{$datos['id']},$datos['enlace'])) }}">
                                    @endif
                                    {{ $sub->{$datos['campo']} }}
                                    @if(array_key_exists('enlace',$datos))
                                    </a>
                                    @endif
                                </p>
                        @endforeach
                    @else
                        -
                    @endif
                @elseif ($datos['tipo']=="select")
                    @if (array_key_exists($value->{$columna},$datos['opciones']))
                        {{ $datos['opciones'][$value->{$columna}] }}
                    @else
                        -
                    @endif
                @elseif ($datos['tipo']=="function")
                    @if (isset($datos['format']))
                        @if (is_array($datos['format']))
                            {{ number_format($value->{$columna}(),$datos['format'][0],$datos['format'][1],$datos['format'][2]) }}
                        @else
                            {{ number_format($value->{$columna}()) }}
                        @endif
                    @else            
                        {{ $value->{$columna}() }}
                    @endif
                @elseif ($datos['tipo']=="url")
                    <a href='{{ $value->{$columna} }}' target='_blank'>{{ $value->{$columna} }}</a>
                @elseif ($datos['tipo']=="file")
                    @if (isset($datos['pathImage']))
                            @if ($value->{$columna} == "" )
                                -
                            @else
                                @if (isset($datos['enlace']))
                                    <a href='{{ str_replace("{value}", $value->{$columna}, $datos['enlace'] ) }}' target="_blank">
                                @endif
                                @if (preg_match('/(\.jpg|\.png|\.bmp)$/', $value->{$columna}))
                                    <image class="img-responsive" src="{{ asset('/images/' . $datos['pathImage'] . $value->{$columna} ) }}" alt="{{ $columna }}"/>
                                @else
                                    <image class="img-responsive" src="{{ asset('/images/img/file.png' ) }}" alt="{{ $columna }}"/>
                                @endif
                                @if (isset($datos['enlace']))
                                    </a>
                                @endif
                            @endif
                    @else
                            @if ($value->{$columna} == "" )
                                -
                            @else
                                @if (isset($datos['enlace']))
                                    <a href='{{ str_replace("{value}", $value->{$columna}, $datos['enlace'] ) }}' target="_blank">
                                @endif
                                    {{ $value->{$columna} }}
                                @if (isset($datos['enlace']))
                                    </a>
                                @endif
                            @endif
                    @endif
                @else
                    @if(array_key_exists('enlace',$datos))
                        <a href="{{ str_replace("{ID}",$value->{$identificador},str_replace(urlencode ("{ID}"),$value->{$identificador},$datos['enlace'])) }}">
                    @endif
                    @if ($datos['tipo']=="number" && isset($datos['format']))
                        @if (is_array($datos['format']))
                            {{ number_format($value->{$columna},$datos['format'][0],$datos['format'][1],$datos['format'][2]) }}
                        @else
                            {{ number_format($value->{$columna}) }}
                        @endif
                    @else            
                        {{ $value->{$columna} }}
                    @endif
                    @if(array_key_exists('enlace',$datos))
                        </a>
                    @endif
                @endif
                @if (isset($datos["post"]))
                    {{ " " . $datos["post"] }}
                @endif
                </td>
            @endforeach
        @endif
            @if (count($botones)>0)
            <td>
                @if (is_array($botones))
                @foreach ($botones as $boton)
                {{ str_replace("{ID}",$value[$identificador],str_replace(urlencode ("{ID}"),$value[$identificador],$boton)) }}
                @endforeach
                @else
                {{ str_replace("{ID}",$value[$identificador],str_replace(urlencode ("{ID}"),$value[$identificador],$botones)) }}
                @endif
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

@section("selfcss")
@parent
{{ HTML::style("packages/sirgrimorum/cms/css/jquery.dataTables.min.css") }}
{{ HTML::style("packages/sirgrimorum/cms/css/dataTables.bootstrap.css") }}
{{ HTML::style("packages/sirgrimorum/cms/css/dataTables.colReorder.min.css") }}
{{ HTML::style("packages/sirgrimorum/cms/css/dataTables.fixedHeader.min.css") }}
{{ HTML::style("packages/sirgrimorum/cms/css/dataTables.responsive.css") }}
{{ HTML::style("packages/sirgrimorum/cms/css/dataTables.tableTools.min.css") }}
@stop

@section("selfjs")
@parent
{{ HTML::script("packages/sirgrimorum/cms/js/jquery.dataTables.min.js") }}
{{ HTML::script("packages/sirgrimorum/cms/js/dataTables.bootstrap.js") }}
{{ HTML::script("packages/sirgrimorum/cms/js/dataTables.colReorder.min.js") }}
{{ HTML::script("packages/sirgrimorum/cms/js/dataTables.fixedHeader.min.js") }}
{{ HTML::script("packages/sirgrimorum/cms/js/dataTables.responsive.min.js") }}
{{ HTML::script("packages/sirgrimorum/cms/js/dataTables.tableTools.min.js") }}
<script>
    $(document).ready(function() {
        var lista_{{ $tabla }} = $('#list_{{ $tablaid }}').DataTable({
            responsive: true,
            dom: 'Rlfrtip',
            tableTools: {
            sSwfPath: "/swf/copy_csv_xls_pdf.swf"
            },
            @if (isset($config['orden']))
            order : {{ json_encode($config['orden']) }},
            @endif
        });
        //new $.fn.dataTable.FixedHeader(lista_{{ $tabla }});
    });
</script>
@stop