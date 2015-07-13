@extends("cms::layouts.includes.principal")

@section('menuobj')
<li><a href="{{ URL::to($base_url . "/" . $plural .'/create') }}">{{ Lang::get('cms::crud.layout.crear') }} {{ $modelo }}</a></li>
@stop

@section('contenido')
<h1>{{ Lang::get('cms::crud.create.titulo') }} {{ $modelo }}</h1>

{{ HTML::ul($errors->all()) }}

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
{{ Form::open(array('url' => $base_url . "/"  . $plural)) }}
    @foreach($table_describes as $key => $columna)
	<div class="form-group">
                @if (array_key_exists($columna->field,$campos))
                @include("cms::crud.templates." . array_get($campos,$columna->field . ".tipo"))
                @elseif (stripos($columna->type,"text")!== false)
		{{ Form::label($columna->field, ucfirst($columna->field)) }}
                {{ Form::textarea($columna->field, Input::old($columna->field), array('class' => 'form-control')) }}
                @elseif (isset($relaciones[$columna->field]))
		{{ Form::label($columna->field, ucfirst($columna->field)) }}
                {{ Form::select($columna->field, $relaciones[$columna->field]["todos"], Input::old($columna->field), array('class' => 'form-control')) }}
                @elseif ($columna->field != "created_at" && $columna->field != "updated_at" && $columna->key != "PRI")
		{{ Form::label($columna->field, ucfirst($columna->field)) }}
		{{ Form::text($columna->field, Input::old($columna->field), array('class' => 'form-control')) }}
                @endif
	</div>
    @endforeach
    {{ Form::submit( Lang::get('cms::crud.create.titulo') . ' ' . $modelo, array('class' => 'btn btn-primary')) }}
{{ Form::close() }}

@stop
