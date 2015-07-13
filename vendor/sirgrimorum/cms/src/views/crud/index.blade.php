@extends("cms::layouts.includes.principal")

@section('menuobj')
<li><a href="{{ URL::to($base_url . "/" . $plural .'/create') }}">{{ Lang::get('cms::crud.layout.crear') }} {{ $modelo }}</a></li>
@stop

@section('contenido')
<h1>{{ ucfirst($plural) }}</h1>

@if (Session::has('message'))
<div class="alert alert-info">{{ Session::pull('message') }}</div>
@endif

<?php
$selects = array('column_name as field', 'column_type as type', 'is_nullable as null', 'column_key as key', 'column_default as default', 'extra as extra');
$table_describes = DB::table('information_schema.columns')
        ->where('table_name', '=', $tabla)
        ->get($selects);
foreach ($table_describes as $k => $v) {
    if (($kt = array_search($v, $table_describes)) !== false and $k != $kt) {
        unset($table_describes[$kt]);
    }
}
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            @foreach($table_describes as $key => $columna)
            @if (isset($relaciones[$columna->field]))
            <td>{{ ucfirst($relaciones[$columna->field]['modelo']) }}</td>
            @elseif ($columna->field != "created_at" && $columna->field != "updated_at")
            <td>{{ $columna->field }}</td>
            @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $key => $value)
        <tr>
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
            <td>
                {{ Form::open(array('url' => $base_url . "/"  . $plural . '/' . $value[$identificador], 'class' => 'pull-right')) }}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit(Lang::get('cms::crud.index.eliminar'), array('class' => 'btn btn-warning')) }}
                {{ Form::close() }}
                <a class="btn btn-small btn-success" href="{{ URL::to( $base_url . "/"  . $plural . '/' . $value[$identificador]) }}">{{ Lang::get('cms::crud.index.ver') }}</a>
                <a class="btn btn-small btn-info" href="{{ URL::to( $base_url . "/"  . $plural . '/' . $value[$identificador] . '/edit') }}">{{ Lang::get('cms::crud.index.editar') }}</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop
