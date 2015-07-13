@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.proyect'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.proyect.campos'), array('satisfaction', 'experience'));
$configPago = array_except(Config::get('crudgen.payment'), array('campos', 'botones'));
$configPago['campos'] = array_except(Config::get('crudgen.payment.campos'), array('proyect_id', 'conditions'));
$configPago['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")) . "'>" . Lang::get("payment.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.edit', array("{ID}")) . "'>" . Lang::get("payment.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.destroy', array("{ID}")) . "'>" . Lang::get("payment.labels.eliminar") . "</a>",
];
$configRestriccion = array_except(Config::get('crudgen.restriction'), array('campos', 'botones'));
$configRestriccion['campos'] = array_only(Config::get('crudgen.restriction.campos'), array('probability', 'name', 'type', 'state', 'date', 'description', 'comments', 'user_id'));
$configRestriccion['botones'] = [
    "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")) . "'>" . Lang::get("restriction.labels.ver") . "</a>",
    "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.edit', array("{ID}")) . "'>" . Lang::get("restriction.labels.editar") . "</a>",
    "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.destroy', array("{ID}")) . "'>" . Lang::get("restriction.labels.eliminar") . "</a>",
];
?>


@section("contenido")

<h1>{{ Lang::get("proyect.titulos.show") }}</h1>
<div class='container'>
    {{ CrudLoader::show($config,$proyect->id,$proyect) }}
</div>

<div class='container'>
    <h3>{{ Lang::get("proyect.labels.restrictions") }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('proyect.descriptions.restrictions') }}
    </span>
    <a href='{{ action('RestrictionsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' >{{ Lang::get("restriction.labels.create") }}</a>

    {{ CrudLoader::lists($configRestriccion,$proyect->restrictions()->get()) }}
</div>
<div class='container'>
    <h3>{{ Lang::get("proyect.labels.payments") }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('proyect.descriptions.payments') }}
    </span>
    <a href='{{ action('PaymentsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' >{{ Lang::get("payment.labels.create") }}</a>

    {{ CrudLoader::lists($configPago,$proyect->payments()->get()) }}
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