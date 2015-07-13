<?php
//$config = array_except(Config::get('crudgen.comment'), array('campos'));
//$config['campos'] = array_except(Config::get('crudgen.comment.campos'), array('order','start', 'end'));
$config = Config::get('crudgen.comment');
$config['campos']['image']['pathImage'] = "comments/"
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("comment.titulos.show") }}</h3>
<div class='container'>
    {{ CrudLoader::show($config,$comment->id,$comment) }}
</div>
@stop

@section("selfjs")
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop