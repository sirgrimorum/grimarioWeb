<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Activación</h2>

        <div>
            Para activar su usuario, vaya a esta dirección <a href="{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}">{{ action("UsersController@getActivation") }}/{{ $id }}?acode={{ $activationCode }}</a>
        </div>
    </body>
</html>
