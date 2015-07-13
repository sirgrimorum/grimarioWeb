@extends("layouts.principal")
<?php
$config = [
            "modelo" => "Group",
            "tabla" => "groups",
            "nombre" => "name",
            "id" => "id",
            "botones" => "Editar",
            "campos" => [
                "name"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("group.labels.name"),
                    "placeholder"=>Lang::get("group.descriptions.name")
                ],
                "admin"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.admin"),
                    "placeholder"=>Lang::get("group.descriptions.admin"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "groups"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.groups"),
                    "placeholder"=>Lang::get("group.descriptions.groups"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "permissions"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.permissions"),
                    "placeholder"=>Lang::get("group.descriptions.permissions"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "articles"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.articles"),
                    "placeholder"=>Lang::get("group.descriptions.articles"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "editor"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.editor"),
                    "placeholder"=>Lang::get("group.descriptions.editor"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "ver"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.ver"),
                    "placeholder"=>Lang::get("group.descriptions.ver"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
            ],
        ]
?>


@section("contenido")

<h1>{{ Lang::get("group.titulos.show") }}</h3>
<div class='container'>
    {{ CrudLoader::show($config,$group->id,$group) }}
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