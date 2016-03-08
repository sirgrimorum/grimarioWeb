<?php
$config = array_except(Config::get('crudgen.proyect'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.proyect.campos'), array('satisfaction','experience','advance', 'totalplan', 'totalcost', 'saves', 'value', 'profit', 'totalplanhours', 'totalhours', 'saveshours'));
$config['botones'] = Lang::get("proyect.labels.edit");
$config['url'] = action('ProyectsController@update', [$proyect->id]);
unset($config["campos"]["state"]["valor"]);
$config["campos"]["pop_nue"]=$config["campos"]["pop"];
$config["campos"]["pop_nue"]["label"]=Lang::get("proyect.labels.pop_nue");
$config["campos"]["pop"]["tipo"]="text";
$config["campos"]["pop"]["readonly"]="readonly";
$config["campos"]["name"]["readonly"]="readonly";
$config["campos"]["code"]["readonly"]="readonly";
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
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  @if ($proyect->enterprises()->first())
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.empresa") . '.show', array($proyect->enterprises()->first()->id)) }}">{{ $proyect->enterprises()->first()->nickname }}</a></li>
  @endif
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id)) }}">{{ $proyect->name }}</a></li>
  <li class="active">{{ Lang::get("proyect.titulos.edit") }}</li>
</ol>
<h1>{{ Lang::get("proyect.titulos.edit") }}</h3>
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
    {{ CrudLoader::edit($config,$proyect->id,$proyect) }}
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