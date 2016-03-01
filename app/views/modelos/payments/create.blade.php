<?php
$config = array_except(Config::get('crudgen.payment'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.payment.campos'), array('paymentdate', 'totalcost', 'profit', 'advance', 'saves', 'totalhours', 'saveshours', 'planh', 'plan'));
$config["campos"]["state"]["tipo"] = "hidden";
$preDatos = false;
if (Input::has('pr')) {
    $preDatos = true;
    $config["campos"]["proyect_id"]["tipo"] = "hidden";
    $config["campos"]["proyect_id"]["valor"] = $proyect->id;
    
    if ($ultimoEntregable = $proyect->payments()->orderby("plandate", "DESC")->first()){
        $config["campos"]["plandate"]["valor"] = $ultimoEntregable->plandate;
    }else{
        $config["campos"]["plandate"]["valor"] = $proyect->planstart;
    }
}
?>
@extends("layouts.principal")

@section("contenido")
@if ($preDatos)
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id)) }}">{{ $proyect->name }}</a></li>
  <li class="active">{{ Lang::get("payment.titulos.create") }}</li>
</ol>
@endif
<h1>{{ Lang::get("payment.titulos.create") }}</h3>
<p>{{ TransArticle::get("payment.prueba2") }}</p>
@if ($preDatos)
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
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="LabGantt">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#TabGantt" aria-expanded="false" aria-controls="TabGantt">
                        {{ Lang::get("proyect.labels.gantt") }}
                    </a>
                </h4>
            </div>
            <div id="TabGantt" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="LabGantt">
                <div id="ganttpro" class="gantt"></div>
            </div>
        </div>
    </div>
</div>
@endif
<div class='container'>
    {{ CrudLoader::create($config) }}
</div>

@stop

@section("selfjs")
@parent
{{ HTML::script("js/jquery.fn.gantt.js") }}
<script>
    $(document).ready(function() {
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
            dow: [
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
{{ HTML::style("css/gantt/gantt.css") }}
@stop