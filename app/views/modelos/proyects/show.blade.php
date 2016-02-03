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
$configClientes = array_except(Config::get('crudgen.user'), array('campos', 'botones'));
$configClientes['campos'] = array_only(Config::get('crudgen.user.campos'), array('name', 'email'));
$configClientes['campos']['cel'] = [
    "label" => Lang::get("userdata.labels.cel"),
    "tipo" => "relationships",
    "modelo" => "userdatum",
    "id" => "id",
    "campo" => "cel",
    //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
    "todos" => ""
];
$configClientes['campos']['otel'] = [
    "label" => Lang::get("userdata.labels.otel"),
    "tipo" => "relationships",
    "modelo" => "userdatum",
    "id" => "id",
    "campo" => "otel",
    //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
    "todos" => ""
];
$configClientes['campos']['charge'] = [
    "label" => Lang::get("userdata.labels.charge"),
    "tipo" => "relationships",
    "modelo" => "userdatum",
    "id" => "id",
    "campo" => "charge",
    //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
    "todos" => ""
];
$configClientes['campos']['oenterprise'] = [
    "label" => Lang::get("userdata.labels.oenterprise"),
    "tipo" => "relationships",
    "modelo" => "Userdatum",
    "id" => "id",
    "campo" => "oenterprise",
    //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
    "todos" => ""
];
$configClientes['botones'] = $configBotonesClientes;

if (Input::has("py")) {
    $ariaExpanded = "true";
    $classExpanded = "in";
} else {
    $ariaExpanded = "false";
    $classExpanded = "";
}
?>


@section("contenido")
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="{{ URL::route(Lang::get("principal.menu.links.empresa") . '.show', array($proyect->enterprises()->first()->id)) }}">{{ $proyect->enterprises()->first()->nickname }}</a></li>
    <li class="active">{{ $proyect->name }}</li>
</ol>
<div id="avancebar"><div class="progress-label">{{ Lang::get("proyect.labels.advance") }}: {{ $proyect->advance() }}%</div></div>
<h1>
    {{ $proyect->name }} 
    <small class="estado">{{ Lang::get("proyect.selects.state." . $proyect->state) }}</small>
    @if (($userSen->inGroup(Sentry::findGroupByName('Empresario')) or $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))))
    <a class="btn btn-danger pull-right" href='{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array($proyect->id)) }}?pre=1' >
        {{ Lang::get("proyect.labels.editarpre") }}
    </a>
    @endif
    @if ($botonCrearEntregables)
    <a class="btn btn-warning pull-right" href='{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array($proyect->id)) }}' >
        {{ Lang::get("proyect.labels.editar") }}
    </a>
    @endif
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseMasinfo" aria-expanded="false" aria-controls="collapseMasinfo">
        {{ Lang::get("principal.labels.mas_info") }}
    </button>
</h1>
<h5>
    <label>{{ Lang::get("proyect.labels.user_id") }}:</label> 
    {{ $proyect->User->name }}
</h5>
<div class="collapse" id="collapseMasinfo">
    {{ CrudLoader::show($config,$proyect->id,$proyect) }}
