@extends("emails.html.plantilla")

@section("titulo")
{{ Lang::get("proyect.emails.titulos.creado") }}
@stop

@section("contenido")
<p>
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("proyect.emails.textos.acaba_de_crear") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto"). '.show', array($proyect->id)) }}" >{{ $proyect->name . " - " . $proyect->code }} </a>
</p>
<p>
    {{ Lang::get("proyect.emails.textos.solicitud_planear") }}
    <br/>
</p>
@stop
