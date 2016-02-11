<?php
$config = array_except(Config::get('crudgen.payment'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.payment.campos'), array('paymentdate', 'totalcost', 'profit', 'advance', 'saves', 'totalhours', 'saveshours', 'planh', 'plan'));
$config["campos"]["state"]["tipo"] = "hidden";
$preDatos = false;
if (Input::has('pr')) {
    $preDatos = true;
    $config["campos"]["proyect_id"]["tipo"] = "hidden";
    $config["campos"]["proyect_id"]["valor"] = $proyect->id;
}
?>
@extends("layouts.principal")

@section("contenido")
@if ($preDatos)
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id)) }}">{{ $proyect->name }}</a></li>
  <li class="active">{{ Lang::get("payment.titulos.create") }}</li>
</ol>
@endif
<h1>{{ Lang::get("payment.titulos.create") }}</h3>
<p>{{ TransArticle::get("payment.prueba2") }}</p>
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
@parent
<script>
    $(document).ready(function() {
        //alert(translations.payment.error);
    });
</script>
@stop

@section("selfcss")
@parent
<!--{{ HTML::style("css/acerca.css") }} -->
@stop