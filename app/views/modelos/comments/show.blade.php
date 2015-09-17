<?php
//$config = array_except(Config::get('crudgen.comment'), array('campos'));
//$config['campos'] = array_except(Config::get('crudgen.comment.campos'), array('order','start', 'end'));
$config = Config::get('crudgen.comment');
$config['campos']['image']['pathImage'] = "comments/"
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($comment->task->proyect->id)) }}">{{ $comment->task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($comment->task->payments()->first()->id)) }}">{{ $comment->task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($comment->task->id)) }}">{{ $comment->task->name }}</a></li>
  <li class="active">{{ Lang::get("comment.titulos.show") }}</li>
</ol>
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