<?php
$configTask = array_except(Config::get('crudgen.task'), array('campos'));
$configTask['campos'] = array_except(Config::get('crudgen.task.campos'), array('end', 'users'));
$configShow = Config::get('crudgen.work');
$configShow['campos']['totalcost'] = [
    "tipo"=>"function",
    "label"=>Lang::get("work.labels.totalcost"),
];
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("work.titulos.show") }}</h1>
<p>{{ TransArticle::get("work.prueba2") }}</p>
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
    <h3>{{ Lang::get("task.labels.tarea") }}</h3>
    {{ CrudLoader::show($configTask,$task->id,$task) }}
</div>
<div class='container'>
    <h3>{{ Lang::get("work.labels.trabajo") }}</h3>
    {{ CrudLoader::show($configShow,$work->id,$work) }}
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
    <div class="row ">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                <h4>{{ Lang::get('work.labels.users') }}</h4>
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.users') }}
                </span>
                <table class="table table-striped table-bordered" id='list_users'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('work.labels.user') }}</td>
                            <td>{{ Lang::get('work.labels.user_responsability') }}</td>
                            <td>{{ Lang::get('work.labels.user_hours') }}</td>
                            <td>{{ Lang::get('work.labels.user_cost') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                {{ $usuario->name }}
                            </td>
                            <td>
                                {{ $task->users()->where("users.id","=",$usuario->id)->first()->pivot->responsability }}
                            </td>
                            <td>
                                {{ $usuario->pivot->hours }}
                            </td>
                            <td>
                                 {{ ( $usuario->pivot->hours * $task->users()->where("users.id","=",$usuario->id)->first()->pivot->valueph ) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-height">
                <h4>{{ Lang::get('work.labels.machines') }}</h4>
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.machines') }}
                </span>
                <table class="table table-striped table-bordered" id='list_machines'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('work.labels.machines') }}</td>
                            <td>{{ Lang::get('work.labels.machines_hours') }}</td>
                            <td>{{ Lang::get('work.labels.machines_cost') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maquinas as $maquina)
                        <tr>
                            <td>
                                {{ $maquina->name }}
                            </td>
                            <td>
                                {{ $maquina->pivot->hours }}
                            </td>
                            <td>
                                {{ ($maquina->pivot->hours * $maquina->valueph) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class='row-sm-height'>
            <div class="col-xs-12 col-sm-6  col-sm-height recursos">
                <h4>{{ Lang::get('work.labels.resources') }}</h4>
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.resources') }}
                </span>
                <table class="table table-striped table-bordered" id='list_resources'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('work.labels.resources') }}</td>
                            <td>{{ Lang::get('work.labels.resources_cant') }}</td>
                            <td>{{ Lang::get('work.labels.resources_unit') }}</td>
                            <td>{{ Lang::get('work.labels.resources_cost') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recursos as $recurso)
                        <tr>
                            <td>
                                {{ $recurso->name }}
                            </td>
                            <td>
                                {{ $recurso->pivot->cuantity }}
                            </td>
                            <td>
                                {{ $recurso->measure }}
                            </td>
                            <td>
                                {{ ($recurso->pivot->cuantity * $recurso->value) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-xs-12 col-sm-6  col-sm-height recursos">
                <h4>{{ Lang::get('work.labels.costs') }}</h4>
                <span class="help-block" id="work_end_help">
                    {{ Lang::get('work.descriptions.costs') }}
                </span>
                <table class="table table-striped table-bordered" id='list_resources'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('cost.labels.date') }}</td>
                            <td>{{ Lang::get('cost.labels.type') }}</td>
                            <td>{{ Lang::get('cost.labels.rubro') }}</td>
                            <td>{{ Lang::get('cost.labels.description') }}</td>
                            <td>{{ Lang::get('cost.labels.value') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($costs as $costo)
                        <tr>
                            <td>
                                {{ $costo->date }}
                            </td>
                            <td>
                                {{ Lang::get('cost.selects.type.' . $costo->type) }}
                            </td>
                            <td>
                                {{ Lang::get('cost.selects.rubro.' . $costo->rubro) }}
                            </td>
                            <td>
                                {{ $costo->description }}
                            </td>
                            <td>
                                {{ $costo->value }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('selfcss')

@stop

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
    });
</script>
@stop