</div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    @if ($botonVerInterno)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabEstats">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabEstats" aria-expanded="false" aria-controls="TabEstats">
                    {{ Lang::get("proyect.labels.estats") }}
                </a>
            </h4>
        </div>
        @if ($botonCrearEntregables or $userSen->inGroup(Sentry::findGroupByName('Lider')))
        <div id="TabEstats" class="panel-collapse collapse " role="tabpanel" aria-labelledby="LabEstats">
            <div id='bar_payments_av'></div>
            <!--div class="row estadistica">
                <div class="col-sm-6 col-xs-6">
                    <div id='pie_payments_per'></div>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <div id='bar_proyect_presup'></div>
                </div>
            </div-->
            <button id="btnStatsAdvance" class="btn btn-info">{{ Lang::get("payment.labels.advance") }}</button>
            <button id="btnStatsPayPresup" class="btn btn-info">{{ Lang::get("payment.labels.value") . ' x ' . Lang::get('payment.labels.pagos') }}</button>
            <button id="btnStatsPayHours" class="btn btn-info">{{ Lang::get("payment.labels.saveshours") . ' x ' . Lang::get('payment.labels.pagos') }}</button>
            <button id="btnStatsPresup" class="btn btn-info">{{ Lang::get("proyect.labels.presupuesto") . ' ' . Lang::get('proyect.labels.proyecto') }}</button>
            <button id="btnStatsHours" class="btn btn-info">{{ Lang::get("proyect.labels.saveshours") . ' ' . Lang::get('proyect.labels.proyecto') }}</button>
        </div>
        @endif
    </div>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabGantt">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabGantt" aria-expanded="false" aria-controls="TabGantt">
                    {{ Lang::get("proyect.labels.gantt") }}
                </a>
            </h4>
        </div>
        <div id="TabGantt" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabGantt">
            <div id="ganttpro" class="gantt"></div>
            <button id="btnExport" class="btn btn-info">{{ Lang::get("proyect.labels.export") }}</button>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabTimeline">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabTimeline" aria-expanded="false" aria-controls="TabTimeline">
                    {{ Lang::get("proyect.labels.timeline") }}
                </a>
            </h4>
        </div>
        <div id="TabTimeline" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabTimeline">
            <div class="panel-body vtl-supercontainer">
                @include("modelos.proyects.stimeline")
            </div>
        </div>
    </div>
    @if ($botonVerInterno)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabSupuestos">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabSupuestos" aria-expanded="false" aria-controls="TabSupuestos">
                    {{ Lang::get("proyect.labels.restrictions") }}
                </a>
                @if ($botonCrearSupuestos)
                <a class='pull-right' href='{{ action('RestrictionsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("restriction.labels.create") }}</a>
                @endif
            </h4>
        </div>
        <div id="TabSupuestos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabSupuestos">
            <div class="panel-body">
                {{ CrudLoader::lists($configRestriccion,$proyect->restrictions()->get()) }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabClientes">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabClientes" aria-expanded="false" aria-controls="TabClientes">
                    {{ Lang::get("proyect.labels.userdatas") }}
                </a>
                @if ($botonCrearClientes)
                <a class='pull-right' href='{{ action('UserdatasController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("userdata.labels.create") }}</a>
                @endif
            </h4>
        </div>
        <div id="TabClientes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="LabClientes">
            <div class="panel-body">
                {{ CrudLoader::lists($configClientes,$proyect->clients()->get()) }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="LabPayments">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#TabPayments" aria-expanded="{{ $ariaExpanded }}" aria-controls="TabPayments">
                    {{ Lang::get("proyect.labels.payments") }}
                </a>
                @if ($botonCrearEntregables)
                <a class='pull-right' href='{{ action('PaymentsController@create') }}?pr={{ $proyect->id }}' class='btn btn-info' ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ Lang::get("payment.labels.create") }}</a>
                @endif
            </h4>
        </div>
        <div id="TabPayments" class="panel-collapse collapse {{ $classExpanded }}" role="tabpanel" aria-labelledby="LabPayments">
            <a name="payment"></a>
            <div class="panel-body">
                @include("modelos.payments.panelpro")
            </div>
        </div>
    </div>
    @endif
</div>


@stop

@section("selfcss")
@parent
{{ HTML::style("css/gantt/gantt.css") }}
@stop

