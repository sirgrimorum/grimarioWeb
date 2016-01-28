<?php
$config = array_except(Config::get('crudgen.task'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.task.campos'), array('users', 'order', 'start', 'end'));
if ($task->state == 'des' || $task->state == 'pau') {
    $config['campos']['start'] = [
        "tipo" => "date",
        "label" => Lang::get("task.labels.start"),
        "placeholder" => Lang::get("task.placeholders.start"),
        "description" => Lang::get("task.descriptions.start"),
    ];
    $config['campos']['dcuantity']['tipo'] = "hidden";
}
if ($task->state == 'ent' || $task->state == 'ter' || $task->state == 'cer') {
    $config['campos']['othercosts'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.othercosts"),
        "pre" => "$",
        "post" => "COP",
        "format" => [0, ".", "."],
    ];
    $config['campos']['profit'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.profit"),
        "pre" => "$",
        "post" => "COP",
        "format" => [0, ".", "."],
    ];
    $config['campos']['totalcost'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.totalcost"),
        "pre" => "$",
        "post" => "COP",
        "format" => [0, ".", "."],
    ];
    $config['campos']['totalhours'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.totalhours"),
        "post" => Lang::get("principal.labels.hours"),
        "format" => [1, ".", "."],
    ];
}
if ($task->state == 'ent' || $task->state == 'cer') {
    $config['campos']['dcuantity']['tipo'] = "number";
}
if ($task->state == 'cer') {
    $config['campos']["satisfaction"] = [
        "tipo" => "select",
        "label" => Lang::get("task.labels.satisfaction"),
        "opciones" => Lang::get("task.selects.satisfaction"),
    ];
    $config['campos']["cuality"] = [
        "tipo" => "select",
        "label" => Lang::get("task.labels.cuality"),
        "opciones" => Lang::get("task.selects.cuality"),
    ];
}


$user = Sentry::getUser();
$work = $task->works()->where('user_id', '=', $user->id)->whereRaw("YEAR(end) = 0 and start < NOW()")->orderBy('start', 'desc')->first();
if ($work && $task->state == 'pau') {
    $task->state = 'des';
    $task->save();
}
?>
<div class="collapse" id="collapseMasinfoTk">
    {{ CrudLoader::show($config,$task->id,$task) }}
</div>
<div class="panel-group" id="accordionTk" role="tablist" aria-multiselectable="true">
    <div class="panel panel-warning">
        <div class="panel-heading" role="tab" id="LabWork">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionTk" href="#TabWork" aria-expanded="false" aria-controls="TabWork">
                    {{ Lang::get('task.labels.works') }}
                </a>
                <div class='botones pull-right'>
                    <ul class='nav nav-pills'>
                        @if ($task->state == 'pla')
                        <li role="presentation" class="active">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=des" alt="{{ Lang::get("task.labels.comenzar") }}">
                                <span class="glyphicon glyphicon-play" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.comenzar") }}</span>

                            </a>
                        </li>
                        @elseif ($task->state == 'pau' || ( $task->state == 'des'  && !($work)))

                        <li role="presentation" class='active'>
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=des" alt="{{ Lang::get("task.labels.reanudar") }}">
                                <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                                <span class="sr-only">{{ Lang::get("task.labels.reanudar") }}</span>
                            </a>
                        </li>
                        @if ($user->inGroup(Sentry::findGroupByName('Lider')) || $user->inGroup(Sentry::findGroupByName('Lider')))
                        <li role="presentation">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=ter" alt="{{ Lang::get("task.labels.finalizar") }}">
                                <span class="glyphicon glyphicon-stop" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.finalizar") }}</span>
                            </a>
                        </li>
                        @endif
                        <li role="presentation">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.trabajo"). '.create') }}?tk={{ $task->id }}" alt="{{ Lang::get("task.labels.planear_work") }}">
                                <span class="glyphicon glyphicon-pause" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.planear_work") }}</span>
                            </a>
                        </li>
                        @elseif ($task->state == 'des' || ($work))
                        <li role="presentation" class="disabled">
                            <a href="#">
                                {{ $work->elapsedtime() . Lang::get("work.labels.h")  }}
                                <span class="glyphicon glyphicon-time" aria-hidden="true"></span> 
                            </a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=pau" alt="{{ Lang::get("task.labels.detener") }}">
                                <span class="glyphicon glyphicon-pause" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.detener") }}</span>
                            </a>
                        </li>
                        @if ($user->inGroup(Sentry::findGroupByName('Lider')) || $user->inGroup(Sentry::findGroupByName('Lider')))
                        <li role="presentation">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=ter" alt="{{ Lang::get("task.labels.finalizar") }}">
                                <span class="glyphicon glyphicon-stop" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.finalizar") }}</span>
                            </a>
                        </li>
                        @endif
                        <li role="presentation">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.trabajo"). '.create') }}?tk={{ $task->id }}" alt="{{ Lang::get("task.labels.planear_work") }}">
                                <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.planear_work") }}</span>
                            </a>
                        </li>
                        @elseif ($task->state == 'ter' && $user->inGroup(Sentry::findGroupByName('Lider')))
                        <li role="presentation" class="active">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=ent" alt="{{ Lang::get("task.labels.entregar") }}">
                                <span class="glyphicon glyphicon-share" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.entregar") }}</span>
                            </a>
                        </li>
                        @elseif ($task->state == 'ent' && $user->inGroup(Sentry::findGroupByName('Lider')))
                        <li role="presentation" class="active">
                            <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=cer" alt="{{ Lang::get("task.labels.evaluar") }}">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 
                                <span class="sr-only">{{ Lang::get("task.labels.evaluar") }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </h4>
        </div>
        <div id="TabWork" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabWork">
            <div class="panel-body">
                <div class='list-group'>
                    @foreach ($task->works()->orderBy('id', 'DESC')->get() as $worklist)
                    <?php
                    if ($work) {
                        if ($worklist->id == $work->id) {
                            $siend = false;
                            $worklistactive = "active";
                        } else {
                            $siend = true;
                            $worklistactive = "";
                        }
                    } else {
                        $siend = false;
                        $worklistactive = "";
                    }
                    ?>
                    <a href='{{ URL::route(Lang::get("principal.menu.links.trabajo") . '.show', array($worklist->id)) }}' class='list-group-item {{ $worklistactive }}'>
                        <h4 class="list-group-item-heading">
                            {{ $worklist->name }}
                        </h4>
                        <p class="list-group-item-text">
                            <label>{{ Lang::get("work.labels.coordinator") }}:</label> {{ $worklist->coordinator->name }}<br/>
                            <label>{{ Lang::get("work.labels.start") }}:</label> {{ $worklist->start }}<br/>
                            @if ($siend)
                            <label>{{ Lang::get("work.labels.end") }}:</label> {{ $worklist->end }}<br/>
                            @endif
                            <label>{{ Lang::get("work.labels.totalworkedhours") }}:</label> {{ $worklist->totalworkedhours() }}<br/>
                            <label>{{ Lang::get("work.labels.totalcost") }}:</label> {{ number_format($worklist->totalcost(),0,".",".") }} COP<br/>
                        </p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabTeam">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionTk" href="#TabTeam" aria-expanded="false" aria-controls="TabTeam">
                    {{ Lang::get('task.labels.users') }}
                </a>
                @if ($user->inGroup(Sentry::findGroupByName('Lider')))
                <a class='pull-right' href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?equipo=act">{{ Lang::get("task.labels.edit_equipo") }}</a>
                @endif
            </h4>
        </div>
        <div id="TabTeam" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabTeam">
            <div class="panel-body">
                <table class="table table-striped table-bordered" id='list_users'>
                    <thead>
                        <tr>
                            <td>{{ Lang::get('task.labels.user') }}</td>
                            <td>{{ Lang::get('task.labels.users_responsability') }}</td>
                            <td>{{ Lang::get('task.labels.users_hourse') }}</td>
                            <td>{{ Lang::get('task.labels.users_hours') }}</td>
                            <td>{{ Lang::get('task.labels.users_calification') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($task->users()->get() as $usuario)
                        <tr>
                            <td>
                                {{ $usuario->name }}
                            </td>
                            <td>
                                {{ $usuario->pivot->responsability }}
                            </td>
                            <td>
                                {{ $usuario->pivot->hours }}
                            </td>
                            <td>
                                {{ $task->workedhours($usuario->id) }}
                            </td>
                            <td>
                                {{ Lang::get("task.selects.user_calification")[$usuario->pivot->calification] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabComments">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordionTk" href="#TabComments" aria-expanded="false" aria-controls="TabComments">
                    {{ Lang::get('work.labels.comments') }}
                </a>
                <a class='pull-right' href='{{ action('CommentsController@create') }}?tk={{ $task->id }}' >{{ Lang::get("comment.labels.create") }}</a>
            </h4>
        </div>
        <div id="TabComments" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabComments">
            <div class="panel-body">
                <div class="row">
                    @foreach($task->comments()->get() as $comment)
                    <div class="col-sm-4 col-xs-6">
                        <div class="thumbnail">
                            @if ($comment->image == "" )
                            @if ($comment->url == "" )
                            -
                            @else
                            @if (strpos($comment->url,"youtube")===false)
                            <a href='{{ $comment->url }}' target="_blank">
                                <image src="{{ asset('/images/img/file.png' ) }}" alt="image"/>
                            </a>
                            @else
                            <?php
                            parse_str(parse_url($comment->url, PHP_URL_QUERY), $arrVariables);
                            ?>
                            <a href='{{ $comment->url }}' target="_blank">
                                <image src="http://i3.ytimg.com/vi/{{ $arrVariables["v"] }}/default.jpg" alt="image"/>
                            </a>
                            @endif
                            @endif
                            @else
                            <a href='{{ asset("images/comments/" . $comment->image) }}' target="_blank">
                                @if (preg_match('/(\.jpg|\.png|\.bmp)$/', $comment->image))
                                <image class="img-responsive" src="{{ asset('/images/comments/' . $comment->image ) }}" alt="image"/>
                                @else
                                <image class="img-responsive" src="{{ asset('/images/img/file.png' ) }}" alt="image"/>
                                @endif
                            </a>
                            @endif
                            <div class="caption">
                                <a class='' href='{{ URL::route(Lang::get("principal.menu.links.comentario") . '.show', array($comment->id)) }}'>
                                    <h4>{{ $comment->commenttype->name}}</h4>
                                </a>
                                <blockquote class="blockquote-reverse">
                                    <p>{{ Str::limit($comment->comment,150) }}</p>
                                    <footer>{{ $comment->user->name }} <cite title="{{Lang::get("comment.labels.date")}}">{{ $comment->date }}</cite></footer>
                                </blockquote>
                                <p>
                                    <a class='btn btn-success' href='{{ URL::route(Lang::get("principal.menu.links.comentario") . '.edit', array($comment->id)) }}'>{{ Lang::get("comment.labels.editar") }}</a>
                                    <a class='btn btn-danger' href='{{ URL::route(Lang::get("principal.menu.links.comentario") . '.destroy', array($comment->id)) }}'>{{ Lang::get("comment.labels.eliminar") }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>





