<?php
if (isset($datos["valor"])) {
    $dato = $datos["valor"];
} else {
    $dato = Input::old($columna);
    if ($dato == "") {
        try {
            $dato = $registro->{$columna};
        } catch (Exception $ex) {
            $dato = "";
        }
    }
}
$error_campo = false;
$claseError = '';
if ($errores == true) {
    if ($errors->has($columna)) {
        $error_campo = true;
        $claseError = 'has-error';
    } 
}
?>
<div class="form-group {{ $claseError }}">
    {{ Form::label($columna, ucfirst($datos['label']), array('class'=>$config['class_label'])) }}
    <div class="{{ $config['class_divinput'] }}">
        @if ($error_campo)
        <div class="alert alert-danger">
            {{ HTML::ul($errors->get($columna)) }}
        </div>
        @endif
        @if (isset($datos["pre"]) || isset($datos["post"]))
        <div class="input-group">
            @endif
            @if (isset($datos["pre"]))
                <div class="input-group-addon">{{ $datos["pre"] }}</div>
            @endif
            {{ Form::number($columna, $dato, array('class' => 'form-control ' . $config['class_input'], 'id' => $tabla . '_' . $columna, 'placeholder'=>$datos['placeholder'])) }}
            @if (isset($datos["post"]))
                <div class="input-group-addon">{{ $datos["post"] }}</div>
            @endif
            @if (isset($datos["pre"]) || isset($datos["post"]))
        </div>
        @endif
        <span class="help-block" id="{{ $tabla . '_' . $columna }}_help">
            @if (isset($datos['description']))
            {{ $datos['description'] }}
            @endif
        </span>
    </div>
</div>