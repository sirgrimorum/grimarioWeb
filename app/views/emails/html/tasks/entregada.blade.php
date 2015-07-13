@extends("emails.html.plantilla")

@section("titulo")
{{ Lang::get("task.emails.titulos.entregada") }}
@stop

@section("contenido")
<p>
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("task.emails.textos.acaba_de_entregar") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.show', array($task->id)) }}" >{{ $task->name . " - " . $task->code }} </a>
    {{ Lang::get("task.emails.textos.para_pago") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.pago"). '.show', array($payment->id)) }}" >{{ $payment->name }} </a>
    {{ Lang::get("task.emails.textos.de_proyecto") }} 
    <a href="{{ URL::route(Lang::get("principal.menu.links.proyecto"). '.show', array($payment->proyect->id)) }}" >{{ $payment->proyect->name . " - " . $payment->proyect->code }} </a>
</p>
<p>
    {{ Lang::get("task.emails.textos.solicitud_evaluar") }}
    <br/>
    <a href="{{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=cer" >{{ Lang::get("task.emails.textos.evaluar"). " " . $task->code }} </a>
</p>
@stop
