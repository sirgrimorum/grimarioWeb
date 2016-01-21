@extends("layouts.principal")
@section("contenido")
<div id="timeline">
</div>
@stop

@section("selfcss")
@parent
<!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
{{ HTML::style("css/timeline/timeline.css") }}

@stop


@section("selfjs")
@parent
<script src="http://code.jquery.com/jquery-migrate-1.3.0.js"></script>
{{ HTML::script("js/timeline-min.js") }}
<script>
    $(document).ready(function() {
        var timeline = new VMM.Timeline();
	timeline.init("{{ asset('/images/img/pruebatml/datatimeline.json') }}");
    });
</script>
@stop