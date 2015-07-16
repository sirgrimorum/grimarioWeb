@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.game'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.game.campos'), array(''));

?>

@section("contenido")

<h1>{{ Lang::get("game.titulos.show") }}</h1>
<div class='container'>
    {{ CrudLoader::show($config,$game->id,$game) }}
</div>
<div class='container'>
    <h2>{{ Lang::get("user.titulos.index") }}</h2>
    <div id='bar_users'></div>
    @barchart('points_users', 'bar_users')
</div>
<div class='container'>
    <h2>{{ Lang::get("team.titulos.index") }}</h2>
    <div id='bar_teams'></div>
    @barchart('points_teams', 'bar_teams')
</div>
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