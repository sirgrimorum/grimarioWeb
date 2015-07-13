@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.proyect'), array('campos'));

$config['campos'] = array_only(Config::get('crudgen.proyect.campos'), $configCampos);
$config['botones'] = $configBotones;
?>


@section("contenido")

<h1>{{ Lang::get("proyect.titulos.index") }}</h3>
@if ($botonCrear)
<a href='{{ action('ProyectsController@create') }}' class='btn btn-info' >{{ Lang::get("proyect.labels.create") }}</a>
@endif
<div class='container'>
    {{ CrudLoader::lists($config,$proyects) }}
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