@extends("layouts.principal")
<?php
$tasks = $user->tasks()->where("state","<>","cer")->get();
$teams = $user->teams()->get();
$works = $user->coordworks()->where("end","=",0)->get();
$proyects = $user->proyects()->get();

$configTareas = array_except(Config::get('crudgen.task'), array('campos', 'botones'));
$configTareas['campos'] = array_only(Config::get('crudgen.task.campos'), array('proyect', 'payments' ,'name', 'code', 'state', 'dpercentage', 'game_id', 'tasktype', 'description', 'dificulty', 'start', 'plan'));
//$configTareas['botones'] = "";
$configTeams = array_except(Config::get('crudgen.team'), array('campos', 'botones'));
$configTeams['campos'] = array_only(Config::get('crudgen.team.campos'), array('logo','name', 'state' ,'type', 'difficulty', 'description'));
$configProyects = array_except(Config::get('crudgen.proyect'), array('campos', 'botones'));
$configProyects['campos'] = array_only(Config::get('crudgen.proyect.campos'), array('name', 'code' ,'priority', 'state', 'advance', 'totalplan', 'totalcost', 'saves', 'pop'));
$configWorks = array_except(Config::get('crudgen.work'), array('campos', 'botones'));
$configWorks['campos'] = array_only(Config::get('crudgen.work.campos'), array('start', 'task_id'));
?>

@section("contenido")

<h1>{{ Lang::get("home.titulos.show") }}</h1>
<div class='container'>
    <h2>{{ Lang::get("team.titulos.index") }}</h2>
    {{ CrudLoader::lists($configTeams,$teams) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("work.titulos.index") }}</h2>
    {{ CrudLoader::lists($configWorks,$works) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("task.titulos.index") }}</h2>
    {{ CrudLoader::lists($configTareas,$tasks) }}
</div>
@if (Sentry::getUser()->hasAccess('proyects'))
<div class='container'>
    <h2>{{ Lang::get("proyect.titulos.index") }}</h2>
    {{ CrudLoader::lists($configProyects,$proyects) }}
</div>
@endif
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