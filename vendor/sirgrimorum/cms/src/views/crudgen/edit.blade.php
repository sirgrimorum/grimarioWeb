@if (Session::has('message'))
<div class="alert alert-info">{{ Session::pull('message') }}</div>
@endif
<?php $errores = false ?>
@if (count($errors->all())>0)
<?php $errores = true ?>
@if (isset($config['render']))
<div class="alert alert-danger">
    {{ HTML::ul($errors->all()) }}
</div>
@endif
@endif
<?php
$tabla = $config['tabla'];
$campos = $config['campos'];
$botones = $config['botones'];
if (isset($config['files'])) {
    $files = $config['files'];
}else{
    $files = false;
}
if (isset($config['relaciones'])) {
    $relaciones = $config['relaciones'];
}
$identificador = $config['id'];
$url = $config['url'];
if (! isset($config['class_form'])) {
    $config['class_form']='form-horizontal';
}
if (! isset($config['class_label'])) {
    $config['class_label']='col-xs-12 col-sm-4 col-md-2';
}
if (! isset($config['class_divinput'])) {
    $config['class_divinput']='col-xs-12 col-sm-8 col-md-10';
}
if (! isset($config['class_input'])) {
    $config['class_input']='';
}
if (! isset($config['class_offset'])) {
    $config['class_offset']='col-xs-offset-0 col-sm-offset-4 col-md-offset-2';
}

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
{{ Form::model($registro, array('url' => array($url, $registro->{$identificador}, 'class' => $config['class_form']), 'method' => 'PUT', 'files'=> $files)) }}
    @if (isset($config['render']))
        @foreach($table_describes as $key => $columna)
            <div class="form-group">
                @if (array_key_exists($columna->field,$campos))
                    @include("cms::crud.templates." . array_get($campos,$columna->field . ".tipo"))
                @elseif ($columna->type == "text")
                    {{ Form::label($columna->field, ucfirst($columna->field), array('class'=>$config['class_label'])) }}
                    {{ Form::textarea($columna->field, $registro->{$columna->field}, array('class' => 'form-control ' . $config['class_input'])) }}
                @elseif (isset($relaciones[$columna->field]))
                    {{ Form::label($columna->field, ucfirst($columna->field), array('class'=>$config['class_label'])) }}
                    {{ Form::select($columna->field, $relaciones[$columna->field]["todos"], $registro->{$columna->field}, array('class' => 'form-control ' . $config['class_input'])) }}
                @elseif ($columna->field != "created_at" && $columna->field != "updated_at" && $columna->key != "PRI")
                    {{ Form::label($columna->field, ucfirst($columna->field), array('class'=>$config['class_label'])) }}
                    {{ Form::text($columna->field, $registro->{$columna->field}, array('class' => 'form-control ' . $config['class_input'])) }}
                @endif
            </div>
        @endforeach
    @else
        @foreach($campos as $columna => $datos)
            @if (View::exists("cms::crudgen.templates." .$datos['tipo']))
                @include("cms::crudgen.templates." . $datos['tipo'])
            @else
                @include("cms::crudgen.templates.text")
            @endif
        @endforeach
    @endif
    @if (count($botones)>0)
        @if (is_array($botones))
        <div class="form-group">
        @foreach ($botones as $boton)
            <div class="{{ $config['class_offset'] }} {{ $config['class_divinput'] }}">
            @if (strpos($boton,"<")===false)
                {{ Form::submit($boton, array('class' => 'btn btn-primary')) }}
            @else
                {{ $boton }}
            @endif
            </div>
        @endforeach
        </div>
        @else
            <div class="form-group">
                <div class="{{ $config['class_offset'] }} {{ $config['class_divinput'] }}">
                    @if (strpos($botones,"<")===false)
                        {{ Form::submit($botones, array('class' => 'btn btn-primary')) }}
                    @else
                        {{ $botones }}
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="form-group">
            <div class="{{ $config['class_offset'] }} {{ $config['class_divinput'] }}">
                {{ Form::submit(Lang::get('cms::crud.create.titulo'), array('class' => 'btn btn-primary')) }}
            </div>
        </div>
    @endif
{{ Form::close() }}
