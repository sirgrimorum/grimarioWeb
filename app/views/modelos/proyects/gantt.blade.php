@extends("layouts.principal")
@section("contenido")
<div id="gantt" class="gantt">
</div>
@stop

@section("selfcss")
@parent
{{ HTML::style("css/gantt/gantt.css") }}
@stop


@section("selfjs")
@parent
<!--script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script-->
{{ HTML::script("js/jquery.fn.gantt.js") }}
<script>
    $(document).ready(function() {
        $("#gantt").gantt({
            source: "{{ asset('/images/img/pruebatml/dataDays.js') }}",
            scale: "weeks",
            minScale: "hours",
            maxScale: "months",
            onItemClick: function(data) {
                alert("Item clicked - show some details");
            },
            onAddClick: function(dt, rowId) {
                alert("Empty space clicked - add an item!");
            },
            onRender: function() {
                console.log("chart rendered");
            }
        });
    });
</script>
@stop
