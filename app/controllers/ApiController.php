<?php

class ApiController extends \BaseController {

    public function getTasks() {
        if (Input::has("user")) {
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $user = User::findOrFail(Input::get("user"));
            $userSen = Sentry::findUserById(Input::get("user"));
            $tasks = $user->tasks()->where("state", "=", "des")->orWhere("state", "=", "pau")->get();
            $taskspen = $user->tasks()->where("state","=","pla")->where("planstart","<", date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))))->get();
            if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
                $proyects = $user->userPros(" proyects.state = 'act' ");
                foreach ($user->teams()->get() as $team) {
                    $tasksT = $team->teamtasks(" tasks.state = 'des' or tasks.state = 'pau' ");
                    if ($tasksT) {
                        $tasks = $tasks->merge($tasksT);
                    }
                    $taskspenT = $team->teamtasks(" tasks.state = 'pla' and tasks.planstart < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"))) . "' ");
                    if ($taskspenT) {
                        $taskspen = $taskspen->merge($taskspenT);
                    }
                }
            }
            
            return $tasks->merge($taskspen);
        } else {
            return false;
        }
    }
    
    public function getTasksp() {
        if (Input::has("user")) {
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $user = User::findOrFail(Input::get("user"));
            $userSen = Sentry::findUserById(Input::get("user"));
            $taskspen = $user->tasks()->where("state","=","pla")->where("planstart","<", date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))))->get();
            if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
                $proyects = $user->userPros(" proyects.state = 'act' ");
                foreach ($user->teams()->get() as $team) {
                    $taskspenT = $team->teamtasks(" tasks.state = 'pla' and tasks.planstart < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"))) . "' ");
                    if ($taskspenT) {
                        $taskspen = $taskspen->merge($taskspenT);
                    }
                }
            }
            return $taskspen;
        } else {
            return false;
        }
    }

    public function anyLogout() {
        Sentry::logout();
        return Response::json(array('result' => true));
    }

    public function postLogin() {
        $messagesV = array_merge(Lang::get("user.mensajes.validation"), Lang::get("principal.mensajes.validation"));
        $validator = Validator::make(Input::all(), array(
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required|min:8',
                        ), $messagesV);

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
            return Response::json(array('result' => '0', 'user' => $user));
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

}

?>