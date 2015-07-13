<?php

class UsersController extends BaseController {

    /**
     * Display a listing of groups
     *
     * @return Response
     */
    public function index() {
        $users = User::all();

        return View::make('modelos.users.index', compact('users'));
    }
    
    /**
     * Display the profile of user
     *
     * @return Response
     */
    public function getProfile($id) {
        $user = User::findOrFail($user_id);
        
        return View::make('hello', ["user"=>$user]);
    }

    /**
     * Show the form for registration
     *
     * @return Response
     */
    public function anyRegistry() {
        return View::make('modelos.users.register');
    }

    /**
     * Register a new user
     *
     * @return Response
     */
    public function postRegister() {
        $messagesV = array_merge(Lang::get("principal.mensajes.validation"), Lang::get("user.mensajes.validation"));
        $validator = Validator::make($data = Input::all(), User::$rules, $messagesV);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        try {
            $messages = new Illuminate\Support\MessageBag;
            // Let's register a user.
            $user = Sentry::register(array(
                        'email' => Input::get('email'),
                        'password' => Input::get('password'),
                        'first_name' => Input::get('first_name'),
                        'last_name' => Input::get('last_name'),
            ));
            
            // Find the group using the group id
            $adminGroup = Sentry::findGroupByName('Usuario');
            // Assign the group to the user
            $user->addGroup($adminGroup);

            // Let's get the activation code
            $activationCode = $user->getActivationCode();

            // Send activation code to the user so he can activate the account
            Mail::send('emails.auth.registro', array("activationCode" => $activationCode, "id" => $user->id), function($message) {
                $message->subject(Lang::get("user.mensajes.subject_registro"));
                $message->from('grimorum@grimorum.com', Lang::get("user.mensajes.from_email"));
                $message->to(Input::get('email'));
            });
            return Redirect::back()->with('message', Lang::get("user.mensajes.usuario_registrado"));
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            $messages->add('email.required', Lang::get("user.mensaje.validation.email.required"));
            return Redirect::back()->withErrors($messages)->withInput();
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            $messages->add('password.required', Lang::get("user.mensaje.validation.password.required"));
            return Redirect::back()->withErrors($messages)->withInput();
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            $messages->add('email.exists', Lang::get("user.mensaje.validation.email.exists"));
            return Redirect::back()->withErrors($messages)->withInput();
        }
    }

