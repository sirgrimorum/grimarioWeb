<?php
$config = array_except(Config::get('crudgen.indicator'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.indicator.campos'), array(''));
$config["campos"]["state"]["tipo"] = "hidden";
$config["campos"]["user_id"]["tipo"] = "hidden";
$config["campos"]["user_id"]["valor"] = $user->id;
$preDatos = false;
if (Input::has('py')) {
    $preDatos = true;
    $config["campos"]["payment_id"]["tipo"] = "hidden";
    $config["campos"]["payment_id"]["valor"] = $payment->id;
}
?>
@extends("layouts.principal")

@section("contenido")
@if ($preDatos)
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id)) }}">{{ $payment->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id)) }}">{{ $payment->name }}</a></li>
  <li class="active">{{ Lang::get("indicator.titulos.create") }}</li>
</ol>
@endif
<h1>{{ Lang::get("indicator.titulos.create") }}</h3>
<p>{{ TransArticle::get("indicator.prueba2") }}</p>
@if ($preDatos)
<div class="row">
    <div class="col-sm-6">
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
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $payment->name }}</h3>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("payment.labels.percentage") }}:</strong> {{ $payment->percentage }}
                <br>
                <strong>{{ Lang::get("payment.labels.value") }}:</strong> {{ $payment->value }}
                <br>
                <strong>{{ Lang::get("payment.labels.state") }}:</strong> {{ Lang::get("payment.selects.state.".$payment->state) }}
                <p>{{ $payment->conditions }}</p>
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