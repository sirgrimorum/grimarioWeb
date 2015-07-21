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
    ];
    $config['campos']['profit'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.profit"),
    ];
    $config['campos']['totalcost'] = [
        "tipo" => "function",
        "label" => Lang::get("task.labels.totalcost"),
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
$configWorks = array_except(Config::get('crudgen.work'), array('campos', 'botones'));
$configWorks['campos'] = array_except(Config::get('crudgen.work.campos'), array('task_id','users'));
$configWorks['botones'] = "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.trabajo") . '.show', array("{ID}")) . "'>" . Lang::get("work.labels.ver") . "</a>";
$configWorks['campos']['totalcost'] = [
    "tipo" => "function",
    "label" => Lang::get("work.labels.totalcost"),
];
$configComments = array_except(Config::get('crudgen.comment'), array('campos', 'botones'));
$configComments['campos'] = array_except(Config::get('crudgen.comment.campos'), array('task_id'));
$configComments['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.show', array("{ID}")) . "'>" . Lang::get("comment.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.edit', array("{ID}")) . "'>" . Lang::get("comment.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.comentario") . '.destroy', array("{ID}")) . "'>" . Lang::get("comment.labels.eliminar") . "</a>",
];

?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("task.titulos.show") }}</h3>
<div class='container'>
    {{ CrudLoader::show($config,$task->id,$task) }}
</div>

<div class='container botones'>
    <div class='row'>
        @if ($task->state == 'pla')
            <div class='col-sm-offset-4 col-sm-4'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=des" class='btn btn-default'>{{ Lang::get("task.labels.comenzar") }}</a>
            </div>
        @elseif ($task->state == 'pau' || ( $task->state == 'des'  && !($work)))
            <div class='col-sm-offset-3 col-sm-3'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=des" class='btn btn-default'>{{ Lang::get("task.labels.reanudar") }}</a>
            </div>
            <div class='col-sm-2'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.trabajo"). '.create') }}?tk={{ $task->id }}" class='btn btn-default'>{{ Lang::get("task.labels.planear_work") }}</a>
            </div>
        @elseif ($task->state == 'des' || ($work))
            <div class='col-sm-offset-3 col-sm-2'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=pau" class='btn btn-default'>{{ Lang::get("task.labels.detener") }}</a>
            </div>
            @if ($user->inGroup(Sentry::findGroupByName('Coordinador')))
                <div class='col-sm-2'>
                    <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=ter" class='btn btn-default'>{{ Lang::get("task.labels.finalizar") }}</a>
                </div>
            @endif
            <div class='col-sm-2'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.trabajo"). '.create') }}?tk={{ $task->id }}" class='btn btn-default'>{{ Lang::get("task.labels.planear_work") }}</a>
            </div>
        @elseif ($task->state == 'ter' && $user->inGroup(Sentry::findGroupByName('Coordinador')))
            <div class='col-sm-offset-4 col-sm-4'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=ent" class='btn btn-default'>{{ Lang::get("task.labels.entregar") }}</a>
            </div>
        @elseif ($task->state == 'ent' && $user->inGroup(Sentry::findGroupByName('Director')))
            <div class='col-sm-offset-4 col-sm-4'>
                <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=cer" class='btn btn-default'>{{ Lang::get("task.labels.evaluar") }}</a>
            </div>
        @endif
    </div>
</div>
<div class="container">
    <h3>{{ Lang::get('task.labels.users') }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('task.descriptions.users') }}
    </span>
    @if ($user->inGroup(Sentry::findGroupByName('Director')))
        <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?equipo=act" class='btn btn-info'>{{ Lang::get("task.labels.edit_equipo") }}</a>
    @endif
    <table class="table table-striped table-bordered" id='list_users'>
        <thead>
            <tr>
                <td>{{ Lang::get('task.labels.user') }}</td>
                <td>{{ Lang::get('task.labels.users_responsability') }}</td>
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
                    {{ $task->workedhours($usuario->id) }}
                </td>
                <td>
                    {{ $usuario->pivot->calification }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="container">
    <h3>{{ Lang::get('task.labels.works') }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('task.descriptions.works') }}
    </span>
    {{ CrudLoader::lists($configWorks,$task->works()->get()) }}
</div>
<div class="container">
    <h3>{{ Lang::get('work.labels.comments') }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('work.descriptions.comments') }}
    </span>
    <a href='{{ action('CommentsController@create') }}?tk={{ $task->id }}' class='btn btn-info' >{{ Lang::get("comment.labels.create") }}</a>
    {{ CrudLoader::lists($configComments,$task->comments()->get()) }}
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