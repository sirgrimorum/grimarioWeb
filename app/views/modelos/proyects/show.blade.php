@extends("layouts.principal")
<?php
$config = array_except(Config::get('crudgen.proyect'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.proyect.campos'), $configCampos);
$configPago = array_except(Config::get('crudgen.payment'), array('campos', 'botones'));
$configPago['campos'] = array_except(Config::get('crudgen.payment.campos'), array('proyect_id', 'conditions'));
$configPago['botones'] = $configBotonesEntregables;
$configRestriccion = array_except(Config::get('crudgen.restriction'), array('campos', 'botones'));
$configRestriccion['campos'] = array_only(Config::get('crudgen.restriction.campos'), array('probability', 'name', 'type', 'state', 'date', 'description', 'comments', 'user_id'));
$configRestriccion['botones'] = $configBotonesSupuestos;
?>


@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.empresa") . '.show', array($proyect->enterprises()->first()->id)) }}">{{ $proyect->enterprises()->first()->nickname }}</a></li>
  <li class="active">{{ $proyect->name }}</li>
</ol>
<h1>{{ Lang::get("proyect.titulos.show") }}</h1>
<div class='container'>
    {{ CrudLoader::show($config,$proyect->id,$proyect) }}
</div>

<div class='container'>
    <h3>{{ Lang::get("proyect.labels.restrictions") }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('proyect.descriptions.restrictions') }}
    </span>
    @if ($botonCrearSupuestos)
    <a href='{{ action('RestrictionsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' >{{ Lang::get("restriction.labels.create") }}</a>
    @endif
    {{ CrudLoader::lists($configRestriccion,$proyect->restrictions()->get()) }}
</div>
<div class='container'>
    <h3>{{ Lang::get("proyect.labels.payments") }}</h3>
    <span class="help-block" id="work_end_help">
        {{ Lang::get('proyect.descriptions.payments') }}
    </span>
    <div id='pie_payments_per'></div>
    @piechart('payments_per', 'pie_payments_per')
    @if ($botonCrearEntregables)
    <a href='{{ action('PaymentsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' >{{ Lang::get("payment.labels.create") }}</a>
    @endif
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