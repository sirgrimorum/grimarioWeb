<?php
$configShow = array_except(Config::get('crudgen.task'), array('campos'));
$configShow['campos'] = array_except(Config::get('crudgen.task.campos'), array('end', 'users', 'dcuantity'));
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
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id)) }}">{{ $task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id)) }}">{{ $task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id)) }}">{{ $task->name }}</a></li>
  <li class="active">{{ Lang::get("task.titulos.actualizar") }}</li>
</ol>
<h1>{{ Lang::get("task.titulos.actualizar") }}</h1>
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
    {{ Form::hidden("work_id", $work->id, array('class' => 'form-control', 'id' => 'work_id')) }}
    {{ Form::hidden("state", $state, array('class' => 'form-control', 'id' => 'task_state')) }}
    {{ Form::hidden("prev", $task->state, array('class' => 'form-control', 'id' => 'task_prev')) }}

    <div class="form-group ">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                {{ Form::label('start', Lang::get('work.labels.date'), array('class'=>'')) }}
                <div class="">
                    @if ($errors->has("start"))
                    <div class="alert alert-danger">
                        {{ HTML::ul($errors->get("start")) }}
                    </div>
                    @endif
                    {{ Form::hidden('start', $work->start, array('class' => 'form-control', 'id' => 'work_start')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                {{ Form::label('end', Lang::get('work.labels.end'), array('class'=>'')) }}
                <div class="">
                    @if ($errors->has("end"))
                    <div class="alert alert-danger">
                        {{ HTML::ul($errors->get("end")) }}
                    </div>
                    @endif
                    {{ Form::hidden('end', date("Y-m-d H:i:s"), array('class' => 'form-control', 'id' => 'work_end')) }}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                {{ Form::label('users', Lang::get('work.labels.users'), array('class'=>'')) }}
                @if ($errors->has("users"))
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->get("users")) }}
                </div>
                @endif
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.users') }}
                </span>
                <table class="table table-striped table-bordered" id='list_users'>
                    <thead>
                        <tr>
                            <td></td>
                            <td>{{ Lang::get('work.labels.user') }}</td>
                            <td>{{ Lang::get('work.labels.user_responsability') }}</td>
                            <td>{{ Lang::get('work.labels.user_hours') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                {{ Form::checkbox("work_users[" . $usuario->id ."]", $usuario->id, false, array('class' => 'chbx_users', 'id' => 'work_users_' . $usuario->id)) }}
                            </td>
                            <td>
                                {{ $usuario->name }}
                            </td>
                            <td>
                                {{ $usuario->pivot->responsability }}
                            </td>
                            <td>
                                {{ Form::number("work_users_c_" . $usuario->id, "0", array('class' => 'form-control hour_users', 'id' => 'work_users_c_' . $usuario->id, 'step' => 'any', 'readonly')) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-xs-12 col-sm-3  col-sm-height recursos">
                {{ Form::label('machines', Lang::get('work.labels.machines'), array('class'=>'')) }}
                @if ($errors->has("machines"))
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->get("machines")) }}
                </div>
                @endif
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.machines') }}
                </span>
                <table class="table table-striped table-bordered" id='list_machines'>
                    <thead>
                        <tr>
                            <td></td>
                            <td>{{ Lang::get('work.labels.machines') }}</td>
                            <td>{{ Lang::get('work.labels.machines_hours') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maquinas as $maquina)
                        <tr>
                            <td>
                                {{ Form::checkbox("work_machines[" . $maquina->id ."]", $maquina->id, false, array('class' => 'chbx_machines', 'id' => 'work_machines_' . $maquina->id)) }}
                            </td>
                            <td>
                                {{ $maquina->name }}
                            </td>
                            <td>
                                {{ Form::number("work_machines_c_" . $maquina->id, "0", array('class' => 'form-control hour_machines', 'id' => 'work_machines_c_' . $maquina->id, 'step' => 'any', 'readonly')) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-xs-12 col-sm-3  col-sm-height recursos">
                {{ Form::label('resources', Lang::get('work.labels.resources'), array('class'=>'')) }}
                @if ($errors->has("resources"))
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->get("resources")) }}
                </div>
                @endif
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.resources') }}
                </span>
                <table class="table table-striped table-bordered" id='list_resources'>
                    <thead>
                        <tr>
                            <td></td>
                            <td>{{ Lang::get('work.labels.resources') }}</td>
                            <td>{{ Lang::get('work.labels.resources_cant') }}</td>
                            <td>{{ Lang::get('work.labels.resources_unit') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recursos as $recurso)
                        <tr>
                            <td>
                                {{ Form::checkbox("work_resources[" . $recurso->id ."]", $recurso->id, false,array('class' => 'chbx_resources', 'id' => 'work_resources_' . $recurso->id)) }}
                            </td>
                            <td>
                                {{ $recurso->name }}
                            </td>
                            <td>
                                {{ Form::number("work_resources_c_" . $recurso->id, "0", array('class' => 'form-control cant_resources', 'id' => 'work_resources_c_' . $recurso->id, 'step' => 'any','readonly')) }}
                            </td>
                            <td>
                                {{ $recurso->measure }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="boton_centrado">
            @if (Input::get('st')=='ter')
            {{ Form::hidden("dpercentage", 100, array('class' => 'form-control', 'id' => 'dpercentage')) }}
            @else
            {{ Form::label('dpercentage', Lang::get('task.labels.dpercentage'), array('class'=>'')) }}<br/>
            {{ Form::text("dpercentage", $task->dpercentage, array('class' => 'form-control ', 'id' => 'dpercentage', 'data-slider-id'=>'dpercentageSlider', 'data-slider-min'=>"0", 'data-slider-max'=>"100", 'data-slider-step'=>"5", 'data-slider-value'=>$task->dpercentage)) }}
            @endif
        </div>
    </div>
    <div class="form-group">
        <div class="boton_centrado">
            @if (Input::get('st')=='ter' && ($user->inGroup(Sentry::findGroupByName('Coordinador')) || $user->inGroup(Sentry::findGroupByName('Director'))))
            {{ Form::submit(Lang::get('task.labels.finalizar'), array('class' => 'btn btn-primary', 'name' => 'formaction')) }}
            @else
            {{ Form::submit(Lang::get('task.labels.detener'), array('class' => 'btn btn-primary', 'name' => 'formaction')) }}
            @if ($user->inGroup(Sentry::findGroupByName('Coordinador')) || $user->inGroup(Sentry::findGroupByName('Director')))
            {{ Form::submit(Lang::get('task.labels.finalizar'), array('class' => 'btn btn-primary', 'name' => 'formaction')) }}
            @endif
            @endif
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
    <a href='{{ action('CostsController@create') }}?wk={{ $work->id }}&tk={{ $task->id }}' class='btn btn-info' >{{ Lang::get("cost.labels.create") }}</a>
    {{ CrudLoader::lists($configCosts,$costs) }}
</div>

@stop

@section('selfcss')
@parent
{{ HTML::style("css/bootstrap-datetimepicker.min.css") }}
@if (Input::get('st')!='ter')
{{ HTML::style("css/bootstrap-slider.css") }}
@endif
@stop

@section("selfjs")
@parent
{{ HTML::script("js/moment-with-locales.min.js") }}
{{ HTML::script("js/bootstrap-datetimepicker.min.js") }}
@if (Input::get('st')!='ter')
{{ HTML::script("js/bootstrap-slider.js") }}
@endif
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
        $('#work_start').datetimepicker({
            locale: '{{ App::getLocale() }}',
            inline: true,
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true
        });
        $('#work_end').datetimepicker({
            locale: '{{ App::getLocale() }}',
            inline: true,
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true
        });
        $("input[type=checkbox]").change(function() {
            var idTemp = "#" + $(this).attr("name").substring(0, $(this).attr("name").indexOf("[")) + "_c_" + $(this).val();
            console.log(idTemp);
            $(idTemp).prop("readonly", !$(this).is(":checked"));
            if ($(idTemp).is("[readonly]")) {
                $(idTemp).val(0);
            }
        });
        @if (Input::get('st') != 'ter')
                $('#dpercentage').sliderb({
            formatter: function(value) {
                return value + "%";
            }
        });
        @endif
    });
</script>
@stop
