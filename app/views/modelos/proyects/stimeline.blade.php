<?php
$arrComments = array();
foreach ($proyect->payments()->get() as $paymenta) {
    foreach ($paymenta->tasks()->get() as $taska) {
        foreach ($taska->comments()->get() as $commenta) {
            if (($botonVerInterno == false && $commenta->public == true) || $botonVerInterno == true) {
                $item = array(
                    "unix" => strtotime($commenta->date),
                    "comment" => $commenta
                );
                array_push($arrComments, $item);
            }
        }
    }
}
//echo "<p>uno</p><pre>" . print_r($arrComments,true) . "</pre>";

$arrComments = array_values(array_sort($arrComments, function($value) {
            return $value['unix'];
        }));
//echo "<pre>" . print_r($arrComments,true) . "</pre>";
?>

<section id="pr-timeline" class="vtl-container">
    @foreach ($arrComments as $sngComment)
    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-{{ $sngComment["comment"]->task->tasktype->id }}" data-toggle="tooltip" data-placement="top" title="{{ Lang::get("principal.labels.months." . date("M",$sngComment["unix"])) . date(" j, g:i a",$sngComment["unix"]) }}">
            <img src="{{ asset('/images/img/tipoact/tipo' . $sngComment["comment"]->commenttype->id . '.svg' ) }}" alt="{{ $sngComment["comment"]->commenttype->name . ", " . $sngComment["comment"]->task->tasktype->name }}">
        </div>

        <div class="vtl-timeline-content">
            <h2>{{ $sngComment["comment"]->task->tasktype->name . " - " . $sngComment["comment"]->commenttype->name }}</h2>
            @if ($sngComment["comment"]->image == "" )
            @if ($sngComment["comment"]->url == "" )
            @else
            @if (strpos($sngComment["comment"]->url,"youtube")===false)
            <a href='{{ $sngComment["comment"]->url }}' target="_blank">
                <image src="{{ asset('/images/img/file.png' ) }}" alt="image"/>
            </a>
            @else
            <?php
            parse_str(parse_url($sngComment["comment"]->url, PHP_URL_QUERY), $arrVariables);
            ?>
            <a href='{{ $sngComment["comment"]->url }}' target="_blank">
                <image src="http://i3.ytimg.com/vi/{{ $arrVariables["v"] }}/default.jpg" alt="image"/>
            </a>
            @endif
            @endif
            @else
            <a href='{{ asset("images/comments/" . $sngComment["comment"]->image) }}' target="_blank">
                @if (preg_match('/(\.jpg|\.png|\.bmp)$/', $sngComment["comment"]->image))
                <image class="img-responsive" src="{{ asset('/images/comments/' . $sngComment["comment"]->image ) }}" alt="image"/>
                @else
                <image class="img-responsive" src="{{ asset('/images/img/file.png' ) }}" alt="image"/>
                @endif
            </a>
            @endif
            <p>{{ $sngComment["comment"]->comment }}</p>

            <!--a href="#0" class="vtl-read-more">Read more</a-->
            <span class="vtl-date" >{{ Lang::get("principal.labels.monthsshort." . date("M",$sngComment["unix"])) . date(" j",$sngComment["unix"]) }}</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->
    @endforeach
</section> <!-- vtl-timeline -->

@section("selfcss")
@parent
{{ HTML::style("css/timeline/stimeline.css") }}
@stop


@section("selfjs")
@parent
{{ HTML::script("js/modernizr.js") }}
{{ HTML::script("js/stimeline.js") }}
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop

