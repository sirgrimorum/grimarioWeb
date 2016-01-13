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

if (Input::has("py")) {
    $payment = Payment::find(Input::get("py"));
} else {
    $payment = $proyect->payments()->first();
}
?>

<ul class="nav nav-pills">
    @foreach ($proyect->payments()->get() as $paymentList)
    @if ($payment->id == $paymentList->id)
    <li role="presentation" class="active">
        @else
    <li role="presentation">
        @endif
        <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($paymentList->proyect->id)) }}?py={{ $paymentList->id }}#payment">
            {{ $paymentList->name }}
            @if ($paymentList->state == 'act')
            <span class="badge"><span class="glyphicon glyphicon-expand" aria-hidden="true"></span></span>
            @elseif ($paymentList->state == 'ent')
            <span class="badge"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></span>
            @elseif ($paymentList->state == 'pag')
            <span class="badge"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></span>
            @else
            <span class="badge"><span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span></span>
            @endif
            @if ($paymentList->state!='pag' && strtotime($paymentList->plandate)< time())
            <span class="glyphicon glyphicon-warning-sign text-danger" aria-hidden="true"></span>
            @endif
        </a>
    </li>
    @endforeach
</ul>
@if ($payment)
<div class="panel panel-default">
    <div class="panel-body">
        <div id="avancepybar"><div class="progress-label">{{ Lang::get("payment.labels.advance") }}: {{ $payment->advance() }}%</div></div>
        <a name="task"></a>
        <h1>
            {{ $payment->name }} 
            <small class="estado">{{ Lang::get("payment.selects.state." . $payment->state) }}</small>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseMasinfoPy" aria-expanded="false" aria-controls="collapseMasinfoPy">
                {{ Lang::get("principal.labels.mas_info") }}
            </button>
        </h1>
        <div class="collapse" id="collapseMasinfoPy">
            {{ CrudLoader::show($config,$payment->id,$payment) }}
        </div>
        <div class="panel-group" id="accordionPy" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="LabIndicadores">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionPy" href="#TabIndicadores" aria-expanded="false" aria-controls="collapseTwo">
                            {{ Lang::get("indicator.titulos.index") }}
                        </a>
                        @if ($botonCrearIndicadores )
                        <a class='pull-right' href='{{ action('IndicatorsController@create') }}?py={{ $payment->id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("indicator.labels.create") }}</a>
                        @endif
                    </h4>
                </div>
                <div id="TabIndicadores" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabIndicadores">
                    <div class="panel-body">
                        {{ CrudLoader::lists($configIndicadores,$payment->indicators()->get()) }}
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="LabRiesgos">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionPy" href="#TabRiesgos" aria-expanded="false" aria-controls="TabRiesgos">
                            {{ Lang::get("risk.titulos.index") }}
                        </a>
                        @if ($botonCrearRiesgos)
                        <a class='pull-right' href='{{ action('RisksController@create') }}?py={{ $payment->id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("risk.labels.create") }}</a>
                        @endif
                    </h4>
                </div>
                <div id="TabRiesgos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabRiesgos">
                    <div class="panel-body">
                        {{ CrudLoader::lists($configRiesgos,$payment->risks()->get()) }}
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="LabTasks">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordionPy" href="#TabTasks" aria-expanded="true" aria-controls="TabTasks">
                            {{ Lang::get("task.titulos.index") }}
                        </a>
                        @if ($botonCrearActividades)
                        <a class='pull-right' href='{{ action('TasksController@create') }}?py={{ $payment->id }}&pr={{ $payment->proyect_id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("task.labels.create") }}</a>
                        @endif
                    </h4>
                </div>
                <div id="TabTasks" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="LabTasks">
                    <div class="panel-body">
                        @include("modelos.tasks.panelpro")
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
        $("#avancepybar").progressbar({
        value: {{ $payment -> advance() }}
    });
    });
</script>
@stop
@endif
