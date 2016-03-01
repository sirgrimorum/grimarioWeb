<?php

class ApiController extends \BaseController {

    public function getTasks() {
        if (Input::has("user")) {
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $user = User::findOrFail(Input::get("user"));
            $userSen = Sentry::findUserById(Input::get("user"));
            $tasks = $user->tasks()->where("state", "=", "des")->orWhere("state", "=", "pau")->get();
            $taskspen = $user->tasks()->where("state", "=", "pla")->where("planstart", "<", date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"))))->get();
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
            $tasks = $tasks->merge($taskspen);
            //$tasks=$taskspen;
            $resultado = array(
                "tasks" => array(),
                "proyects" => array()
            );
            foreach ($tasks->sortBy('planstart') as $task) {

                $work = $task->works()->where('user_id', '=', $user->id)->whereRaw("YEAR(end) = 0 and start < NOW()")->orderBy('start', 'desc')->first();
                if ($work && $task->state == 'pau') {
                    $task->state = 'des';
                    $task->save();
                }
                $item = [
                    'id' => $task->id,
                    'name' => $task->name,
                    'state' => $task->state,
                    'statestr' => Lang::get('task.selects.state.' . $task->state),
                    'proyect' => $task->proyect->name,
                    'proyect_id' => $task->proyect->id,
                    'payment' => $task->payments()->first()->name,
                    'result' => $task->result,
                    'description' => $task->description,
                    'percentage' => $task->percentage,
                    'dpercentage' => $task->dpercentage,
                    'type' => $task->tasktype->name,
                    'othercosts' => $task->othercosts(),
                    'totalcost' => $task->totalcost(),
                    'profit' => $task->profit(),
                    'totalhours' => $task->totalhours(),
                    'elapsedtime' => $task->elapsedtime(),
                    'timeleft' => $task->timeleft(),
                    'plan' => $task->plan,
                    'planstart' => $task->planstart,
                    'expenses' => $task->expenses,
                    'pcuantity' => $task->pcuantity,
                    'start' => $task->start,
                    'end' => $task->end,
                    'fav' => false,
                ];
                if ($task->state == "des" || $task->state == "pau" || $task->state == "pla") {
                    if ($task->timeleft() < 0) {
                        $item['timeleftstr'] = Lang::get("task.labels.retrasado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias");
                    } else {
                        $item['timeleftstr'] = Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias");
                    }
                } elseif ($task->state == "ter") {
                    if ($task->timeleft() < 0) {
                        $item['timeleftstr'] = Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues");
                    } else {
                        $item['timeleftstr'] = Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes");
                    }
                } else {
                    if ($task->timeleft() < 0) {
                        $item['timeleftstr'] = Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues");
                    } else {
                        $item['timeleftstr'] = Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes");
                    }
                }
                if ($work) {
                    $item['conwork'] = true;
                    $item['work'] = [
                        'id' => $work->id,
                        'name' => $work->name,
                        'start' => $work->start,
                        'elapsedtime' => $work->elapsedtime()
                    ];
                } else {
                    $item['conwork'] = false;
                }
                $item['works'] = array();
                foreach ($task->works()->get() as $work2) {
                    $itemw = [
                        'id' => $work2->id,
                        'name' => $work2->name,
                        'start' => $work2->start,
                        'end' => $work2->end,
                        'coordinator' => $work2->coordinator->id,
                        'elapsedtime' => $work2->elapsedtime(),
                        'totalworkedhours' => $work2->totalworkedhours(),
                        'totalcost' => $work2->totalcost(),
                    ];
                    array_push($item['works'], $itemw);
                }
                $item['comments'] = $task->comments()->count();

                $item['players'] = array();
                foreach ($task->users()->get() as $user2) {
                    $itemp = [
                        'id' => $user2->id,
                        'name' => $user2->name,
                        'responsability' => $user2->responsability,
                        'workedhours' => $task->workedhours($user2->id),
                        'picture' => $user2->picture,
                    ];
                    array_push($item['players'], $itemp);
                }
                $item['machines'] = array();
                foreach ($task->proyect->enterprises()->first()->machines()->get() as $machine) {
                    $itemm = [
                        'id' => $machine->id,
                        'name' => $machine->name,
                        'description' => $machine->description,
                        'valueph' => $machine->valueph,
                    ];
                    array_push($item['machines'], $itemm);
                }
                $item['resources'] = array();
                foreach ($task->proyect->enterprises()->first()->resources()->get() as $resource) {
                    $itemr = [
                        'id' => $resource->id,
                        'name' => $resource->name,
                        'description' => $resource->description,
                        'value' => $resource->value,
                        'measure' => $resource->measure,
                    ];
                    array_push($item['resources'], $itemr);
                }
                $item['commenttypes'] = array();
                foreach ($task->tasktype->commenttypes()->get() as $commenttype) {
                    $itemc = [
                        'id' => $commenttype->id,
                        'name' => $commenttype->name,
                        'description' => $commenttype->description,
                    ];
                    array_push($item['commenttypes'], $itemc);
                }

                $item['coststype'] = array();
                foreach (Lang::get("cost.selects.type") as $id => $name) {
                    $itemct = [
                        'id' => $id,
                        'name' => $name,
                    ];
                    array_push($item['coststype'], $itemct);
                }
                $item['costsrubro'] = array();
                foreach (Lang::get("cost.selects.rubro") as $id => $name) {
                    $itemcr = [
                        'id' => $id,
                        'name' => $name,
                    ];
                    array_push($item['costsrubro'], $itemcr);
                }

                array_push($resultado["tasks"], $item);
            }
            $proyects = $user->userPros(" proyects.state = 'act' ");
            if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
                //$proyects = Proyect::whereRaw(" proyects.state = 'act' ")->get();
                $proyectsa = $user->proyects()->whereRaw(" proyects.state = 'act' ")->get();
                $proyectsb = $user->userPros(" proyects.state = 'act' ");
                $proyects = $proyectsa->merge($proyectsb);
            }
            if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                $proyects = Proyect::whereRaw(" proyects.state = 'act' ")->get();
            }
            foreach ($proyects->sortBy('name') as $proyect) {
                $item = [
                    'id' => $proyect->id,
                    'name' => $proyect->name,
                    'code' => $proyect->code,
                    'state' => $proyect->state,
                    'statestr' => Lang::get('proyect.selects.state.' . $proyect->state),
                    'priority' => $proyect->priority,
                    'prioritystr' => Lang::get('proyect.selects.priority.' . $proyect->priority),
                    'type' => $proyect->type,
                    'typestr' => Lang::get('proyect.selects.type.' . $proyect->type),
                    'pop' => $proyect->pop,
                    'description' => $proyect->description,
                    'problem' => $proyect->problem,
                    'resources' => $proyect->resources,
                    'experience' => $proyect->experience,
                    'satisfaction' => $proyect->satisfaction,
                    'advance' => $proyect->advance(),
                    'totalplan' => $proyect->totalplan(),
                    'totalcost' => $proyect->totalcost(),
                    'saves' => $proyect->saves(),
                    'value' => $proyect->value(),
                    'profit' => $proyect->profit(),
                    'totalplanhours' => $proyect->totalplanhours(),
                    'totalhours' => $proyect->totalhours(),
                    'saveshours' => $proyect->saveshours(),
                ];
                if ($proyect->user) {
                    $item['user_id'] = $proyect->user->id;
                    $item['user'] = $proyect->user->name;
                    $item['user_picture'] = $proyect->user->picture;
                } else {
                    $item['user_id'] = 0;
                    $item['user'] = "NN";
                    $item['user_picture'] = "";
                }
                $item['teams'] = array();
                foreach ($proyect->teams()->get() as $team) {
                    $itemt = [
                        'id' => $team->id,
                        'name' => $team->name,
                        'description' => $team->description,
                    ];
                    array_push($item['teams'], $itemt);
                }
                array_push($resultado["proyects"], $item);
            }
            return json_encode($resultado);
        } else {
            return false;
        }
    }

    public function getProyects() {
        if (Input::has("user")) {
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $user = User::findOrFail(Input::get("user"));
            $userSen = Sentry::findUserById(Input::get("user"));
        }
    }

    public function getCosts() {
        if (Input::has("task")) {
            $task = Task::findOrFail(Input::get("task"));
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $resultado = array();
            foreach ($task->works()->get() as $work) {
                foreach ($work->costs()->get() as $cost) {

                    $item = [
                        'id' => $cost->id,
                        'work_id' => $work->id,
                        'work_name' => $work->name,
                        'date' => $cost->date,
                        'type' => $work->type,
                        'typestr' => Lang::get('cost.selects.type.' . $cost->type),
                        'rubro' => $cost->type,
                        'rubrostr' => Lang::get('cost.selects.rubro.' . $cost->rubro),
                        'description' => $cost->description,
                        'code' => $cost->code,
                        'value' => $cost->value,
                    ];
                    if ($cost->user) {
                        $item['user_id'] = $cost->user->id;
                        $item['user'] = $cost->user->name;
                        $item['user_picture'] = $cost->user->picture;
                    } else {
                        $item['user_id'] = 0;
                        $item['user'] = "NN";
                        $item['user_picture'] = "";
                    }
                    array_push($resultado, $item);
                }
            }
            return json_encode($resultado);
        } else {
            return false;
        }
    }

    public function getComments() {
        if (Input::has("task")) {
            $task = Task::findOrFail(Input::get("task"));
            //$userSen = Sentry::getUser();
            //$user = User::findOrFail($userSen->id);
            $resultado = array();
            foreach ($task->comments()->get() as $comment) {
                $item = [
                    'id' => $comment->id,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                    'date' => $comment->date,
                    'type' => $comment->commenttype->id,
                    'typestr' => $comment->commenttype->name,
                    'comment' => $comment->comment,
                    'public' => $comment->public,
                ];
                if ($comment->image != "") {
                    if (preg_match('/(\.jpeg|\.jpg|\.png|\.bmp)$/', $comment->image)) {
                        $item['image'] = asset("images/comments/" . $comment->image);
                        $item['thumb'] = asset("images/comments/thumb/" . $comment->image);
                        $item['url'] = "";
                        $item['file'] = asset("images/comments/" . $comment->image);
                    } else {
                        $item['image'] = asset('/images/img/file.png');
                        $item['thumb'] = asset('/images/img/file.png');
                        $item['url'] = "";
                        $item['file'] = asset("images/comments/" . $comment->image);
                    }
                } elseif ($comment->url != "") {
                    if (strpos($comment->url, "youtube") === false) {
                        $item['image'] = asset('/images/img/file.png');
                        $item['thumb'] = asset('/images/img/file.png');
                        $item['url'] = $comment->url;
                        $item['file'] = "";
                    } else {
                        parse_str(parse_url($comment->url, PHP_URL_QUERY), $arrVariables);
                        $item['image'] = "http://i3.ytimg.com/vi/" . $arrVariables["v"] . "/default.jpg";
                        $item['thumb'] = "http://i3.ytimg.com/vi/" . $arrVariables["v"] . "/default.jpg";
                        $item['url'] = $comment->url;
                        $item['file'] = "";
                    }
                } else {
                    $item['image'] = "";
                    $item['thumb'] = "img/comment.png";
                    $item['url'] = "";
                    $item['file'] = "";
                }
                if ($comment->user) {
                    $item['user_id'] = $comment->user->id;
                    $item['user'] = $comment->user->name;
                    $item['user_picture'] = $comment->user->picture;
                } else {
                    $item['user_id'] = 0;
                    $item['user'] = "NN";
                    $item['user_picture'] = "";
                }
                array_push($resultado, $item);
            }
            return json_encode($resultado);
        } else {
            return false;
        }
    }

    public function postPautask() {
        $task = Task::findOrFail(Input::get("task_id"));
        $data = $task->getAttributes();
        $data['state'] = 'pau';
        $data['dpercentage'] = Input::get('dpercentage');
        $work = Work::findOrFail(Input::get('work_id'));
        $datosWork = $work->getAttributes();
        $datosWork['name'] = Input::get('name');
        if (!Input::has('end')) {
            $datosWork['end'] = date("Y-m-d H:i:s");
        }
        if (Input::has('start')) {
            $datosWork['start'] = Input::get('start');
        }
        $validator = Validator::make($datosWork, Work::$rules);
        if ($validator->fails()) {
            return Response::json(array('result' => 1, 'errors' => $validator));
        }
        $work->update($datosWork);
        $workers = json_decode(Input::get('work_users'), true);
        if (is_array($workers)) {
            foreach ($workers as $worker_id => $valor_sel) {
                $worker = User::find($worker_id);
                $work->users()->save($worker, ['hours' => $valor_sel]);
            }
        }
        $machines = json_decode(Input::get('work_machines'), true);
        if (is_array($machines)) {
            foreach ($machines as $machine_id => $valor_sel) {
                $machine = Machine::find($machine_id);
                $work->machines()->save($machine, ['hours' => $valor_sel]);
            }
        }
        $resources = json_decode(Input::get('work_resources'), true);
        if (is_array($resources)) {
            foreach ($resources as $resource_id => $valor_sel) {
                $resource = Resource::find($resource_id);
                $work->resources()->save($resource, ['cuantity' => $valor_sel]);
            }
        }
        $validator = Validator::make($data, Task::$rules);
        //return "<pre>" . print_r($data,true) . "</pre><p>Otro</p><pre>" . print_r($task->getAttributes(),true) . "</pre>";
        if ($validator->fails()) {
            return Response::json(array('result' => 2, 'errors' => $validator));
        }
        $task->update($data);
        return Response::json(array('result' => 0, 'input' => Input::all()));
    }

    public function postStarttask() {
        $user = User::findOrFail(Input::get("user"));
        $userSen = Sentry::findUserById(Input::get("user"));
        $task = Task::findOrFail(Input::get("task_id"));
        $data = $task->getAttributes();
        $comienzo = date("Y-m-d H:i:s");
        if ($task->state == "pla") {
            $data['start'] = $comienzo;
            $payment = $task->payments()->first();
            if ($payment->state == 'pla' || $payment->state == 'cre') {
                $payment->state = 'act';
                $payment->save();
            }
            $proyect = $task->proyect;
            if ($proyect->state == 'pla' || $proyect->state == 'cre') {
                $proyect->state = 'act';
                $proyect->save();
            }
        }
        $data['state'] = 'des';
        $validator = Validator::make($data, Task::$rules);
        if ($validator->fails()) {
            return Response::json(array('result' => 1, 'errors' => $validator));
        }
        $task->update($data);
        $work = new Work;
        $work->start = $comienzo;
        $work->task()->associate($task);
        $work->coordinator()->associate($user);
        $work->save();
        return Response::json(array('result' => 0, 'input' => Input::all()));
    }

    public function postCreatecost() {
        $data = Input::except('_token');
        if (!Input::has('date')) {
            $data['date'] = date("Y-m-d H:i:s");
        }

        $validator = Validator::make($data, Cost::$rules);
        if ($validator->fails()) {
            return Response::json(array('result' => 1, 'errors' => $validator, 'input' => Input::all()));
        }
        Cost::create($data);
        return Response::json(array('result' => 0, 'input' => Input::all()));
    }

    public function postCreatecomment() {
        $data = Input::except('image', 'redirect', 'commenttype');
        $data['type'] = Input::get('commenttype');
        $data['date'] = date("Y-m-d H:i:s");
        $validator = Validator::make($data, Comment::$rules);

        if ($validator->fails()) {
            return Response::json(array('result' => 1, 'errors' => $validator->messages(), 'input' => Input::all()));
        }

        $file = Input::file('image');

        if ($file) { //Input::has('image')) {
            //$dataImg = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
            if (substr($file->getMimeType(), 0, 5) == 'image') {
                $esImagen = true;
            } else {
                $esImagen = false;
            }
            $destinationPath = public_path() . '/images/comments/';
            //$filename = $file->getClientOriginalName();
            $filename = str_random(20) . ".jpg";


            //$upload_success = file_put_contents($destinationPath, $dataImg);
            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success != false) {
                if ($esImagen) {
                    // resizing an uploaded file
                    Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . "thumb/" . $filename, 100);

                    //return Response::json('success', 200);
                }
            } else {
                return Response::json(array('result' => 2, 'errors' => $validator, 'input' => Input::all()));
            }
            $data = Input::except('_token', 'image', 'redirect', 'commenttype');
            $data['type'] = Input::get('commenttype');
            $data['date'] = date("Y-m-d H:i:s");
            $data['image'] = $filename;

            Comment::create($data);
        } else {
            $data = Input::except('_token', 'image', 'redirect', 'commenttype');
            $data['type'] = Input::get('commenttype');
            $data['date'] = date("Y-m-d H:i:s");
            Comment::create($data);
        }
        return Response::json(array('result' => 0, 'input' => Input::all()));
    }

    public function anyLogout() {
        Sentry::logout();
        return Response::json(array('result' => true));
    }

    public function postLogin2() {
        if (Input::has("email")) {
            $user = User::where("email", "=", Input::get("email"))->first();
            if ($user) {
                return Response::json(array('result' => '0', 'user' => $user));
            } else {
                return Response::json(array('result' => '1', 'message' => "No se encuentra el usuario"));
            }
        } else {
            return Response::json(array('result' => '1', 'message' => "No llego info"));
        }
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