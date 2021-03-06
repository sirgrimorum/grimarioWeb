<?php
$config = array_except(Config::get('crudgen.restriction'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.restriction.campos'), array(''));
$config['botones'] = Lang::get("restriction.labels.edit");
$config['url'] = action('RestrictionsController@update', [$restriction->id]);
$proyect = $restriction->proyect;
$config["campos"]["proyect_id"]["tipo"] = "hidden";
unset($config["campos"]["state"]["valor"]);
if (Input::has("tk") && Input::has("st")) {
    $config["campos"]["tk"] = [
        "tipo" => "hidden",
        "valor" => Input::get("tk"),
    ];
    $config["campos"]["st"] = [
        "tipo" => "hidden",
        "valor" => Input::get("st"),
    ];
}
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id)) }}">{{ $proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array($restriction->id)) }}">{{ $restriction->name }}</a></li>
  <li class="active">{{ Lang::get("restriction.titulos.edit") }}</li>
</ol>
<h1>{{ Lang::get("restriction.titulos.edit") }}</h3>
<p>{{ TransArticle::get("restriction.prueba2") }}</p>
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
<div class='container'>
    {{ CrudLoader::edit($config,$restriction->id,$restriction) }}
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