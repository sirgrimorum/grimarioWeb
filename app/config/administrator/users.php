<?php

return array(
    'title' => Lang::get("user.labels.usuarios"),
    'single' => Lang::get("user.labels.usuario"),
    'model' => 'User',
    'columns' => array(
        'email' => array(
            'title' => Lang::get("user.labels.email")
        ),
        'activated' => array(
            'title' => Lang::get("user.labels.activated")
        ),
        'first_name' => array(
            'title' => Lang::get("user.labels.first_name")
        ),
        'last_name' => array(
            'title' => Lang::get("user.labels.last_name")
        ),
        'last_login' => array(
            'title' => Lang::get("user.labels.last_login"),
        ),
        'activated_at' => array(
            'title' => Lang::get("user.labels.activated_at"),
        ),
        'created_at' => array(
            'title' => Lang::get("user.labels.created_at"),
        ),
    ),
    'edit_fields' => array(
        'email' => array(
            'title' => Lang::get("user.labels.email"),
            'type' => 'text',
        ),
        'activated' => array(
            'title' => Lang::get("user.labels.activated"),
            'type' => 'bool',
        ),
        'first_name' => array(
            'title' => Lang::get("user.labels.first_name"),
            'type' => 'text',
        ),
        'last_name' => array(
            'title' => Lang::get("user.labels.last_name"),
            'type' => 'text',
        ),
        'name' => array(
            'title' => Lang::get("user.labels.name"),
            'type' => 'text',
        ),
        'valueph' => array(
            'title' => Lang::get("user.labels.valueph"),
            'type' => 'number',
            'decimals' => 2,
        ),
        'groups' => array(
            'title' => Lang::get("user.labels.groups"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => true,
        ),
    ),
    'filters' => array(
        'email' => array(
            'title' => Lang::get("user.labels.email"),
        ),
        'activated' => array(
            'title' => Lang::get("user.labels.activated"),
            'type' => 'bool',
        ),
        'activated_at' => array(
            'title' => Lang::get("user.labels.activated_at"),
            'type' => 'date',
        ),
        'created_at' => array(
            'title' => Lang::get("user.labels.activated_at"),
            'type' => 'date',
        ),
        'groups' => array(
            'title' => Lang::get("user.labels.groups"),
            'type' => 'relationship',
            'name_field' => 'name'
        ),
    ),
    'rules' => array(
        'email' => 'required|email|unique:user,email',
        'first_name' => 'required',
        'last_name' => 'required',
    ),
    'messages' => array(
    /* 'name.required' => 'Es obligatorio establecer un nombre para la imagen',
      'image.required' => 'Es obligatorio seleccionar una imagen', */
    ),
    'sort' => array(
        'field' => 'email',
        'direction' => 'desc',
    ),
    'form_width' => 600,
    'actions' => array(
        //change password
        'change_password' => array(
            'title' => Lang::get("user.labels.cambiar_clave"),
            'messages' => array(
                'active' => Lang::get("user.mensajes.cambiando_clave"),
                'success' => Lang::get("user.mensajes.clave_cambiada"),
                'error' => Lang::get("user.mensajes.clave_nocambiada"),
            ),
            'permission' => function($model) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            //return true;
            return $user->hasAccess('admin');
        } else {
            return false;
        }
    },
            //the model is passed to the closure
            'action' => function($model) {
        $user = Sentry::findUserById($model->id);

        // Get the password reset code
        $resetCode = $user->getResetPasswordCode();
        // Send activation code to the user so he can activate the account
        Mail::send('emails.auth.resetpassword', array("resetCode" => $resetCode, "id" => $user->id), function($message) {
            $message->subject(Lang::get("user.mensajes.subject_resetPassword"));
            $message->from('grimorum@grimorum.com', Lang::get("user.mensajes.from_email"));
            $message->to(Sentry::getUser()->email);
        });
    }
        ),
        //ban user
        'ban_user' => array(
            'title' => Lang::get("user.labels.ban"),
            'messages' => array(
                'active' => Lang::get("user.mensajes.baneando"),
                'success' => Lang::get("user.mensajes.baned"),
                'error' => Lang::get("user.mensajes.no_baned"),
            ),
            'permission' => function($model) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            //return true;
            return $user->hasAccess('admin');
        } else {
            return false;
        }
    },
            //the model is passed to the closure
            'action' => function($model) {
        $user = Sentry::findUserById($model->id);

        $user->ban();
    }
        ),
        //unban user
        'unban_user' => array(
            'title' => Lang::get("user.labels.unban"),
            'messages' => array(
                'active' => Lang::get("user.mensajes.unbaneando"),
                'success' => Lang::get("user.mensajes.unbaned"),
                'error' => Lang::get("user.mensajes.no_unbaned"),
            ),
            'permission' => function($model) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            //return true;
            return $user->hasAccess('admin');
        } else {
            return false;
        }
    },
            //the model is passed to the closure
            'action' => function($model) {
        $user = Sentry::findUserById($model->id);

        $user->unBan();
    }
        ),
        //suspend user
        'suspend_user' => array(
            'title' => Lang::get("user.labels.suspend"),
            'messages' => array(
                'active' => Lang::get("user.mensajes.suspendiendo"),
                'success' => Lang::get("user.mensajes.suspendido"),
                'error' => Lang::get("user.mensajes.no_suspendido"),
            ),
            'permission' => function($model) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            //return true;
            return $user->hasAccess('admin');
        } else {
            return false;
        }
    },
            //the model is passed to the closure
            'action' => function($model) {
        $user = Sentry::findUserById($model->id);

        $user->suspend();
    }
        ),
        //unsuspend user
        'unsuspend_user' => array(
            'title' => Lang::get("user.labels.unsuspend"),
            'messages' => array(
                'active' => Lang::get("user.mensajes.unsuspendiendo"),
                'success' => Lang::get("user.mensajes.unsuspendido"),
                'error' => Lang::get("user.mensajes.no_unsuspendido"),
            ),
            'permission' => function($model) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            //return true;
            return $user->hasAccess('admin');
        } else {
            return false;
        }
    },
            //the model is passed to the closure
            'action' => function($model) {
        $user = Sentry::findUserById($model->id);

        $user->unsuspend();
    }
        ),
    ),
);



