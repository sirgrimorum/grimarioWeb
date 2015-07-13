<?php
return array(
    'izquierdo' => array(
        //'Qué es?' => URL::to('/'),
        //'Cómo funciona?' => URL::to('/'),
        'Mis tareas' => array(
            'logedin' => true,
            'items' => array(
                'Equipos' => Lang::get("principal.menu.links.equipo"),
                'Proyectos' => Lang::get("principal.menu.links.proyecto"),
                '_' => '_',
                'Tareas' => Lang::get("principal.menu.links.tarea"),
            )
        )
    ),
    'derecho' => array(
        'Ingresar' => array(
            'logedin' => false,
            'items' => array(
                'login' => 'blade:modelos.users.login',
                '_' => '_',
                'Iniciar sesión con Linkedin' => action('OauthController@getLinkedincallback'),
                //'Iniciar sesión con Facebook' => action('OauthController@getFacebookcallback'),
                'Iniciar sesión con Google' => action('OauthController@getGooglecallback'),
                '_2' => '_',
                'Registrarme' => action('UsersController@anyRegistry'),
                'Olvidé mi clave' => '#reset',
                'reset' => 'blade:modelos.users.resetpassword'
            ),
        ),
        '<img src="{picture}" alt="{first_name}" class="img-circle foto_profile"> {first_name}' => array(
            'logedin' => true,
            'items' => array(
                'Cerrar sesion' => action('UsersController@anyLogout'),
                '_' => '_',
                'Cambiar mi clave' => action('UsersController@getNewPassword'),
            )
        ),
        App::getLocale() => array(
            'logedin' => 'NA',
            'items' => array(
                'Español' => URL::to('/') . '/es',
                'English' => URL::to('/') . '/en'
            ),
        )
    )
);

