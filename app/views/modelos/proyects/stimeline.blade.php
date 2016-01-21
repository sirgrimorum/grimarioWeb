<?php ?>

<section id="pr-timeline" class="vtl-container">
    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-picture">
            <img src="{{ asset('/images/img/vtl-icon-picture.svg' ) }}" alt="Picture">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Title of section 1</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
            <a href="#0" class="vtl-read-more">Read more</a>
            <span class="vtl-date">Jan 14</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->

    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-movie">
            <img src="{{ asset('/images/img/vtl-icon-movie.svg' ) }}" alt="Movie">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Title of section 2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde?</p>
            <a href="#0" class="vtl-read-more">Read more</a>
            <span class="vtl-date">Jan 18</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->

    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-picture">
            <img src="{{ asset('/images/img/vtl-icon-picture.svg' ) }}" alt="Picture">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Title of section 3</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, obcaecati, quisquam id molestias eaque asperiores voluptatibus cupiditate error assumenda delectus odit similique earum voluptatem doloremque dolorem ipsam quae rerum quis. Odit, itaque, deserunt corporis vero ipsum nisi eius odio natus ullam provident pariatur temporibus quia eos repellat consequuntur perferendis enim amet quae quasi repudiandae sed quod veniam dolore possimus rem voluptatum eveniet eligendi quis fugiat aliquam sunt similique aut adipisci.</p>
            <a href="#0" class="vtl-read-more">Read more</a>
            <span class="vtl-date">Jan 24</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->

    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-location">
            <img src="{{ asset('/images/img/vtl-icon-location.svg' ) }}" alt="Location">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Title of section 4</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
            <a href="#0" class="vtl-read-more">Read more</a>
            <span class="vtl-date">Feb 14</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->

    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-location">
            <img src="{{ asset('/images/img/vtl-icon-location.svg' ) }}" alt="Location">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Title of section 5</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum.</p>
            <a href="#0" class="vtl-read-more">Read more</a>
            <span class="vtl-date">Feb 18</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->

    <div class="vtl-timeline-block">
        <div class="vtl-timeline-img vtl-movie">
            <img src="{{ asset('/images/img/vtl-icon-movie.svg' ) }}" alt="Movie">
        </div> <!-- vtl-timeline-img -->

        <div class="vtl-timeline-content">
            <h2>Final Section</h2>
            <p>This is the content of the last section</p>
            <span class="vtl-date">Feb 26</span>
        </div> <!-- vtl-timeline-content -->
    </div> <!-- vtl-timeline-block -->
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

    });
</script>
@stop

