<?php
$config = array_except(Config::get('crudgen.indicator'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.indicator.campos'), array(''));
$config['botones'] = Lang::get("indicator.labels.edit");
$config['url'] = action('IndicatorsController@update', [$indicator->id]);
$payment = $indicator->payment;
$proyect = $payment->proyect;
$config["campos"]["payment_id"]["tipo"] = "hidden";
unset($config["campos"]["state"]["valor"]);
if (Input::has("tk") && Input::has("st")) {
    $config["campos"]["tk"] = [
        "tipo" => "hidden",
        "valor" => Input::get("tk"),
    ];
    $config["campos"]["st"] = [
        "tipo" => "hidden",
        "valor" => Input::get("st"),
    ];
}
?>
@extends("layouts.principal")

@section("contenido")
<h1>{{ Lang::get("indicator.titulos.edit") }}</h3>
<p>{{ TransArticle::get("indicator.prueba2") }}</p>
<div class="row">
    <div class="col-sm-6">
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
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $payment->name }}</h3>
            </div>
            <div class="panel-body">
                <strong>{{ Lang::get("payment.labels.percentage") }}:</strong> {{ $payment->percentage }}
                <br>
                <strong>{{ Lang::get("payment.labels.value") }}:</strong> {{ $payment->value }}
                <br>
                <strong>{{ Lang::get("payment.labels.state") }}:</strong> {{ Lang::get("payment.selects.state.".$payment->state) }}
                <p>{{ $payment->conditions }}</p>
            </div>
        </div>
    </div>
</div>
<div class='container'>
    {{ CrudLoader::edit($config,$indicator->id,$indicator) }}
</div>

@stop

@section("selfjs")
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop