<?php
$config = Config::get('crudgen.comment');
$preDatos = false;
if (Input::has('tk')) {
    $preDatos = true;
    $config["campos"]["task_id"]["tipo"] = "hidden";
    $config["campos"]["task_id"]["valor"] = $task->id;
    $config["campos"]["user_id"]["tipo"] = "hidden";
    $config["campos"]["user_id"]["valor"] = $user->id;
    $config["campos"]["date"]["tipo"] = "hidden";
    $config["campos"]["date"]["valor"] = date("Y-m-d H:i:s");
    $config['campos']['redirect'] = [
        'tipo' => 'hidden',
        'valor' => URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array($task->id))
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
    $config['campos']['commenttype']['todos'] = $commenttypes;
} else {
    $config['campos']['redirect'] = [
        'tipo' => 'hidden',
        'valor' => URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id, 'py' => $task->payments()->first()->id, 'tt' => $task->tasktype->id, 'tk' => $task->id))->with('message', Lang::get("comment.mensajes.creado"))
    ];
}
?>
@extends("layouts.principal")

@section("contenido")
@if ($preDatos)
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id)) }}">{{ $task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id)) }}">{{ $task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id)) }}">{{ $task->name }}</a></li>
  <li class="active">{{ Lang::get("comment.titulos.create") }}</li>
</ol>
@endif
<h1>{{ Lang::get("comment.titulos.create") }}</h3>
<p>{{ TransArticle::get("comment.prueba2") }}</p>
@if ($preDatos)
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
@endif
<div class='container'>
    {{ CrudLoader::create($config) }}
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