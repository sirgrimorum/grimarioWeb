@extends("layouts.principal")
<?php
$config = [
            "modelo" => "User",
            "tabla" => "users",
            "nombre" => "first_name",
            "id" => "id",
            "url" => action('UsersController@postRegister'),
            "botones" => "Registrarme",
            "render" => "",
            "campos" => [
                "email"=>[
                    "tipo"=>"email",
                    "label"=>Lang::get("user.labels.email"),
                    "placeholder"=>Lang::get("user.placeholders.email")
                ],
                "password"=>[
                    "tipo"=>"password",
                    "label"=>Lang::get("user.labels.password"),
                    "placeholder"=>Lang::get("user.placeholders.password")
                ],
                "first_name"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("user.labels.first_name"),
                    "placeholder"=>Lang::get("user.placeholders.first_name")
                ],
                "last_name"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("user.labels.last_name"),
                    "placeholder"=>Lang::get("user.placeholders.last_name")
                ],
            ],
            "relaciones"=>[]
        ]
?>


@section("contenido")

<h1>{{ Lang::get("user.titulos.registro") }}</h3>
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