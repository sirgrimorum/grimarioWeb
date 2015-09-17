<?php
$config = Config::get('crudgen.comment');
$task = $comment->task;
$config['botones'] = Lang::get("comment.labels.edit");
$config['url'] = action('CommentsController@update', [$comment->id]);
$config["campos"]["image_nue"]=$config["campos"]["image"];
$config["campos"]["image_nue"]["label"]=Lang::get("comment.labels.image_nue");
$config["campos"]["image"]["tipo"]="text";
$config["campos"]["image"]["readonly"]="readonly";
$config["campos"]["task_id"]["tipo"] = "hidden";
//$config["campos"]["user_id"]["reado"] = "hidden";
$config["campos"]["date"]["tipo"] = "hidden";
$config["campos"]["commenttype"]["valor"] = $comment->commenttype->id;
$config['campos']['redirect'] = [
    'tipo' => 'hidden',
    'valor' => URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id))
];
if ($task->state == 'pla') {
    $config['campos']['redirect']['valor'].= '?st=des';
} elseif ($task->state == 'des') {
    $config['campos']['redirect']['valor'].= "?st=pau";
} elseif ($task->state == 'ter') {
    $config['campos']['redirect']['valor'] .= "?st=ent";
}elseif ($task->state == 'ent') {
    $config['campos']['redirect']['valor'] = URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
}elseif ($task->state == 'cer') {
    $config['campos']['redirect']['valor'] = URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
}
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($comment->task->proyect->id)) }}">{{ $comment->task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($comment->task->payments()->first()->id)) }}">{{ $comment->task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($comment->task->id)) }}">{{ $comment->task->name }}</a></li>
  <li class="active">{{ Lang::get("comment.titulos.edit") }}</li>
</ol>
<h1>{{ Lang::get("comment.titulos.edit") }}</h3>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $task->name }}</h3> <h6>{{ $task->code }}</h6>
            </div>
            <div class="panel-body">
                <p>{{ $task->description }}</p>
            </div>
        </div>
    </div>
</div>
<div class='container'>
    {{ CrudLoader::edit($config,$comment->id,$comment) }}
</div>

@stop

@section("selfjs")
<script >
    $(document).ready(function() {
        //alert(translations.task.error);
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop