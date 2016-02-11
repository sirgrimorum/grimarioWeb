<?php
$config = array_except(Config::get('crudgen.payment'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.payment.campos'), array('totalcost', 'profit', 'paymentdate', 'advance', 'saves', 'totalhours', 'saveshours'));
$proyect = $payment->proyect;
$config['botones'] = Lang::get("payment.labels.edit");
$config['url'] = action('PaymentsController@update', [$payment->id]);
$config["campos"]["proyect_id"]["tipo"] = "hidden";
$config["campos"]["proyect_id"]["valor"] = $proyect->id;
unset($config["campos"]["state"]["valor"]);
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id)) }}">{{ $payment->proyect->name }}</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id)) }}">{{ $payment->name }}</a></li>
  <li class="active">{{ Lang::get("payment.titulos.edit") }}</li>
</ol>
<h1>{{ Lang::get("payment.titulos.edit") }}</h3>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $proyect->name }}</h3> <h6>{{ $proyect->code }}</h6>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("proyect.selects.priority.".$proyect->priority) }}</strong>
                <br>
                <strong>{{ Lang::get("proyect.labels.state") }}:</strong> {{ Lang::get("proyect.selects.state.".$proyect->state) }}
                <p>{{ $proyect->description }}</p>
                <strong>{{ Lang::get("proyect.labels.teams") }}:</strong>
                <p>
                    @foreach ($proyect->teams()->get() as $team)
                    <a href="{{ URL::route(Lang::get("principal.menu.links.equipo"). '.show', array($team->id)) }}">{{ $team->name }}</a>, 
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>
<div class='container'>
    {{ CrudLoader::edit($config,$payment->id,$payment) }}
</div>

@stop

@section("selfjs")
@parent
<script>
    $(document).ready(function() {
        //alert(translations.payment.error);
    });
</script>
@stop

@section("selfcss")
@parent
<!--{{ HTML::style("css/acerca.css") }} -->
@stop