@section("selfjs")
@if ($botonCrearEntregables)
@columnchart('payments_av', 'bar_payments_av')
@endif
{{ HTML::script("js/jquery.fn.gantt.js") }}
{{ HTML::script("js/jspdf.min.js") }}
<script>
    var doc = new jsPDF();
    var specialElementHandlers = {
        '#editor': function(element, renderer) {
            return true;
        }
    };
    $(document).ready(function() {
        $("#avancebar").progressbar({
        value: {{ $proyect->advance() }}
    });
    $("#btnExport").click(function(e) {
        //window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#ganttpro>.fn-gantt>.fn-content').html()));
        //window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#list_restrictions').html()));
        doc.fromHTML($('#ganttpro>.fn-gantt>.fn-content').html(), 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });
        doc.save('sample-file.pdf');
    });
    $("#btnStatsPayHours").click(function(e) {
        lava.getChart('payments_av', function (googleChart, lavaChart) {
            lavaChart.options.title = "{{ Lang::get('payment.labels.saveshours') . ' x ' . Lang::get('payment.labels.pagos') }}";
            lavaChart.options.vAxis.format = "decimal";
            lavaChart.options.isStacked = false;
            //console.log("lavachart",lavaChart);
            $.getJSON('{{ action('JsonsController@getChartProPayHours', $proyect->id); }}', function(dataTableJson) {
                lava.loadData('payments_av', dataTableJson, function(chart) {
                    //console.log(chart);
                });
            });
        });
    });
    $("#btnStatsPayPresup").click(function(e) {
        lava.getChart('payments_av', function (googleChart, lavaChart) {
            lavaChart.options.title = "{{ Lang::get('payment.labels.value') . ' x ' . Lang::get('payment.labels.pagos') }}";
            lavaChart.options.vAxis.format = "currency";
            lavaChart.options.isStacked = false;
            //console.log("lavachart",lavaChart);
            $.getJSON('{{ action('JsonsController@getChartProPayPresup', $proyect->id); }}', function(dataTableJson) {
                lava.loadData('payments_av', dataTableJson, function(chart) {
                    //console.log(chart);
                });
            });
        });
    });
    $("#btnStatsAdvance").click(function(e) {
        lava.getChart('payments_av', function (googleChart, lavaChart) {
            lavaChart.options.title = "{{ Lang::get('payment.labels.advance') }}";
            lavaChart.options.vAxis.format = "percent";
            lavaChart.options.isStacked = true;
            console.log("lavachart",lavaChart);
            $.getJSON('{{ action('JsonsController@getChartProAdvance', $proyect->id); }}', function(dataTableJson) {
                lava.loadData('payments_av', dataTableJson, function(chart) {
                    //console.log(chart);
                });
            });
        });
    });
    $("#btnStatsPresup").click(function(e) {
        lava.getChart('payments_av', function (googleChart, lavaChart) {
            lavaChart.options.title = "{{ Lang::get('proyect.labels.presupuesto') . ' ' . Lang::get('proyect.labels.proyecto') }}";
            lavaChart.options.vAxis.format = "currency";
            lavaChart.options.isStacked = true;
            console.log("lavachart",lavaChart);
            $.getJSON('{{ action('JsonsController@getChartProPresup', $proyect->id); }}', function(dataTableJson) {
                lava.loadData('payments_av', dataTableJson, function(chart) {
                    //console.log(chart);
                });
            });
        });
    });
    $("#btnStatsHours").click(function(e) {
        lava.getChart('payments_av', function (googleChart, lavaChart) {
            lavaChart.options.title = "{{ Lang::get('proyect.labels.saveshours') . ' ' . Lang::get('proyect.labels.proyecto') }}";
            lavaChart.options.vAxis.format = "decimal";
            lavaChart.options.isStacked = true;
            console.log("lavachart",lavaChart);
            $.getJSON('{{ action('JsonsController@getChartProHours', $proyect->id); }}', function(dataTableJson) {
                lava.loadData('payments_av', dataTableJson, function(chart) {
                    //console.log(chart);
                });
            });
        });
    });
    $("#ganttpro").gantt({
    source: "{{ action('JsonsController@getGanttproyect', $proyect->id); }}",
            scale: "weeks",
            minScale: "hours",
            maxScale: "months",
            itemsPerPage: 15,
            navigate: "scroll",
            scrollToToday: false,
            onRender: function() {
                //console.log("chart rendered");
            },
            onItemClick: function(dataObj) {
                console.log("Click en" + dataObj);
            },
            months: [
                    "{{ Lang::get('principal.labels.months.Jan') }}",
                    "{{ Lang::get('principal.labels.months.Feb') }}",
                    "{{ Lang::get('principal.labels.months.Mar') }}",
                    "{{ Lang::get('principal.labels.months.Apr') }}",
                    "{{ Lang::get('principal.labels.months.May') }}",
                    "{{ Lang::get('principal.labels.months.Jun') }}",
                    "{{ Lang::get('principal.labels.months.Jul') }}",
                    "{{ Lang::get('principal.labels.months.Aug') }}",
                    "{{ Lang::get('principal.labels.months.Sep') }}",
                    "{{ Lang::get('principal.labels.months.Oct') }}",
                    "{{ Lang::get('principal.labels.months.Nov') }}",
                    "{{ Lang::get('principal.labels.months.Dec') }}"
            ],
            dow:[
                    "{{ Lang::get('principal.labels.dow.su') }}",
                    "{{ Lang::get('principal.labels.dow.mo') }}",
                    "{{ Lang::get('principal.labels.dow.tu') }}",
                    "{{ Lang::get('principal.labels.dow.we') }}",
                    "{{ Lang::get('principal.labels.dow.th') }}",
                    "{{ Lang::get('principal.labels.dow.fr') }}",
                    "{{ Lang::get('principal.labels.dow.sa') }}"
            ]
    });
    });
</script>
@stop

@section("selfcss")
@parent
{{ HTML::style("css/panel.css") }}
@stop