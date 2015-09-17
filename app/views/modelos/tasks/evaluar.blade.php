<?php
$configShow = array_except(Config::get('crudgen.task'), array('campos'));
$configShow['campos'] = array_except(Config::get('crudgen.task.campos'), array('end', 'users'));
$configShow['campos']['othercosts'] = [
    "tipo"=>"function",
    "label"=>Lang::get("task.labels.othercosts"),
];
$configShow['campos']['profit'] = [
    "tipo"=>"function",
    "label"=>Lang::get("task.labels.profit"),
];
$configShow['campos']['totalcost'] = [
    "tipo"=>"function",
    "label"=>Lang::get("task.labels.totalcost"),
];
$configComments = array_except(Config::get('crudgen.comment'), array('campos'));
$configComments['campos'] = array_except(Config::get('crudgen.comment.campos'), array('task_id'));
$configComments['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.show', array("{ID}")) . "'>" . Lang::get("comment.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.edit', array("{ID}")) . "'>" . Lang::get("comment.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.destroy', array("{ID}")) . "'>" . Lang::get("comment.labels.eliminar") . "</a>",
];
$configCosts = array_except(Config::get('crudgen.cost'), array('campos'));
$configCosts['campos'] = array_except(Config::get('crudgen.cost.campos'), array('code', 'user_id', 'work_id'));
$configCosts['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.costo") . '.show', array("{ID}")) . "'>" . Lang::get("cost.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.costo") . '.edit', array("{ID}")) . "'>" . Lang::get("cost.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.costo") . '.destroy', array("{ID}")) . "'>" . Lang::get("cost.labels.eliminar") . "</a>",
];
$configIndicadores = array_except(Config::get('crudgen.indicator'), array('campos', 'botones'));
$configIndicadores['campos'] = array_only(Config::get('crudgen.indicator.campos'), array('priority', 'name', 'type', 'state', 'description', 'fuente', 'user_id'));
$configIndicadores['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")) . "'>" . Lang::get("indicator.labels.ver") . "</a>",
    //"<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.edit', array("{ID}")) . "?tk=" . $task->id . "&st=" . $state . "'>" . Lang::get("indicator.labels.editar") . "</a>",
];
$configRestriccion = array_except(Config::get('crudgen.restriction'), array('campos', 'botones'));
$configRestriccion['campos'] = array_only(Config::get('crudgen.restriction.campos'), array('probability', 'name', 'type', 'state', 'date', 'description', 'comments', 'user_id'));
$configRestriccion['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")) . "'>" . Lang::get("restriction.labels.ver") . "</a>",
    //"<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.edit', array("{ID}")) . "?tk=" . $task->id . "&st=" . $state . "'>" . Lang::get("restriction.labels.editar") . "</a>",
];
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id)) }}">{{ $task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id)) }}">{{ $task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id)) }}">{{ $task->name }}</a></li>
  <li class="active">{{ Lang::get("task.labels.evaluar") }}</li>
</ol>
<h1>{{ Lang::get("task.titulos.edit") }}</h1>
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
    {{ CrudLoader::show($configShow,$task->id,$task) }}
</div>
<div class='container'>
    <?php $errores = false ?>
    @if (count($errors->all())>0)
    <?php $errores = true ?>
    @if (isset($config['render']))
    <div class="alert alert-danger">
        {{ HTML::ul($errors->all()) }}
    </div>
    @endif
    @endif
    {{ Form::model($task, array('route' => array(Lang::get("principal.menu.links.tarea") . '.update', $task->id), 'class' => '', 'method' => 'PUT')) }}
    {{ Form::hidden("task_id", $task->id, array('class' => 'form-control', 'id' => 'task_id')) }}
    {{ Form::hidden("state", $state, array('class' => 'form-control', 'id' => 'task_state')) }}
    {{ Form::hidden("prev", $task->state, array('class' => 'form-control', 'id' => 'task_prev')) }}

    <div class="form-group ">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                {{ Form::label('satisfaction', Lang::get('task.labels.satisfaction'), array('class'=>'')) }}
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('task.descriptions.satisfaction') }}
                </span>
                {{ Form::select("satisfaction", Lang::get("task.selects.satisfaction"), 0, array('class' => 'form-control ', 'id' => 'satisfaction')) }}
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                {{ Form::label('cuality', Lang::get('task.labels.cuality'), array('class'=>'')) }}
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('task.descriptions.cuality') }}
                </span>
                {{ Form::select("cuality", Lang::get("task.selects.cuality"), 0, array('class' => 'form-control ', 'id' => 'cuality')) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="boton_centrado">
            {{ Form::submit(Lang::get('task.labels.entregar'), array('class' => 'btn btn-primary', 'name' => 'formaction')) }}
        </div>
    </div>
</div>
{{ Form::close() }}

<div class="container">
    {{ Form::label('comments', Lang::get('work.labels.comments'), array('class'=>'')) }}
    <span class="help-block" id="work_end_help">
        {{ Lang::get('work.descriptions.comments') }}
    </span>
    <a href='{{ action('CommentsController@create') }}?wk={{ $work->id }}&tk={{ $task->id }}' class='btn btn-info' >{{ Lang::get("comment.labels.create") }}</a>
    {{ CrudLoader::lists($configComments,$comments) }}
</div>

<div class="container">
    {{ Form::label('costs', Lang::get('work.labels.costs'), array('class'=>'')) }}
    <span class="help-block" id="work_end_help">
        {{ Lang::get('work.descriptions.costs') }}
    </span>
    <a href='{{ action('CostsController@create') }}?tk={{ $task->id }}' class='btn btn-info' >{{ Lang::get("cost.labels.create") }}</a>
    {{ CrudLoader::lists($configCosts,$costs) }}
</div>
<div class='container'>
    {{ Form::label('costs', Lang::get('indicator.titulos.index'), array('class'=>'')) }}
    {{ CrudLoader::lists($configIndicadores,$payment->indicators()->get()) }}
</div>
<div class='container'>
    {{ Form::label('costs', Lang::get('restriction.titulos.index'), array('class'=>'')) }}
    <span class="help-block" id="work_end_help">
        {{ Lang::get('proyect.descriptions.restrictions') }}
    </span>
    <!--a href='{{ action('RestrictionsController@create') }}?pr={{ $proyect->id }}&tk={{ $task->id }}&st={{ $state }}' class='btn btn-info' >{{ Lang::get("restriction.labels.create") }}</a-->
    {{ CrudLoader::lists($configRestriccion,$proyect->restrictions()->get()) }}
</div>
@stop

@section('selfcss')
{{ HTML::style("css/bootstrap-datetimepicker.min.css") }}
@stop

@section("selfjs")
{{ HTML::script("js/moment-with-locales.min.js") }}
{{ HTML::script("js/bootstrap-datetimepicker.min.js") }}
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
        $('#work_end').datetimepicker({
            locale: '{{ App::getLocale() }}',
            inline: true,
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true
        });
        $("input[type=checkbox]").change(function() {
            var idTemp = "#" + $(this).attr("name").substring(0,$(this).attr("name").indexOf("[")) + "_c_" + $(this).val();
            console.log(idTemp);
            $(idTemp).prop("readonly", !$(this).is(":checked"));
            if ($(idTemp).is("[readonly]")) {
                $(idTemp).val(0);
            }
        });
    });
</script>
@stop
