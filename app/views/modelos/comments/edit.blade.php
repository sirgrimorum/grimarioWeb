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
$config["campos"]["commenttype"]["valor"] = $task->commenttype->id;
$config['campos']['redirect'] = [
    'tipo' => 'hidden',
    'valor' => URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id))
];
if ($task->state == 'pla') {
    $config['campos']['redirect']['valor'].= '?st=des';
} elseif ($task->state == 'des') {
    $config['campos']['redirect']['valor'].= "?st=pau";
} elseif ($task->state == 'ter') {
    $config['campos']['redirect']['valor'] .= "?st=ter";
}
?>
@extends("layouts.principal")

@section("contenido")
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