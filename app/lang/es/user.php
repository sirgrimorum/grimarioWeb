<?php

return array(
    "labels" => array(
        "usuario" => "Usuario",
        "usuarios" => "Usuarios",
        "email" => "Email",
        "password" => "Clave",
        "newpassword" => "Nueva clave",
        "newpassword2" => "Repita la clave",
        "first_name" => "Nombres",
        "last_name" => "Appelidos",
        "name" => "Nombre",
        "points" => "Puntos",
        "valueph" => "Valor de la hora",
        "ingresar" => "Entrar",
        "reset" => "Recuperar clave",
        "cambiar_clave" => "Cambiar clave",
        "remember" => "Recordarme",
        "linkedin" => "Iniciar sesión con Linkedin",
        "google" => "Iniciar sesión con Google",
    ),
    "descriptions" => array(
        "usuario" => "Usuario",
        "usuarios" => "Usuarios",
        "points" => "Puntos",
        "email" => "usuario@dominio.com",
        "password" => "******",
    ),
    "selects" => array(
        "state" => array(
            "0" => "Planeado",
            "1" => "En desarrollo",
            "2" => "Terminada",
            "3" => "Entregada",
        ),
        "type" => array(
            "0" => "Producción",
            "1" => "Desarrollo",
            "2" => "Consultoría",
            "3" => "Asesoría",
            "4" => "Taller",
            "5" => "Mentoría",
            "6" => "Reunión",
        ),
        "cuality" => array(
            "0" => "No cumple todos los indicadores",
            "1" => "Cumple todos los indicadores",
        ),
        "satisfaction" => array(
            "0" => "No cumple las expectativas",
            "1" => "Cumple las expectativas",
            "2" => "Es mejor de lo esperado",
            "3" => "Es más de lo imaginado",
        ),
    ),
    "placeholders" => array(
        "email" => "usuario@dominio.com",
        "password" => "*******",
        "newpassword" => "*******",
        "newpassword2" => "*******",
        "first_name" => "Pepito",
        "last_name" => "Pérez"
    ),
    "titulos" => array(
        "author" => "SirGrimorum",
        "title" => "Grimario, juega a controlar tu tiempo",
        "description" => "Juego, gamification, gerencia de proyectos",
        "registro" => "Registro de usuario",
        "nuevo_password" => "Nueva clave",
        "login" => "Iniciar sesión",
        "index" => "Jugadores",
        "create" => "Crear cliente",
    ),
    "mensajes" => array(
        "subject_registro" => "Bienvenido!",
        "subject_resetPassword" => "Clave reiniciada",
        "from_email" => "Grimario",
        "logout" => "Hemos cerrado su sesión",
        "login" => "Bienvenido!",
        "usuario_registrado" => "Ha sido registrado en Grimario. Le hemos enviado un correo para que active su cuenta.",
        "usuario_activado" => "Listo, ya eres un usuario activo. Disfruta!",
        "clave_cambiada" => "Listo, hemos registrado su nueva clave.",
        "clave_nocambiada" => "No ha sido posible registrar la nueva clave. Por favor vuelva a intentarlo.",
        "usuario_noactivado" => "No ha sido posible activar el usuario. Por favor vuelva a intentarlo.",
        "suspendido" => "El usuario está suspendido por *time* minutos",
        "banned" => "El usuario está reportado",
        "not_found" => "El usuario no ha sido encontrado",
        "already_activated" => "El usuario ya se encuentra activado",
        "codigor_invalido" => "El código es inválido. Vuelva a solicitarlo.",
        "no_permission" => "No tiene permisos para realizar esta acción",
        "validation" => array(
            "email.required" => "El email es obligatorio.",
            "email.exists" => "Este email ya se encuentra registrado.",
            "password.required" => "Debe ingresar su clave.",
            "password.exists" => "Clave errada."
        )
    ),
    "emails" => array(
        "titulos" => array(
            "activacion" => "Activación",
            "activacioncliente" => "Ha sido dado de alta en Grimario como dueño de proyecto",
        ),
        "textos" => array(
            "paractivar" => "Para activar su usuario, vaya a esta dirección ",
            "paractivarcliente" => "Debido a que ha contratado los servicios de <a href='http://www.grimorum.com' target='_blank'>Grimorum</a>, ha sido registrado como dueño de proyecto en nuestra herramienta de seguimiento <a href='http://grimario.grimorum.com'>Grimario</a>. Lo invitamos a activar su cuenta con los siguientes datos:",
            "cliente_email" =>"Correo electrónico",
            "cliente_clave" =>"Contraseña temporal",
            "cliente_enlace" =>"Enlace",
        )
    )
        )
?>


