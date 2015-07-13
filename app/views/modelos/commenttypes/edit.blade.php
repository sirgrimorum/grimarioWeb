D:\wamp\www\grimario\app/views/modelos/commenttypes/edit.blade.php
<?php
//$config = array_except(Config::get('crudgen.$NAME$'), array('campos'));
//$config['campos'] = array_only(Config::get('crudgen.$NAME$.campos'), array('name', 'code', 'priority','type','enterprises'));
//$config['campos'] = array_except(Config::get('crudgen.$NAME$.campos'), array('name', 'code', 'priority','type','enterprises'));
$config['campos'] = Config::get('crudgen.$NAME$');
$config['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.destroy', array("{ID}")) . "'>" . Lang::get("proyect.labels.eliminar") . "</a>",
];
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("$NAME$.titulos") }}</h3>
<p>{{ TransArticle::get("$NAME$.prueba2") }}</p>
<div class='container'>
    {{ CrudLoader::lists($config) }}
    {{ CrudLoader::create($config) }}
    {{ CrudLoader::show($config,$NAME$->id,$NAME$) }}
    {{ CrudLoader::edit($config,$payment->id,$payment) }}
</div>

@stop

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
        alert(translations.$NAME$.error);
    });
</script>
@stop

@section("selfcss")
@parent
<!--{{ HTML::style("css/acerca.css") }} -->
@stop