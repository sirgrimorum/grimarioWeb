<?php

$dato = Input::old($columna->field);
if ($dato == ""){
    try{
        $dato = $registro->{$columna->field};
    }catch (Exception $ex){
        $dato="";
    }
}
?>
{{ Form::label($columna->field, ucfirst($columna->field)) }}
{{ Form::textarea($columna->field, $dato, array('class' => 'form-control', 'id' => 'html_' . $columna->field)) }}

@section('selfjs')
{{ HTML::script("//cdn.ckeditor.com/4.4.5/full/ckeditor.js") }}
<script>
    $(document).ready(function() {
        CKEDITOR.replace( '{{ "html_" . $columna->field }}' );
    });
</script>
@stop
