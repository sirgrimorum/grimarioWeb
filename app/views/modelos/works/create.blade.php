<?php
$config = array_except(Config::get('crudgen.work'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.work.campos'), array('end', 'coordinator'));
$config["campos"]["user_id"]["tipo"] = "hidden";
$config["campos"]["user_id"]["valor"] = $user->id;
$preDatos = false;
if (Input::has('tk')) {
    $preDatos = true;
    $config["campos"]["task_id"]["tipo"] = "hidden";
    $config["campos"]["task_id"]["valor"] = $task->id;
    $config["campos"]["users"]["valor"] = $users;
}
$config["campos"]["calendario"] = [
    "tipo" => "checkbox",
    "label" => Lang::get("work.labels.calendario"),
    "description" => Lang::get("work.descriptions.calendario"),
    "valor" => false,
    "value" => true
];
$config["campos"]["sala"] = [
    "tipo" => "checkbox",
    "label" => Lang::get("work.labels.sala"),
    "description" => Lang::get("work.descriptions.sala"),
    "valor" => false,
    "value" => true
];
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("work.titulos.create") }}</h3>
<p>{{ TransArticle::get("work.prueba2") }}</p>
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