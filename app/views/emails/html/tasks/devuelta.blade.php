@extends("emails.html.plantilla")

@section("titulo")
{{ Lang::get("task.emails.titulos.devuelta") }}
@stop

@section("contenido")
<p>
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("task.emails.textos.acaba_de_devolver") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.show', array($task->id)) }}" >{{ $task->name . " - " . $task->code }} </a>
    {{ Lang::get("task.emails.textos.para_pago_devuelta") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.pago"). '.show', array($payment->id)) }}" >{{ $payment->name }} </a>
    {{ Lang::get("task.emails.textos.de_proyecto") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto"). '.show', array($payment->proyect->id)) }}" >{{ $payment->proyect->name . " - " . $payment->proyect->code }} </a>
</p>
<p>
    {{ Lang::get("task.emails.textos.solicitud_retomar") }}
    <br/>
</p>
@stop

