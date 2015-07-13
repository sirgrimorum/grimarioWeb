<?php
$config = array_except(Config::get('crudgen.restriction'), array('campos'));

$preDatos = false;

if (Input::has("tk") && Input::has("st")) {
    $config['campos'] = array_except(Config::get('crudgen.restriction.campos'), array(''));
    $config["campos"]["tk"] = [
        "tipo" => "hidden",
        "valor" => Input::get("tk"),
    ];
    $config["campos"]["st"] = [
        "tipo" => "hidden",
        "valor" => Input::get("st"),
    ];
} else {
    $config['campos'] = array_except(Config::get('crudgen.restriction.campos'), array('comments'));
    $config["campos"]["state"]["tipo"] = "hidden";
}
if (Input::has('pr')) {
    $preDatos = true;
    $config["campos"]["proyect_id"]["tipo"] = "hidden";
    $config["campos"]["proyect_id"]["valor"] = $proyect->id;
}
$config["campos"]["user_id"]["tipo"] = "hidden";
$config["campos"]["user_id"]["valor"] = $user->id;
$config["campos"]["date"]["tipo"] = "hidden";
$config["campos"]["date"]["valor"] = date("Y-m-d H:i:s");
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("restriction.titulos.create") }}</h3>
<p>{{ TransArticle::get("restriction.prueba2") }}</p>
@if ($preDatos)
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $proyect->name }}</h3> <h6>{{ $proyect->code }}</h6>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("proyect.selects.priority.".$proyect->priority) }}</strong>
                <br>
                <strong>{{ Lang::get("proyect.labels.state") }}:</strong> {{ Lang::get("proyect.selects.state.".$proyect->state) }}
                <p>{{ $proyect->description }}</p>
                <strong>{{ Lang::get("proyect.labels.teams") }}:</strong>
                <p>
                    @foreach ($proyect->teams()->get() as $team)
                    <a href="{{ URL::route(Lang::get("principal.menu.links.equipo"). '.show', array($team->id)) }}">{{ $team->name }}</a>, 
                    @endforeach
                </p>
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