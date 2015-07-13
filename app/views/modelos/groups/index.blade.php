@extends("layouts.principal")
<?php
$config = [
            "modelo" => "Group",
            "tabla" => "groups",
            "nombre" => "name",
            "id" => "id",
            "botones" => [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.grupo") . '.show', array("{ID}")) . "'>" . Lang::get("group.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.grupo") . '.edit', array("{ID}")) . "'>" . Lang::get("group.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.grupo") . '.destroy', array("{ID}")) . "'>" . Lang::get("group.labels.eliminar") . "</a>",
            ],
            "campos" => [
                "name"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("group.labels.name"),
                ],
                "permissions"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("group.labels.permissions"),
                ],
            ],
        ]
?>


@section("contenido")

<h1>{{ Lang::get("group.titulos.index") }}</h3>
<a href='{{ action('GroupsController@create') }}' class='btn btn-info' >{{ Lang::get("group.titulos.create") }}</a>
<div class='container'>
    {{ CrudLoader::lists($config,$groups) }}
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