<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Activación</h2>

        <div>
            Para cambiar su clave, vaya a esta dirección <a href="{{ action("UsersController@getChangePassword") }}/{{ $id }}?rcode={{ $resetCode }}">{{ action("UsersController@getChangePassword") }}/{{ $id }}?rcode={{ $resetCode }}</a>
        </div>
    </body>
</html>
