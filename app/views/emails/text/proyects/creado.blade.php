@extends("emails.text.plantilla")

@section("titulo")
{{ Lang::get("proyect.emails.titulos.creado") }}
@stop

@section("contenido")
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("proyect.emails.textos.acaba_de_crear") }} 
    {{ $proyect->name . " - " . $proyect->code }}
    \n
    {{ Lang::get("proyect.emails.textos.solicitud_planear") }}
@stop