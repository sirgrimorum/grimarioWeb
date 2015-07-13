@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.task'), array('campos'));
$config['campos'] = array_only(Config::get('crudgen.task.campos'),array('order','name','code','state','game_id','type','description','difficulty','start','end', 'dcuantity', 'pcuantity'));
?>


@section("contenido")

<h1>{{ Lang::get("task.titulos.index") }}</h3>
<a href='{{ action('TasksController@create') }}' class='btn btn-info' >{{ Lang::get("task.labels.create") }}</a>
<div class='container'>
    {{ CrudLoader::lists($config,$tasks) }}
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