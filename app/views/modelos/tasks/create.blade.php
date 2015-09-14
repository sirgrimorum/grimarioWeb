<?php
$config = array_except(Config::get('crudgen.task'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.task.campos'), array('start', 'end', 'dcuantity', 'contribution', 'dpercentage'));
$config["campos"]["state"]["tipo"] = "hidden";
$preDatos = false;
if (Input::has('pr') && Input::has('py')) {
    $preDatos = true;
    $config["campos"]["proyect_id"]["tipo"] = "hidden";
    $config["campos"]["proyect_id"]["valor"] = $proyect->id;
    $config["campos"]["payments"]["tipo"] = "hidden";
    $config["campos"]["payments"]["valor"] = $payment->id;
    $config["campos"]["game_id"]["todos"] = $juegos;
    $config["campos"]["users"]["todos"] = $usuarios;
    $otrasTareas = Task::where("proyect_id", "=", $proyect->id)->get()->filter(function($task) {
                if ($task->payments->first()->id == Input::has('pr')) {
                    return true;
                }
            });
    $config["campos"]["order"]["valor"] = $otrasTareas->count()+1;
}
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("task.titulos.create") }}</h3>
<p>{{ TransArticle::get("task.prueba2") }}</p>
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