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
        {{ Form::hidden($columna, $dato, array('class' => 'form-control ' . $config['class_input'], 'id' => $tabla . '_' . $columna)) }}
        <span class="help-block" id="{{ $tabla . '_' . $columna }}_help">
            @if (isset($datos['description']))
            {{ $datos['description'] }}
            @endif
        </span>
    </div>
</div>
@section('selfcss')
@parent
{{ HTML::style("css/bootstrap-datetimepicker.min.css") }}
@stop
@section('selfjs')
@parent
{{ HTML::script("js/moment-with-locales.min.js") }}
{{ HTML::script("js/bootstrap-datetimepicker.min.js") }}
<script>
    $(document).ready(function() {
        $('#{{ $tabla . "_" . $columna }}').datetimepicker({
            locale: '{{ App::getLocale() }}',
            inline: true,
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true
        });
    });
</script>
@stop
