<?php
$config = [
            "modelo" => "$NAME$",
            "tabla" => "$COLLECTION$",
            "nombre" => "name",
            "id" => "id",
            "campos" => [
                "content" => [
                    "tipo" => "html",
                    "label" => "Content"
                ],
                "author_user_id" => [
                    "label" => "Autor",
                    "tipo" => "relationship",
                    "modelo" => "User",
                    "id" => "id",
                    "campo" => "name",
                    "todos" => ""
                ],
                "permissions"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("group.labels.permissions"),
                    "placeholder"=>Lang::get("group.placeholders.permissions"),
                    "description"=>Lang::get("group.descriptions.permissions"),
                    "opciones"=>array(
                        "admin"=>"Administrador",
                        "user"=>"Usuario",
                        "superuser"=>"SuperUsuario",
                        "empleado"=>"Empleado",
                    )
                ],
                "hobbies"=>[
                    "tipo"=>"checkbox",
                    "valor"=>[
                        1=>[
                            "label"=>Lang::get("group.labels.hobbies.1"),
                            "description"=>Lang::get("group.descriptions.hobbies.1"),
                        ],
                        2=>[
                            "label"=>Lang::get("group.labels.hobbies.2"),
                            "description"=>Lang::get("group.descriptions.hobbies.2"),
                        ],
                    ],
                ],    
                "activated"=>[
                    "tipo"=>"checkbox",
                    "label"=>Lang::get("group.labels.activated"),
                    "description"=>Lang::get("group.descriptions.activated"),
                    "valor"=>activated,
                ]           
            ],
            "botones" => [
                "<a href='{ID}'>Borrar</a>"
            ]
        ]
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("$NAME$.titulos") }}</h3>
<p>{{ TransArticle::get("$NAME$.prueba2") }}</p>
<div class='container'>
    {{ CrudLoader::lists($config) }}
</div>

@stop

@section("selfjs")
<script>
    $(document).ready(function() {
        alert(translations.$NAME$.error);
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop