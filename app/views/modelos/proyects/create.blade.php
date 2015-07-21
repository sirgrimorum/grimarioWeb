<?php
$config = array_except(Config::get('crudgen.proyect'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.proyect.campos'), array('satisfaction','experience','advance', 'totalplan', 'totalcost', 'saves', 'value', 'profit'));
$config["campos"]["state"]["tipo"] = "hidden";
$preDatos = false;
if (Input::has('en')) {
    $preDatos = true;
    $config["campos"]["type"]["tipo"] = "hidden";
    $config["campos"]["type"]["valor"] = "emp";
    $config["campos"]["enterprises"]["tipo"] = "hidden";
    $config["campos"]["enterprises"]["valor"] = $enterprise->id;
    $config["campos"]["teams"]["todos"] = $enterprise->teams()->get();
}
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("proyect.titulos.create") }}</h3>
<p>{{ TransArticle::get("proyect.prueba2") }}</p>
@if ($preDatos)
<div class="row">
    <div class='row-sm-height'>
    <div class="col-sm-6 col-sm-height">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $enterprise->nickname }}</h3> 
                <h6>{{ $enterprise->fullname }}</h6>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("enterprise.selects.type.".$enterprise->type) }}</strong>
                <p>{{ $enterprise->description }}</p>
                <strong>{{ Lang::get("proyect.labels.teams") }}:</strong>
                <p>
                    @foreach ($enterprise->teams()->get() as $team)
                    <a href="{{ URL::route(Lang::get("principal.menu.links.equipo"). '.show', array($team->id)) }}">{{ $team->name }}</a>, 
                    @endforeach
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-sm-height">
        <div class="panel panel-default">
            <div class="panel-body">
                <img class="img-responsive img-rounded" src="{{ asset("images/enterprises/" . $enterprise->logo) }}"
            </div>
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