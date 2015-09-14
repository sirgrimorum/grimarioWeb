@extends("emails.html.plantilla")

@section("titulo")
{{ Lang::get("userdata.emails.titulos.nuevo_cliente") }}
@stop

@section("contenido")
<p>
    {{ $userTo->name }}, {{ Lang::get("userdata.emails.textos.acaba_de_crear") }}
</p>
<ul>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.nombre") }}: </strong> {{ $user->name }}
    </li>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.email") }}: </strong> {{ $user->email }}
    </li>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.password") }}: </strong> {{ $clave }}
    </li>
</ul>
<p>
    {{ Lang::get("userdata.emails.textos.enviar_datos") }}
    <br/>
</p>
@stop
