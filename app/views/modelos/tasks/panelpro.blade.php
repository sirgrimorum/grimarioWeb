<?php
if (Input::has("tk")) {
    $task = Task::find(Input::get("tk"));
}
?>
<div class="row">
    <div class="col-xs-3 col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            @foreach (Tasktype::all() as $tasktype)
            @if ($payment->tasks()->where("type","=",$tasktype->id)->count()>0)
            @if ($tasktype->id == Input::get("tt"))
            <li role="presentation" class="active">
                @else
            <li role="presentation">
                @endif
                <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id)) }}?py={{ $payment->id }}&tt={{ $tasktype->id }}#task">
                    {{ $tasktype->name }}
                    <span class="badge">{{ $payment->tasks()->where("type","=",$tasktype->id)->count() }}</span>
                </a>
            </li>
            @endif
            @endforeach
        </ul>
    </div>
    <div class="col-xs-6 col-sm-8">
        <div class="contenidopanel">
            @if (Input::has("tk"))
            <div class="progress">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{ $task->dpercentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $task->dpercentage }}%;">
                    {{ Lang::get("task.labels.dpercentage") }}: {{ $task->dpercentage }}%
                </div>
            </div>
            <h1>
                {{ $task->name }} 
                <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseMasinfoTk" aria-expanded="false" aria-controls="collapseMasinfoTk">
                    {{ Lang::get("principal.labels.mas_info") }}
                </button>
            </h1>
            <h2>
                <small class="estado">
                    {{ Lang::get("task.selects.state." . $task->state) }}
                </small>
                @if ($task->state == 'ent' || $task->state == 'cer')
                @if ($task->timeleft() < 0)
                <span class="label label-danger">{{ Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") }}</span>
                @elseif ($task->timeleft() < 2)
                <span class="label label-warning">{{ Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") }}</span>
                @else
                <span class="label label-success">{{ Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") }}</span>
                @endif
                @else
                @if ($task->timeleft() < 0)
                <span class="label label-danger">{{ Lang::get("task.labels.retrasado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias") }}</span>
                @elseif ($task->timeleft() < 2)
                <span class="label label-warning">{{ Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias") }}</span>
                @else
                <span class="label label-success">{{ Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias") }}</span>
                @endif
                @endif
            </h2>
            @include("modelos.tasks.paneltask")
            @else
            @if (Input::has("tt"))
            <h2>{{ Lang::get("task.titulos.index") }}</h2>
            {{ CrudLoader::lists($configTareas,$payment->tasks()->where("type","=",Input::get("tt"))->get()) }}
            @else
            {{ CrudLoader::show($config,$payment->id,$payment) }}
            @endif
            @endif
        </div>
    </div>
    <div class="col-xs-3 col-sm-2">
        <ul class="nav nav-pills nav-stacked">
            @foreach ($payment->tasks()->where("type","=",Input::get("tt"))->get() as $tasklist)
            @if ($tasklist->id == Input::get("tk"))
            <li role="presentation" class="active">
                @else
            <li role="presentation">
                @endif
                <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id)) }}?py={{ $payment->id }}&tt={{ Input::get("tt") }}&tk={{ $tasklist->id }}#task">
                    {{ $tasklist->name }}
                    <span class="badge">{{ $tasklist->works()->count() }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
    
    });
</script>
@stop