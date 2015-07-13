@extends("layouts.principal")
<?php
$config = [
            "modelo" => "Payment",
            "tabla" => "payments",
            "nombre" => "name",
            "id" => "id",
            "botones" => [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")) . "'>" . Lang::get("payment.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.edit', array("{ID}")) . "'>" . Lang::get("payment.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.destroy', array("{ID}")) . "'>" . Lang::get("payment.labels.eliminar") . "</a>",
            ],
            "campos" => [
                "proyect_id" => [
                    "label" => Lang::get("payment.labels.proyect_id"),
                    "tipo" => "relationship",
                    "modelo" => "proyect",
                    "id" => "id",
                    "campo" => "name",
                    "enlace"=> URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")),
                    "todos" => ""
                ],
                "name"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("payment.labels.name"),
                    "enlace"=> URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")),
                ],
                "state"=>[
                    "tipo"=>"select",
                    "label"=>Lang::get("payment.labels.state"),
                    "opciones"=>Lang::get("payment.selects.state")
                ],
                "percentage"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("payment.labels.percentage"),
                ],
                "value"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("payment.labels.value"),
                ],
                "plandate"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("payment.labels.plandate"),
                ],
                "paymentdate"=>[
                    "tipo"=>"text",
                    "label"=>Lang::get("payment.labels.paymentdate"),
                ],
            ],
        ]
?>


@section("contenido")

<h1>{{ Lang::get("payment.titulos.index") }}</h1>
<a href='{{ action('PaymentsController@create') }}' class='btn btn-info' >{{ Lang::get("payment.labels.create") }}</a>
<div class='container'>
    {{ CrudLoader::lists($config,$payments) }}
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