@extends("layouts.principal")
<?php
$teams = $user->teams()->get();

//$proyects = $user->proyects()->get();

$configTareas = array_except(Config::get('crudgen.task'), array('campos', 'botones'));
$configTareas['campos'] = array_only(Config::get('crudgen.task.campos'), array('proyect', 'payments' ,'name', 'code', 'dpercentage', 'start', 'plan'));
//$configTareas['botones'] = "";
$configTareasPen = array_except(Config::get('crudgen.task'), array('campos', 'botones'));
$configTareasPen['campos'] = array_only(Config::get('crudgen.task.campos'), array('proyect', 'payments' ,'name', 'code', 'planstart', 'plan'));
//$configTareasPen['botones'] = "";
$configTeams = array_except(Config::get('crudgen.team'), array('campos', 'botones'));
$configTeams['campos'] = array_only(Config::get('crudgen.team.campos'), array('logo','name', 'type', 'description'));
$configProyects = array_except(Config::get('crudgen.proyect'), array('campos', 'botones'));
$configProyects['campos'] = array_only(Config::get('crudgen.proyect.campos'), array('name', 'code' ,'priority', 'state', 'advance', 'pop'));
$configWorks = array_except(Config::get('crudgen.work'), array('campos', 'botones'));
$configWorks['campos'] = array_only(Config::get('crudgen.work.campos'), array('name','start', 'task_id'));
?>

@section("contenido")

<h1>{{ Lang::get("home.titulos.show") }}</h1>
@if (!$esCliente)
<div class='container'>
    <h2>{{ Lang::get("work.titulos.index") }}</h2>
    {{ CrudLoader::lists($configWorks,$works) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("task.titulos.activas") }}</h2>
    {{ CrudLoader::lists($configTareas,$tasks) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("task.titulos.pendientes") }}</h2>
    {{ CrudLoader::lists($configTareasPen,$taskspen) }}
</div>
@endif
@if (!$noProyects)
<div class='container'>
    <h2>{{ Lang::get("proyect.titulos.activos") }}</h2>
    {{ CrudLoader::lists($configProyects,$proyects) }}
</div>
@endif
@if (!$esCliente)
<div class='container'>
    <h2>{{ Lang::get("team.titulos.propios") }}</h2>
    {{ CrudLoader::lists($configTeams,$teams) }}
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