    /**
     * Activate an user.
     *
     * @return Response
     */
    public function getActivation($id) {
        if (Input::has("acode")) {
            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($id);

                // Attempt to activate the user
                if ($user->attemptActivation(Input::get("acode"))) {
                    return Redirect::back()->with('message', Lang::get("user.mensajes.usuario_activado"));
                } else {
                    $messages->add('not_activated', Lang::get("user.mensaje.usuario_noactivado"));
                    return Redirect::back()->withErrors($messages)->withInput();
                }
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensaje.not_found"));
                return Redirect::back()->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e) {
                $messages->add('already_activated', Lang::get("user.mensaje.already_activated"));
                return Redirect::back()->withErrors($messages)->withInput();
            }
        }
    }

    /**
     * logout
     *
     * @return Response
     */
    public function anyLogout() {
        Sentry::logout();
        return Redirect::to("/")->with('message', Lang::get("user.mensajes.logout"));
    }
    
    /**
     * Show de view for login
     * 
     * @return Response
     */
    public function getLogin(){
        return View::make("modelos.users.loginPlantilla");
    }

    /**
      /**
     * Store a newly created group in storage.
     *
     * @return Response
     */
    public function postLogin() {
        $messagesV = array_merge(Lang::get("user.mensajes.validation"),Lang::get("principal.mensajes.validation"));
        $validator = Validator::make(Input::all(), array(
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required|min:8',
        ),$messagesV);

        if ($validator->fails()) {
            return Response::json(array('result' => '1', 'message' => $validator->messages()->toArray()));
        }
        try {
            // Login credentials
            $credentials = array(
                'email' => Input::get('email'),
                'password' => Input::get('password'),
            );

            // Authenticate the user
            if (Input::get('loginRemember') == "remember") {
                $user = Sentry::authenticate($credentials, true);
            } else {
                $user = Sentry::authenticate($credentials, false);
            }
            return Response::json(array('result' => '0', 'message' => 'Bienvenido'));
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return Response::json(array('result' => '2', 'message' => Lang::get("user.mensajes.validation.email.required")));
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return Response::json(array('result' => '3', 'message' => Lang::get("user.mensajes.validation.password.required")));
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            return Response::json(array('result' => '3', 'message' => Lang::get("user.mensajes.validation.password.exists")));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Response::json(array('result' => '2', 'message' => Lang::get("user.mensajes.not_found")));
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            return Response::json(array('result' => '2', 'message' => Lang::get("user.mensajes.usuario_noactivado")));
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            $time = $throttle->getSuspensionTime();
            return Response::json(array('result' => '2', 'message' => str_replace("*time*", $time, Lang::get("user.mensajes.suspendido"))));
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            return Response::json(array('result' => '2', 'message' => Lang::get("user.mensajes.banned")));
        }
    }

    /**
      /**
     * Store a newly created group in storage.
     *
     * @return Response
     */
    public function postStore() {
        $validator = Validator::make($data = Input::all(), User::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        User::create($data);

        return Redirect::route('users.index');
    }

    /**
     * Reset user password.
     *
     * @param  int  $id
     * @return Response
     */
    public function postResetPassword() {
        $validator = Validator::make(Input::all(), array(
                    'email' => 'required|email|exists:users,email',
        ));

        if ($validator->fails()) {
            return Response::json(array('result' => '1', 'message' => $validator->messages()->toArray()));
        }
        try {
            // Find the user using the user email address
            $user = Sentry::findUserByLogin(Input::get('email'));

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();
            // Send activation code to the user so he can activate the account
            Mail::send('emails.auth.resetpassword', array("resetCode" => $resetCode, "id" => $user->id), function($message) {
                $message->subject(Lang::get("user.mensajes.subject_resetPassword"));
                $message->from('grimorum@grimorum.com', Lang::get("user.mensajes.from_email"));
                $message->to(Input::get('email'));
            });
            return Response::json(array('result' => '0', 'message' => 'Listo'));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Response::json(array('result' => '2', 'message' => 'User was not found.'));
        }
    }

    /**
     * Ask for a new password.
     *
     * @param  int  $id
     * @return Response
     */
    public function getNewPassword() {
        try {
            // Find the user using the user email address
            $user = Sentry::findUserById(Sentry::getUser()->id);

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();
            // Send activation code to the user so he can activate the account
            Mail::send('emails.auth.resetpassword', array("resetCode" => $resetCode, "id" => $user->id), function($message) {
                $message->subject(Lang::get("user.mensajes.subject_resetPassword"));
                $message->from('grimorum@grimorum.com', Lang::get("user.mensajes.from_email"));
                $message->to(Sentry::getUser()->email);
            });
            return Response::json(array('result' => '0', 'message' => 'Listo.'));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Response::json(array('result' => '2', 'message' => 'User was not found.'));
        }
    }

    /**
     * Activate an user.
     *
     * @return Response
     */
    public function getChangePassword($id) {
        if (Input::has("rcode")) {
            $rcode = Input::get("rcode");
        } elseif (Input::old("rcode")) {
            $rcode = Input::old("rcode");
        } else {
            $rcode = "";
        }
        if ($rcode != "") {
            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($id);

                // Check if the reset password code is valid
                if ($user->checkResetPasswordCode($rcode)) {
                    return View::make("modelos.users.changepassword", array("user" => $user, "rcode" => $rcode));
                } else {
                    $messages->add('codigor_invalido', Lang::get("user.mensajes.codigor_invalido"));
                    return Redirect::back()->withErrors($messages)->withInput();
                }
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::back()->withErrors($messages)->withInput();
            }
        }
    }

    /**
     * Activate an user.
     *
     * @return Response
     */
    public function postChangePassword() {
        if (Input::has("rcode") && Input::has("id")) {
            $messagesV = array_merge(Lang::get("user.mensajes.validation"), Lang::get("principal.mensajes.validation"));
            $validator = Validator::make(Input::all(), array(
                        'password' => 'required|min:8',
                        'password2' => 'required|min:8|same:password',
                            ), $messagesV);

            if ($validator->fails()) {
                return Redirect::to(action('UsersController@getChangePassword') . "/" . Input::get("id"))->withInput()->withErrors($validator);
            }
            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById(Input::get("id"));

                // Check if the reset password code is valid
                if ($user->checkResetPasswordCode(Input::get("rcode"))) {
                    // Attempt to reset the user password
                    if ($user->attemptResetPassword(Input::get("rcode"), Input::get("password"))) {
                        return Redirect::back()->with('message', Lang::get("user.mensajes.clave_cambiada"));
                    } else {
                        $messages->add('clave_nocambiada', Lang::get("user.mensaje.clave_nocambiada"));
                        return Redirect::back()->withErrors($messages)->withInput();
                    }
                } else {
                    $messages->add('codigor_invalido', Lang::get("user.mensaje.codigor_invalido"));
                    return Redirect::back()->withErrors($messages)->withInput();
                }
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensaje.not_found"));
                return Redirect::back()->withErrors($messages)->withInput();
            }
        }
    }

    /**
     * Display the specified group.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id) {
        $user = User::findOrFail($id);

        return View::make('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEdit($id) {
        $user = User::find($id);

        return View::make('users.edit', compact('user'));
    }

    /**
     * Update the specified group in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postUpdate($id) {
        $user = User::findOrFail($id);

        $validator = Validator::make($data = Input::all(), User::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user->update($data);

        return Redirect::route('users.index');
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postDestroy($id) {
        User::destroy($id);

        return Redirect::route('users.index');
    }

}
