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
        <span class="help-block" id="{{ $tabla . '_' . $columna }}_help">
            @if (isset($datos['description']))
            {{ $datos['description'] }}
            @endif
        </span>
        {{ Form::textarea($columna, $dato, array('class' => 'form-control ' . $config['class_input'], 'id' => $tabla . '_' . $columna)) }}
    </div>
</div>

@section('selfjs')
@parent
{{ HTML::script("//cdn.ckeditor.com/4.4.5/full/ckeditor.js") }}
<script>
    $(document).ready(function() {
        CKEDITOR.replace('{{ $tabla . "_" . $columna }}');
    });
</script>
@stop
