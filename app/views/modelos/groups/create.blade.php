@extends("layouts.principal")
<?php
$config = [
            "modelo" => "Group",
            "tabla" => "groups",
            "nombre" => "name",
            "id" => "id",
            "url" => action('GroupsController@store'),
            "botones" => "Crear",
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
               "gamers"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.gamers"),
                    "placeholder"=>Lang::get("group.descriptions.gamers"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "teams"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.teams"),
                    "placeholder"=>Lang::get("group.descriptions.teams"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "enterprises"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.enterprises"),
                    "placeholder"=>Lang::get("group.descriptions.enterprises"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "games"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.games"),
                    "placeholder"=>Lang::get("group.descriptions.games"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "prices"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.prices"),
                    "placeholder"=>Lang::get("group.descriptions.prices"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "proyects"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.proyects"),
                    "placeholder"=>Lang::get("group.descriptions.proyects"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "tasks"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.tasks"),
                    "placeholder"=>Lang::get("group.descriptions.tasks"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ],
                "comments"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.comments"),
                    "placeholder"=>Lang::get("group.descriptions.comments"),
                    "opciones"=>Lang::get("group.selects.permissions")
                ]
            ],
        ]
?>


@section("contenido")

<h1>{{ Lang::get("group.titulos.create") }}</h3>
<div class='container'>
    {{ CrudLoader::create($config) }}
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