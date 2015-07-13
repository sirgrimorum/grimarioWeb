<?php
$config = Config::get('crudgen.cost');
$preDatos = false;
$preTrabajo = false;
if (Input::has('wk')) {
    $preDatos = true;
    $preTrabajo = true;
    $config["campos"]["work_id"]["tipo"] = "hidden";
    $config["campos"]["work_id"]["valor"] = $work->id;
    $config["campos"]["user_id"]["tipo"] = "hidden";
    $config["campos"]["user_id"]["valor"] = $user->id;
    $config['campos']['redirect'] = [
        'tipo' => 'hidden',
        'valor' => URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array($task->id))
    ];
    if ($task->state == 'pla') {
        $config['campos']['redirect']['valor'].= '?st=des';
    } elseif ($task->state == 'des') {
        $config['campos']['redirect']['valor'].= "?st=pau";
    } elseif ($task->state == 'ter') {
        $config['campos']['redirect']['valor'] .= "?st=ter";
    }
} elseif (Input::has('tk')){
    $preDatos = true;
    $config["campos"]["work_id"]["todos"] = $task->works()->get();
    $config["campos"]["user_id"]["tipo"] = "hidden";
    $config["campos"]["user_id"]["valor"] = $user->id;
    $config['campos']['redirect'] = [
        'tipo' => 'hidden',
        'valor' => URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array($task->id))
    ];
    if ($task->state == 'pla') {
        $config['campos']['redirect']['valor'].= '?st=des';
    } elseif ($task->state == 'des') {
        $config['campos']['redirect']['valor'].= "?st=pau";
    } elseif ($task->state == 'ter') {
        $config['campos']['redirect']['valor'] .= "?st=ter";
    }
} else{
    $config['campos']['redirect'] = [
        'tipo' => 'hidden',
        'valor' => URL::route(Lang::get("principal.menu.links.costo") . '.index')
    ];
}
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("cost.titulos.create") }}</h3>
<p>{{ TransArticle::get("cost.prueba2") }}</p>
@if ($preDatos)
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $task->name }}</h3> <h6>{{ $task->code }}</h6>
            </div>
            <div class="panel-body">
                <p>{{ $task->description }}</p>
                @if ($preTrabajo)
                <p>{{ $work->start }}</p>
                @endif
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