@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.payment'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.payment.campos'), array('paymentdate'));
$configTareas = array_except(Config::get('crudgen.task'), array('campos', 'botones'));
$configTareas['campos'] = array_only(Config::get('crudgen.task.campos'), array('priority', 'name', 'code', 'state', 'contribution', 'dpercentage', 'game_id', 'tasktype', 'description', 'dificulty', 'start', 'end'));
$configTareas['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.show', array("{ID}")) . "'>" . Lang::get("task.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array("{ID}")) . "'>" . Lang::get("task.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.destroy', array("{ID}")) . "'>" . Lang::get("task.labels.eliminar") . "</a>",
];
$configIndicadores = array_except(Config::get('crudgen.indicator'), array('campos', 'botones'));
$configIndicadores['campos'] = array_only(Config::get('crudgen.indicator.campos'), array('priority', 'name', 'type', 'state', 'description', 'fuente', 'user_id'));
$configIndicadores['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")) . "'>" . Lang::get("indicator.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.edit', array("{ID}")) . "'>" . Lang::get("indicator.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.destroy', array("{ID}")) . "'>" . Lang::get("indicator.labels.eliminar") . "</a>",
];
$configRiesgos = array_except(Config::get('crudgen.risk'), array('campos', 'botones'));
$configRiesgos['campos'] = array_only(Config::get('crudgen.risk.campos'), array('probability', 'name', 'type', 'state', 'description', 'impact', 'importance', 'detect'));
$configRiesgos['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.show', array("{ID}")) . "'>" . Lang::get("risk.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.edit', array("{ID}")) . "'>" . Lang::get("risk.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.destroy', array("{ID}")) . "'>" . Lang::get("risk.labels.eliminar") . "</a>",
];
?>

@section("contenido")

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
    <a href='{{ action('IndicatorsController@create') }}?py={{ $payment->id }}' class='btn btn-info' >{{ Lang::get("indicator.labels.create") }}</a>
    {{ CrudLoader::lists($configIndicadores,$payment->indicators()->get()) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("risk.titulos.index") }}</h2>
    <a href='{{ action('RisksController@create') }}?py={{ $payment->id }}' class='btn btn-info' >{{ Lang::get("risk.labels.create") }}</a>
    {{ CrudLoader::lists($configRiesgos,$payment->risks()->get()) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("task.titulos.index") }}</h2>
    <a href='{{ action('TasksController@create') }}?py={{ $payment->id }}&pr={{ $payment->proyect_id }}' class='btn btn-info' >{{ Lang::get("task.labels.create") }}</a>
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