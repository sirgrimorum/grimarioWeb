@extends("emails.html.plantilla")

@section("titulo")
{{ Lang::get("userdata.emails.titulos.nuevo_proyecto") }}
@stop

@section("contenido")
<p>
    {{ $user->name }}, {{ Lang::get("userdata.emails.textos.nuevo_proyecto") }} <strong>{{ $proyect->name }}</strong>
</p>
<p>
    {{ Lang::get("userdata.emails.textos.nuevo_proyecto2") }}
</p>
<ul>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.enlace") }}: </strong> <a href="http://grimario.grimorum.com">grimario.grimorum.com</a>
    </li>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.email") }}: </strong> {{ $user->email }}
    </li>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.password") }}: </strong> {{ $clave }}
    </li>
</ul>
<p>
    {{ Lang::get("userdata.emails.textos.contactese") }}
</p>
<ul>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.nombre") }}: </strong> {{ $proyect->user->name }}
    </li>
    <li>
        <strong>{{ Lang::get("userdata.emails.textos.email") }}: </strong> {{ $proyect->user->email }}
    </li>
</ul>
@stop