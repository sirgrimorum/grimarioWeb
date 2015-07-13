@extends("emails.text.plantilla")

@section("titulo")
{{ Lang::get("task.emails.titulos.devuelta") }}
@stop

@section("contenido")
    {{ $userTo->name }}, {{ $user->name }} {{ Lang::get("task.emails.textos.acaba_de_devolver") }} 
    {{ $task->name . " - " . $task->code }}
    {{ Lang::get("task.emails.textos.para_pago_devuelta") }} 
    {{ $payment->name }}
    {{ Lang::get("task.emails.textos.de_proyecto") }} 
    {{ $payment->proyect->name . " - " . $payment->proyect->code }}
    \n
    {{ Lang::get("task.emails.textos.solicitud_retomar") }}
@stop