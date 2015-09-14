<?php
$config = array_except(Config::get('crudgen.userdata'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.userdata.campos'), array('user_id'));
$config['url'] = URL::to(action('UsersController@postNew'));
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("user.titulos.create") }}</h3>
<p>{{ TransArticle::get("user.prueba2") }}</p>
<div class='container'>
    {{ CrudLoader::create($config) }}
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