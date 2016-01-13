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
if (isset($datos["readonly"])){
    $readonly = $datos["readonly"];
}else{
    $readonly = "";
}
if (isset($datos["min"])){
    $min = $datos["min"];
}else{
    $min = 0;
}
if (isset($datos["max"])){
    $max = $datos["max"];
}else{
    $max = 100;
}
if (isset($datos["step"])){
    $step = $datos["step"];
}else{
    $step = 1;
}
if (isset($datos["post"])){
    $post = $datos["post"];
}else{
    $post = "";
}
if (isset($datos["pre"])){
    $pre = $datos["pre"];
}else{
    $pre = "";
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
        {{ Form::text($columna, $dato, array('class' => 'form-control ' . $config['class_input'], 'id' => $tabla . '_' . $columna, 'data-slider-id'=>$tabla . '_' . $columna . 'Slider', 'data-slider-min'=>$min, 'data-slider-max'=>$max, 'data-slider-step'=>$step, 'data-slider-value'=>$dato ,$readonly)) }}
        <span class="help-block" id="{{ $tabla . '_' . $columna }}_help">
            @if (isset($datos['description']))
            {{ $datos['description'] }}
            @endif
        </span>
    </div>
</div>

@section('selfcss')
@parent
{{ HTML::style("css/bootstrap-slider.css") }}
@stop
@section('selfjs')
@parent
{{ HTML::script("js/bootstrap-slider.js") }}
<script>
    $(document).ready(function() {
        $('#{{ $tabla . "_" . $columna }}').sliderb({
            formatter:function(value){
                return "{{ $pre }}" + value + "{{ $post }}";
            }
        });
    });
</script>
@stop
