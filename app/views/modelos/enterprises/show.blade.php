@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.enterprise'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.enterprise.campos'), $configCampos);

$configProyects = array_except(Config::get('crudgen.proyect'), array('campos'));
$configProyects['campos'] = array_only(Config::get('crudgen.proyect.campos'), $configCampos);
$configProyects['botones'] = $configBotones;
$configProyects['orden']=[[3,'asc']];
?>


@section("contenido")
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">{{ $enterprise->nickname }}</li>
</ol>
<h1>{{ Lang::get("enterprise.titulos.show") }}</h1>
<div class='container'>
    {{ CrudLoader::show($config,$enterprise->id,$enterprise) }}
</div>
<div class='container'>
    <h3>{{ Lang::get("proyect.titulos.index") }}</h3>
        @if ($botonCrear)
        <a href='{{ action('ProyectsController@create') }}?en={{ $enterprise->id }}' class='btn btn-info' >{{ Lang::get("proyect.labels.create") }}</a>
        @endif
        {{ CrudLoader::lists($configProyects,$proyects) }}
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