@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.payment'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.payment.campos'), array('paymentdate'));
$configTareas = array_except(Config::get('crudgen.task'), array('campos', 'botones'));
$configTareas['campos'] = array_only(Config::get('crudgen.task.campos'), array('priority', 'name', 'code', 'state', 'contribution', 'dpercentage', 'game_id', 'tasktype', 'description', 'dificulty', 'start', 'end'));
$configTareas['botones'] = $configBotonesActividades;
$configIndicadores = array_except(Config::get('crudgen.indicator'), array('campos', 'botones'));
$configIndicadores['campos'] = array_only(Config::get('crudgen.indicator.campos'), array('priority', 'name', 'type', 'state', 'description', 'fuente', 'user_id'));
$configIndicadores['botones'] = $configBotonesIndicadores;
$configRiesgos = array_except(Config::get('crudgen.risk'), array('campos', 'botones'));
$configRiesgos['campos'] = array_only(Config::get('crudgen.risk.campos'), array('probability', 'name', 'type', 'state', 'description', 'impact', 'importance', 'detect'));
$configRiesgos['botones'] = $configBotonesRiesgos;
?>

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id)) }}">{{ $payment->proyect->name }}</a></li>
  <li class="active">{{ $payment->name }}</li>
</ol>
<h1>{{ Lang::get("payment.titulos.show") }}</h1>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $payment->proyect->name }}</h3> <h6>{{  $payment->proyect->code }}</h6>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("proyect.selects.priority.". $payment->proyect->priority) }}</strong>
                <br>
                <strong>{{ Lang::get("proyect.labels.state") }}:</strong> {{ Lang::get("proyect.selects.state.". $payment->proyect->state) }}
                <p>{{  $payment->proyect->description }}</p>
                <strong>{{ Lang::get("proyect.labels.teams") }}:</strong>
                <p>
                    @foreach ( $payment->proyect->teams()->get() as $team)
                    <a href="{{ URL::route(Lang::get("principal.menu.links.equipo"). '.show', array($team->id)) }}">{{ $team->name }}</a>, 
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>
<div class='container'>
    {{ CrudLoader::show($config,$payment->id,$payment) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("indicator.titulos.index") }}</h2>
    @if ($botonCrearIndicadores)
        <a href='{{ action('IndicatorsController@create') }}?py={{ $payment->id }}' class='btn btn-info' >{{ Lang::get("indicator.labels.create") }}</a>
    @endif
    {{ CrudLoader::lists($configIndicadores,$payment->indicators()->get()) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("risk.titulos.index") }}</h2>
    @if ($botonCrearRiesgos)
        <a href='{{ action('RisksController@create') }}?py={{ $payment->id }}' class='btn btn-info' >{{ Lang::get("risk.labels.create") }}</a>
    @endif
    {{ CrudLoader::lists($configRiesgos,$payment->risks()->get()) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("task.titulos.index") }}</h2>
    <div id='pie_tasks_per'></div>
    @piechart('tasks_per', 'pie_tasks_per')
    @if ($botonCrearActividades)
        <a href='{{ action('TasksController@create') }}?py={{ $payment->id }}&pr={{ $payment->proyect_id }}' class='btn btn-info' >{{ Lang::get("task.labels.create") }}</a>
    @endif
    {{ CrudLoader::lists($configTareas,$payment->tasks()->get()) }}
</div>
@stop

@section("selfjs")
<script>
    $(document).ready(function() {
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop