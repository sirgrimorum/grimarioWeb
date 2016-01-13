<?php
$config = array_except(Config::get('crudgen.task'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.task.campos'), array( 'dcuantity', 'contribution')); //array('start', 'end', 'dcuantity', 'contribution'));
$config['botones'] = [
    Form::submit(Lang::get('task.labels.edit'), array('class' => 'btn btn-primary', 'name' => 'formaction'))
];
$config['url'] = action('TasksController@update', [$task->id]);
$proyect = $task->proyect;
$payment = $task->payments()->first();
$config["campos"]["proyect_id"]["tipo"] = "hidden";
$config["campos"]["proyect_id"]["valor"] = $proyect->id;
$config["campos"]["payments"]["tipo"] = "hidden";
$config["campos"]["payments"]["valor"] = $payment->id;
$config["campos"]["game_id"]["todos"] = $juegos;
$config["campos"]["users"]["todos"] = $usuarios;
if ($task->tasktype){
    $config["campos"]["tasktype"]["valor"] = $task->tasktype->id;
}
//$config["campos"]["name"]["readonly"]="readonly";
//$config["campos"]["code"]["readonly"]="readonly";
unset($config["campos"]["state"]["valor"]);
unset($config["campos"]["difficulty"]["valor"]);
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id)) }}">{{ $task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id)) }}">{{ $task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id)) }}">{{ $task->name }}</a></li>
  <li class="active">{{ Lang::get("task.titulos.edit") }}</li>
</ol>
<h1>{{ Lang::get("task.titulos.edit") }}</h3>
<p>{{ TransArticle::get("task.prueba2") }}</p>
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
<div class='container'>
    {{ CrudLoader::edit($config,$task->id,$task) }}
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