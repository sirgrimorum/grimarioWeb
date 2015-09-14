<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{ Lang::get("user.emails.titulos.activacion") }}</h2>

        <div>
            {{ Lang::get("user.emails.textos.paraactivar") }} <a href="{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}">{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}</a>
        </div>
    </body>
</html>
