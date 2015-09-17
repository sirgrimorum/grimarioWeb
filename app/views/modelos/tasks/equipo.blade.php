<?php
$configShow = array_except(Config::get('crudgen.task'), array('campos'));
$configShow['campos'] = array_except(Config::get('crudgen.task.campos'), array('end', 'users','dcuantity'));

?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($task->proyect->id)) }}">{{ $task->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id)) }}">{{ $task->payments()->first()->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id)) }}">{{ $task->name }}</a></li>
  <li class="active">{{ Lang::get("task.titulos.equipo") }}</li>
</ol>
<h1>{{ Lang::get("task.titulos.equipo") }}</h1>
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
    {{ Form::hidden("act_equipo", "actualizar", array('class' => 'form-control', 'id' => 'task_act_equipo')) }}

    <div class="form-group ">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-12 col-sm-height">
                {{ Form::label('users', Lang::get('task.labels.users'), array('class'=>'')) }}
                @if ($errors->has("users"))
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->get("users")) }}
                </div>
                @endif
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('task.descriptions.users') }}
                </span>
                <table class="table table-striped table-bordered" id='list_users'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('task.labels.user') }}</td>
                            <td>{{ Lang::get('task.labels.users_responsability') }}</td>
                            @if ($user->inGroup(Sentry::findGroupByName('Coordinador')))
                            <td>{{ Lang::get('task.labels.users_valueph') }}</td>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                {{ $usuario->name }}
                            </td>
                            <td>
                                {{ Form::text("task_users_r_" . $usuario->id, $usuario->pivot->responsability, array('class' => 'form-control hour_users', 'id' => 'work_users_r_' . $usuario->id)) }}
                            </td>
                            @if ($user->inGroup(Sentry::findGroupByName('Coordinador')))
                            <td>
                                {{ Form::number("task_users_v_" . $usuario->id, $usuario->pivot->valueph, array('class' => 'form-control hour_users', 'id' => 'work_users_v_' . $usuario->id)) }}
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="boton_centrado">
            {{ Form::submit(Lang::get('task.labels.actualizar_equipo'), array('class' => 'btn btn-primary')) }}
        </div>
    </div>
</div>
{{ Form::close() }}

@stop

@section('selfcss')
@parent
@stop

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
    });
</script>
@stop
