<?php 
$config = array_except(Config::get('crudgen.proyect'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.proyect.campos'), "");

?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id)) }}">{{ $proyect->name }}</a></li>
    <li class="active">{{ Lang::get("proyect.titulos.editpresup") }}</li>
</ol>
<h1>{{ Lang::get("proyect.titulos.editpresup") }}</h1>
<div class='container'>
    {{ CrudLoader::show($config,$proyect->id,$proyect) }}
</div>
<div class='container'>
    {{ Form::model($proyect, array('route' => array(Lang::get("principal.menu.links.proyecto") . '.update', $proyect->id), 'class' => '', 'method' => 'PUT')) }}
    {{ Form::hidden("proyect_id", $proyect->id, array('class' => 'form-control', 'id' => 'proyect_id')) }}
    {{ Form::hidden("act_presup", "actualizar", array('class' => 'form-control', 'id' => 'proyect_act_presup')) }}

    <table class="table table-striped table-bordered" id='list_users'>
        <thead>
            <tr>
                <td>{{ Lang::get('payment.labels.name') }}</td>
                <td>{{ Lang::get('payment.labels.plandate') }}</td>
                <td>{{ Lang::get('payment.labels.percentage') }}</td>
                <td>{{ Lang::get('payment.labels.value') }}</td>
                <td>{{ Lang::get('payment.labels.conditions') }}</td>
                <td>{{ Lang::get('payment.labels.plan') }}</td>
                <td>{{ Lang::get('payment.labels.planh') }}</td>
            </tr>
        </thead>
        <tbody>
            @foreach($proyect->payments()->orderBy('plandate','ASC')->get() as $payment)
            <tr>
                <td>
                    {{ $payment->name }}
                </td>
                <td>
                    {{ date("j M Y",strtotime($payment->plandate)) }}
                </td>
                <td>
                    {{ $payment->percentage }}%
                </td>
                <td>
                    ${{ number_format($payment->value,0,".",".") }}
                </td>
                <td>
                    {{ $payment->conditions }}
                </td>
                <td>
                    {{ Form::text("proyect_payments_p_" . $payment->id, $payment->plan, array('class' => 'form-control plan_payment', 'id' => 'proyect_payments_p_' . $payment->id)) }}
                </td>
                <td>
                    {{ Form::text("proyect_payments_h_" . $payment->id, $payment->planh, array('class' => 'form-control planh_payment', 'id' => 'proyect_payments_h_' . $payment->id)) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="form-group">
        <div class="boton_centrado">
            {{ Form::submit(Lang::get('proyect.labels.editpresup'), array('class' => 'btn btn-primary')) }}
        </div>
    </div>
    {{ Form::close() }}
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