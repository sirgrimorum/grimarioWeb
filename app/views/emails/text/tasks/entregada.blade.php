@extends("emails.text.plantilla")

@section("titulo")
{{ Lang::get("task.emails.titulos.entregada") }}
@stop

@section("contenido")
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("task.emails.textos.acaba_de_entregar") }} 
    {{ $task->name . " - " . $task->code }}
    {{ Lang::get("task.emails.textos.para_pago") }} 
    {{ $payment->name }}
    {{ Lang::get("task.emails.textos.de_proyecto") }} 
    {{ $payment->proyect->name . " - " . $payment->proyect->code }}
    \n
    {{ Lang::get("task.emails.textos.solicitud_evaluar") }}
    \n
    {{ URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) }}?st=cer
@stop