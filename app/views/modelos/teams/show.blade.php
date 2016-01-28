<?php
$config = array_except(Config::get('crudgen.team'), array('campos'));
$config['campos'] = array_except(Config::get('crudgen.team.campos'), array());
if ($team->state == 'des' || $team->state == 'pau') {
    $config['campos']['start'] = [
        "tipo" => "date",
        "label" => Lang::get("task.labels.start"),
        "placeholder" => Lang::get("task.placeholders.start"),
        "description" => Lang::get("task.descriptions.start"),
    ];
    $config['campos']['dcuantity']['tipo'] = "hidden";
}
?>
@extends("layouts.principal")

@section("contenido")
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">{{ $team->name }}</li>
</ol>
<h1>{{ Lang::get("team.titulos.show") }}</h3>
<div class='container'>
    {{ CrudLoader::show($config,$team->id,$team) }}
</div>
<div id="ganttteam" class="gantt"></div>
@stop

@section("selfjs")
{{ HTML::script("js/jquery.fn.gantt.js") }}
<script>
    $(document).ready(function() {
        //alert(translations.task.error);
        $("#ganttteam").gantt({
            source: "{{ action('JsonsController@getGanttteam', $team->id); }}",
            scale: "days",
            minScale: "hours",
            maxScale: "weeks",
            itemsPerPage: 25,
            navigate: "scroll",
            scrollToToday: true,
            onRender: function() {
                console.log("chart rendered");
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
