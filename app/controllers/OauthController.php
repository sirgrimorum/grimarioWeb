<?php

class OauthController extends BaseController {

    public function getIndex() {
        return View::make("oauth.index");
    }

    public function getGooglecallback() {
        $code = Input::get('code');
        if (Session::has("redirect")) {
            $redirect = Session::get("redirect");
        } else {
            $redirect = "/";
        }
        $googleService = OAuth::consumer('Google');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {
            
            $googleService->setAccessType("offline");
            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

            //Return "<pre>" . print_r($result, true) . "</pre><br/><pre>" . print_r($token,true) . "</pre>";

            $uid = $result['id'];

            $profile = Profile::where("uid", "=", $uid)->first();

            if (empty($profile)) {
                $sinGrupo = false;
                try {
                    $userSen = Sentry::findUserByLogin($result['email']);
                    $user = User::findOrFail($userSen->id);
                    $sinGrupo = true;
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    $user = new User;
                    $user->password = str_random(20);
                }
                if (isset($result['name'])) {
                    $user->name = $result['name'];
                }
                if (isset($result['given_name'])) {
                    $user->first_name = $result['given_name'];
                }
                if (isset($result['family_name'])) {
                    $user->last_name = $result['family_name'];
                }
                if (isset($result['email'])) {
                    $user->email = $result['email'];
                }
                $user->picture = $result['picture'];
                if (isset($result['verified_email'])) {
                    $user->activated = $result['verified_email'];
                }
                $user->save();

                $userSen = Sentry::findUserByLogin($result['email']);
                if ($sinGrupo) {
                    // Find the group using the group id
                    $adminGroup = Sentry::findGroupByName('Jugador');

                    // Assign the group to the user
                    $userSen->addGroup($adminGroup);
                }

                $profile = new Profile();
                $profile->uid = $uid;
                $profile->username = $result['id'];
                $profile->data = json_encode($result);
                $profile->type = "Google";
                $profile = $user->profiles()->save($profile);
            }
            $profile->update_c = time();
            $profile->token_json = json_encode(toDataObj($token));
            $profile->access_token = getReflectedPropertyValue($token, 'accessToken');
            $profile->save();

            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($profile->user_id);
                // Log the user in
                Sentry::login($user, false);
                return Redirect::to($redirect)->with('message', Lang::get("user.mensajes.login"));
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                $messages->add('email.required', Lang::get("user.mensajes.validation.email.required"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                $messages->add('usuario_noactivado', Lang::get("user.mensajes.usuario_noactivado"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                $time = $throttle->getSuspensionTime();
                $messages->add('suspendido', str_replace("*time*", $time, Lang::get("user.mensajes.suspendido")));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                $messages->add('banned', Lang::get("user.mensajes.banned"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            }
        }
        // if not ask for permission first
        else {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri();
            // return to google login url
            return Redirect::to((string) $url);
        }
    }

    public function getFacebookcallback() {
        $code = Input::get('code');
        if (Session::has("redirect")) {
            $redirect = Session::get("redirect");
        } else {
            $redirect = "/";
        }
        $facebookService = OAuth::consumer('Facebook');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {

            // This was a callback request from google, get the token
            $token = $facebookService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($facebookService->request('/me'), true);

            //Return "<pre>" . print_r($result, true) . "</pre><br/><pre>" . print_r($token,true) . "</pre>";

            $uid = $result['id'];

            $profile = Profile::where("uid", "=", $uid)->first();

            if (empty($profile)) {
                try {
                    $user = Sentry::findUserByLogin($result['email']);
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    $user = new User;
                    $user->password = str_random(20);
                }
                if (isset($result['name'])) {
                    $user->name = $result['name'];
                }
                if (isset($result['given_name'])) {
                    $user->first_name = $result['given_name'];
                }
                if (isset($result['family_name'])) {
                    $user->last_name = $result['family_name'];
                }
                if (isset($result['email'])) {
                    $user->email = $result['email'];
                }
                $user->picture = $result['picture'];
                if (isset($result['verified_email'])) {
                    $user->activated = $result['verified_email'];
                }
                $user->save();

                $userSen = Sentry::findUserByLogin($result['emailAddress']);
                // Find the group using the group id
                $adminGroup = Sentry::findGroupByName('Usuario');

                $profile = new Profile();
                $profile->uid = $uid;
                $profile->username = $result['id'];
                $profile->data = json_encode($result);
                $profile->type = "Facebook";
                $profile = $user->profiles()->save($profile);
                /* Adicionando mas informacion del perfil */
            }
            $profile->access_token = getReflectedPropertyValue($token, 'accessToken');
            $profile->save();

            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($profile->user_id);
                // Log the user in
                Sentry::login($user, false);
                return Redirect::to($redirect)->with('message', Lang::get("user.mensajes.login"));
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                $messages->add('email.required', Lang::get("user.mensajes.validation.email.required"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                $messages->add('usuario_noactivado', Lang::get("user.mensajes.usuario_noactivado"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                $time = $throttle->getSuspensionTime();
                $messages->add('suspendido', str_replace("*time*", $time, Lang::get("user.mensajes.suspendido")));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                $messages->add('banned', Lang::get("user.mensajes.banned"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            }
        }
        // if not ask for permission first
        else {
            // get googleService authorization
            $url = $facebookService->getAuthorizationUri();

            // return to google login url
            return Redirect::to((string) $url);
        }
    }

    public function getTwittercallback() {
        if (Session::has("redirect")) {
            $redirect = Session::get("redirect");
        } else {
            $redirect = "/";
        }
        // get data from input
        $token = Input::get('oauth_token');
        $verify = Input::get('oauth_verifier');

        // get twitter service
        $tw = OAuth::consumer('Twitter');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($token) && !empty($verify)) {

            // This was a callback request from twitter, get the token
            $token = $tw->requestAccessToken($token, $verify);

            // Send a request with it
            $result = json_decode($tw->request('account/verify_credentials.json'), true);

            //Return "<pre>" . print_r($result, true) . "</pre><br/><pre>" . print_r($token, true) . "</pre>";

            $uid = $result['id'];

            $profile = Profile::where("uid", "=", $uid)->first();

            if (empty($profile)) {
                $user = new User;
                $user->password = str_random(20);
                if (isset($result['name'])) {
                    $user->name = $result['name'];
                }

                $user->picture = $result['profile_image_url'];
                $user->save();

                $userSen = Sentry::findUserByLogin($result['emailAddress']);
                // Find the group using the group id
                $adminGroup = Sentry::findGroupByName('Usuario');

                $profile = new Profile();
                $profile->uid = $uid;
                $profile->username = $result['screen_name'];
                $profile->data = json_encode($result);
                $profile->type = "Twitter";
                $profile = $user->profiles()->save($profile);
                /* Adicionando mas informacion del perfil */
            }
            $profile->access_token = getReflectedPropertyValue($token, 'requestToken');
            $profile->save();

            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($profile->user_id);
                // Log the user in
                Sentry::login($user, false);
                return Redirect::to($redirect)->with('message', Lang::get("user.mensajes.login"));
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                $messages->add('email.required', Lang::get("user.mensajes.validation.email.required"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                $messages->add('usuario_noactivado', Lang::get("user.mensajes.usuario_noactivado"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                $time = $throttle->getSuspensionTime();
                $messages->add('suspendido', str_replace("*time*", $time, Lang::get("user.mensajes.suspendido")));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                $messages->add('banned', Lang::get("user.mensajes.banned"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            }
        }
        // if not ask for permission first
        else {
            // get request token
            $reqToken = $tw->requestRequestToken();

            // get Authorization Uri sending the request token
            $url = $tw->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));

            // return to twitter login url
            return Redirect::to((string) $url);
        }
    }

    public function getLinkedincallback() {
        $code = Input::get('code');
        if (Session::has("redirect")) {
            $redirect = Session::get("redirect");
        } else {
            $redirect = "/";
        }
        $linkedinService = OAuth::consumer('Linkedin');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {

            // This was a callback request from google, get the token
            $token = $linkedinService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($linkedinService->request('/people/~:(id,first-name,last-name,formatted-name,headline,location,summary,picture-url,email-address,public-profile-url)?format=json'), true);

            //return Response::json($result);

            $uid = $result['id'];

            $profile = Profile::where("uid", "=", $uid)->first();

            if (empty($profile)) {
                $sinGrupo = false;
                try {
                    $userSen = Sentry::findUserByLogin($result['emailAddress']);
                    $user = User::find($userSen->id);
                    $sinGrupo = true;
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    $user = new User;
                    $user->password = str_random(20);
                }
                if (isset($result['formattedName'])) {
                    $user->name = $result['formattedName'];
                }
                if (isset($result['firstName'])) {
                    $user->first_name = $result['firstName'];
                }
                if (isset($result['lastName'])) {
                    $user->last_name = $result['lastName'];
                }
                if (isset($result['emailAddress'])) {
                    $user->email = $result['emailAddress'];
                }
                $user->picture = $result['pictureUrl'];

                $user->activated = true;
                $user->save();

                $userSen = Sentry::findUserByLogin($result['emailAddress']);
                if ($sinGrupo) {
                    // Find the group using the group id
                    $adminGroup = Sentry::findGroupByName('Jugador');
                    
                    // Assign the group to the user
                    $userSen->addGroup($adminGroup);
                }
                $profile = new Profile();
                $profile->uid = $uid;
                $profile->username = $result['id'];
                $profile->data = json_encode($result);
                $profile->type = "Linkedin";
                $profile = $user->profiles()->save($profile);
                /* Adicionando mas informacion del perfil */
            }
            //Return "<pre>" . print_r($token,true) . "</pre>";
            $profile->access_token = getReflectedPropertyValue($token, 'accessToken');
            $profile->save();

            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($profile->user_id);
                // Log the user in
                Sentry::login($user, false);
                return Redirect::to($redirect)->with('message', Lang::get("user.mensajes.login"));
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                $messages->add('email.required', Lang::get("user.mensajes.validation.email.required"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                $messages->add('usuario_noactivado', Lang::get("user.mensajes.usuario_noactivado"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                $time = $throttle->getSuspensionTime();
                $messages->add('suspendido', str_replace("*time*", $time, Lang::get("user.mensajes.suspendido")));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                $messages->add('banned', Lang::get("user.mensajes.banned"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            }
        }
        // if not ask for permission first
        else {
            // get googleService authorization
            $url = $linkedinService->getAuthorizationUri();

            // return to google login url
            return Redirect::to((string) $url);
        }
    }

    public function getYahoocallback() {
        $token = Input::get('oauth_token');
        $verify = Input::get('oauth_verifier');
        if (Session::has("redirect")) {
            $redirect = Session::get("redirect");
        } else {
            $redirect = "/";
        }
        $yahooService = OAuth::consumer('Yahoo');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($token) && !empty($verify)) {

            // This was a callback request from google, get the token
            $token = $yahooService->requestAccessToken($token, $verify);
            $xid = array($token->getExtraParams());
            // Send a request with it
            $result = json_decode($yahooService->request('https://social.yahooapis.com/v1/user/' . $xid[0]['xoauth_yahoo_guid'] . '/profile?format=json'), true);

            //Return "<pre>" . print_r($result, true) . "</pre><br/><pre>" . print_r($token,true) . "</pre>";

            $uid = $result['id'];

            $profile = Profile::where("uid", "=", $uid)->first();

            if (empty($profile)) {
                try {
                    $user = Sentry::findUserByLogin($result['email']);
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    $user = new User;
                }
                if (isset($result['name'])) {
                    $user->name = $result['name'];
                }
                if (isset($result['given_name'])) {
                    $user->first_name = $result['given_name'];
                }
                if (isset($result['family_name'])) {
                    $user->last_name = $result['family_name'];
                }
                if (isset($result['email'])) {
                    $user->email = $result['email'];
                }
                $user->picture = $result['picture'];
                if (isset($result['verified_email'])) {
                    $user->activated = $result['verified_email'];
                }
                $user->save();

                $userSen = Sentry::findUserByLogin($result['emailAddress']);
                // Find the group using the group id
                $adminGroup = Sentry::findGroupByName('Usuario');

                $profile = new Profile();
                $profile->uid = $uid;
                $profile->username = $result['id'];
                $profile->data = json_encode($result);
                $profile->type = "Yahoo";
                $profile = $user->profiles()->save($profile);
                /* Adicionando mas informacion del perfil */
            }
            $profile->access_token = getReflectedPropertyValue($token, 'accessToken');
            $profile->save();

            try {
                $messages = new Illuminate\Support\MessageBag;
                // Find the user using the user id
                $user = Sentry::findUserById($profile->user_id);
                // Log the user in
                Sentry::login($user, false);
                return Redirect::to($redirect)->with('message', Lang::get("user.mensajes.login"));
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                $messages->add('email.required', Lang::get("user.mensajes.validation.email.required"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                $messages->add('not_found', Lang::get("user.mensajes.not_found"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                $messages->add('usuario_noactivado', Lang::get("user.mensajes.usuario_noactivado"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                $time = $throttle->getSuspensionTime();
                $messages->add('suspendido', str_replace("*time*", $time, Lang::get("user.mensajes.suspendido")));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                $messages->add('banned', Lang::get("user.mensajes.banned"));
                return Redirect::to($redirect)->withErrors($messages)->withInput();
            }
        }
        // if not ask for permission first
        else {
            // get request token
            $reqToken = $yahooService->requestRequestToken();
            // get Authorization Uri sending the request token
            $url = $yahooService->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));
            // return to yahoo login url
            // return to google login url
            return Redirect::to((string) $url);
        }
    }

}
