<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ Lang::get("user.emails.titulos.activacioncliente") }}</h2>

        <div>
            <p>{{ Lang::get("user.emails.textos.paraactivarcliente") }}</p>
            <ul>
                <li>
                    {{ Lang::get("user.emails.textos.cliente_email") }}: {{ $email }}
                </li>
                <li>
                    {{ Lang::get("user.emails.textos.cliente_clave") }}: {{ $clave }}
                </li>
                <li>
                    {{ Lang::get("user.emails.textos.cliente_enlace") }}: <a href="{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}">{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}</a>
                </li>
            </ul>
            
        </div>
    </body>
</html>
