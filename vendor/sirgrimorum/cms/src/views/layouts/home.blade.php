@extends("cms::layouts.includes.principal")

@section("contenido")
<div class="welcome">
    <h1>{{ Lang::get('cms::crud.layout.hola') }}</h1>
</div>
@stop

@section("selfjs")
<script>
    $(document).ready(function() {
        
    });
</script>
@stop
