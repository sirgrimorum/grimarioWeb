@extends("emails.text.plantilla")

@section("titulo")
{{ Lang::get("userdata.emails.titulos.creado") }}
@stop

@section("contenido")
    {{ $userTo->name }}, {{ Lang::get("userdata.emails.textos.acaba_de_crear") }}
    \n\t-{{ Lang::get("userdata.emails.textos.nombre") }}: {{ $user->name }}
    \n\t-{{ Lang::get("userdata.emails.textos.email") }}: {{ $user->email }}
    \n\t-{{ Lang::get("userdata.emails.textos.password") }}: {{ $clave }}
    \n
    \n
    {{ Lang::get("userdata.emails.textos.enviar_datos") }}
@stop