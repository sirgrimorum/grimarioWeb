<?php

class TasksController extends \BaseController {

    /**
     * Display a listing of tasks
     *
     * @return Response
     */
    public function index() {
        $tasks = Task::all();

        return View::make('modelos.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task
     *
     * @return Response
     */
    public function create() {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if (Input::has('pr') && Input::has('py')) {
            $proyectId = Input::get('pr');
            $paymentId = Input::get('py');
            $proyect = Proyect::find($proyectId);
            $payment = Payment::find($paymentId);
            //$juegos = new \Illuminate\Database\Eloquent\Collection;
            //$juegos = \Illuminate\Database\Eloquent\Collection::make([]);
            $juegos = [];
            foreach ($proyect->enterprises()->get() as $enterprise) {
                foreach ($enterprise->games()->where('state', '<>', 'ter')->get() as $game) {
                    $juegos[$game->id] = $game->name;
                }
            }
            $usuarios = [];
            foreach ($proyect->teams()->get() as $team) {
                foreach ($team->users()->get() as $user) {
                    $usuarios[$user->id] = $user->name;
                }
            }
            //return "<pre>" . print_r($usuarios,true) . "</pre>";
            return View::make('modelos.tasks.create', ['proyect' => $proyect, 'payment' => $payment, 'juegos' => $juegos, 'usuarios' => $usuarios]);
        } else {
            return View::make('modelos.tasks.create');
        }
    }

    /**
     * Store a newly created task in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::except('payments', 'users', 'tasktype');
        $data['type'] = Input::get("tasktype");
        $validator = Validator::make($data, Task::$rules);
        //return "<pre>" . print_r(Input::all(),true) . "</pre>";
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data = Input::except('_token', 'payments', 'users', 'tasktype');
        $data['type'] = Input::get("tasktype");
        $task = Task::create($data);
        if (Input::has('payments')) {
            $payment = Payment::find(Input::get('payments'));
            $task->payments()->save($payment);
            if ($payment->state == 'cre') {
                $payment->state = 'pla';
                $payment->save();
            }
        }
        $proyect = $task->proyect;
        if ($proyect->state == 'cre') {
            $proyect->state = 'pla';
            $proyect->save();
        }
        if (Input::has('users')) {
            $usuarios = Input::get('users');
            if (is_array($usuarios)) {
                foreach ($usuarios as $usuario_id) {
                    $usuario = User::find($usuario_id);
                    $task->users()->save($usuario);
                }
            } else {
                $usuario = User::find($usuarios);
                $task->users()->save($usuario);
            }
        }
        return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($task->payments()->first()->id));

        //return Redirect::route(Lang::get("principal.menu.links.tarea") . '.index');
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $task = Task::findOrFail($id);
        $user = Sentry::getUser();
        $work = $task->works()->where('user_id', '=', $user->id)->whereRaw("YEAR(end) = 0 and start < NOW()")->orderBy('start', 'desc')->first();
        if ($work && $task->state == 'pau') {
            $task->state = 'des';
            $task->save();
        }

        return View::make('modelos.tasks.show', ['task' => $task, 'user' => $user, 'work' => $work]);
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $task = Task::find($id);
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("tasks")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $vista = ".edit";
        $pasa = false;
        if (Input::has('equipo')) {
            $parametros = [
                'task' => $task,
                'usuarios' => $task->users()->get(),
                'proyect' => $task->proyect,
                'payment' => $task->payments()->first(),
                'user' => $userSen
            ];
            if (!$userSen->inGroup(Sentry::findGroupByName('Director'))) {
                $messages = new Illuminate\Support\MessageBag;
                $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                return Redirect::route("home")->withErrors($messages);
            }
            return View::make('modelos.tasks.equipo', $parametros);
        } elseif (Input::has('st')) {
            if (Input::get('st') == 'des') {
                $data = $task->getAttributes();
                $comienzo = date("Y-m-d H:i:s");
                $data['state'] = 'des';
                $data['start'] = $comienzo;
                $pasa = true;
                $validator = Validator::make($data, Task::$rules);
                if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator)->withInput();
                }
                $task->update($data);
                $user = Sentry::getUser();
                $work = new Work;
                $work->start = $comienzo;
                $work->task()->associate($task);
                $work->coordinator()->associate($user);
                $work->save();
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
                return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id))->with('message', Lang::get("task.mensajes.comenzado"));
            } else {
                $state = Input::get('st');

                $user = Sentry::getUser();
                $work = $task->works()->where('user_id', '=', $user->id)->whereRaw("YEAR(end) = 0 and start < NOW()")->orderBy('start', 'desc')->first();
                if (!$work) {
                    $work = $task->works()->orderBy('end', 'desc')->first();
                }
                $first = true;
                foreach ($task->works()->get() as $auxwork) {
                    $nuecosts = $auxwork->costs()->get();
                    if ($first) {
                        $costs = $nuecosts;
                    } else {
                        $costs = $costs->merge($nuecosts);
                    }
                    $first = false;
                }
                $parametros = [
                    'task' => $task,
                    'state' => $state,
                    'usuarios' => $task->users()->get(),
                    'maquinas' => $task->proyect->enterprises()->first()->machines()->get(),
                    'recursos' => $task->proyect->enterprises()->first()->resources()->get(),
                    'proyect' => $task->proyect,
                    'payment' => $task->payments()->first(),
                    'work' => $work,
                    'comments' => $task->comments,
                    'costs' => $costs,
                    'user' => $user,
                ];
                if (Input::get('st') == 'ent') {
                    if (!$userSen->hasAccess("proyects")) {
                        $messages = new Illuminate\Support\MessageBag;
                        $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                        return Redirect::route("home")->withErrors($messages);
                    }
                    $vista = ".entregar";
                } elseif (Input::get('st') == 'cer') {
                    if (!$userSen->inGroup(Sentry::findGroupByName('Director'))) {
                        $messages = new Illuminate\Support\MessageBag;
                        $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                        return Redirect::route("home")->withErrors($messages);
                    }
                    $vista = ".evaluar";
                } else {
                    $vista = ".actualizar";
                }
                $pasa = true;
            }
        } else {
            $juegos = [];
            foreach ($task->proyect->enterprises()->get() as $enterprise) {
                foreach ($enterprise->games()->where('state', '<>', 'ter')->get() as $game) {
                    $juegos[$game->id] = $game->name;
                }
            }
            $usuarios = [];
            foreach ($task->proyect->teams()->get() as $team) {
                foreach ($team->users()->get() as $user) {
                    $usuarios[$user->id] = $user->name;
                }
            }
            $parametros = [
                'task' => $task,
                'usuarios' => $usuarios,
                'juegos' => $juegos,
            ];
            $vista = ".edit";
            $pasa = true;
        }
        if ($pasa) {
            return View::make('modelos.tasks' . $vista, $parametros);
        } else {
            return Redirect::back()->with('message', Lang::get("task.mensajes.no_actualizado"));
        }
    }

    /**
     * Update the specified task in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $task = Task::findOrFail($id);
        $userSen = Sentry::getUser();
        $paraEntrega = false;
        $pasa = false;
        if (Input::has('act_equipo')) {
            foreach ($task->users()->get() as $worker) {
                if (Input::has("task_users_r_" . $worker->id) && Input::has("task_users_v_" . $worker->id)) {
                    //$user_task = $task->users()->find($worker->id);
                    //$user_task->pivot->calification = Input::get("work_users_c_" . $worker->id);
                    //$user_task->pivot->save();
                    if (Input::has("task_users_v_" . $worker->id)) {
                        $task->users()->updateExistingPivot($worker->id, [
                            'valueph' => Input::get("task_users_v_" . $worker->id),
                            'responsability' => Input::get("task_users_r_" . $worker->id)
                        ]);
                    } else {
                        $task->users()->updateExistingPivot($worker->id, [
                            'valueph' => Input::get("task_users_v_" . $worker->id),
                            'responsability' => Input::get("task_users_r_" . $worker->id)
                        ]);
                    }
                }
            }
            return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
        } elseif (Input::has('st')) {
            if (Input::get('st') == 'ch') {
                $data = $task->getAttributes();
                $data['state'] = 'des';
                $pasa = true;
            } else {
                
            }
        } elseif ($task->state == 'pau' || ( $task->state == 'des' && !($task->works()->where('user_id', '=', $userSen->id)->whereRaw("YEAR(end) = 0 and start < NOW()")->orderBy('start', 'desc')->first()))) {
            $data['state'] = 'ter';
            $pasa = true;
        } else {
            $data = $task->getAttributes();
            if (Input::has('formaction')) {
                if (Input::get('formaction') == Lang::get('task.labels.detener')) {
                    $data['state'] = 'pau';
                } elseif (Input::get('formaction') == Lang::get('task.labels.finalizar')) {
                    $data['state'] = 'ter';
                } else {
                    $data['state'] = Input::get('state');
                }
            } else {
                $data['state'] = Input::get('state');
            }

            if ($data['state'] == 'ent') {
                $data['end'] = date("Y-m-d H:i:s");
                foreach ($task->users()->get() as $worker) {
                    if (Input::has("work_users_c_" . $worker->id)) {
                        //$user_task = $task->users()->find($worker->id);
                        //$user_task->pivot->calification = Input::get("work_users_c_" . $worker->id);
                        //$user_task->pivot->save();
                        $task->users()->updateExistingPivot($worker->id, ['calification' => Input::get("work_users_c_" . $worker->id)]);
                    }
                }
                if (Input::has("dcuantity")) {
                    $data['dcuantity'] = Input::get("dcuantity");
                }
                Session::put('userTo', $task->proyect->user->id);
                Mail::send(array('emails.html.tasks.entregada', 'emails.text.tasks.entregada'), array('task' => $task, 'user' => Sentry::getUser(), 'userTo' => $task->proyect->user, 'payment' => $task->payments()->first()), function($message) {
                    $user = User::find(Session::get('userTo'));
                    $message->from(Lang::get("email.from_email"), Lang::get("email.from_name"));
                    $message->to($user->email, $user->name)->subject(Lang::get("task.emails.titulos.entregada"));
                });
                $pasa = true;
            } elseif ($data['state'] == 'cer' && Input::has('formaction')) {
                if (Input::has("satisfaction")) {
                    $data['satisfaction'] = Input::get("satisfaction");
                }
                if (Input::has("cuality")) {
                    $data['cuality'] = Input::get("cuality");
                    if ($data['cuality'] == "noc") {
                        $data['state'] = 'pau';
                        $userTo = $task->works()->orderBy('end', 'desc')->first()->coordinator;
                        Session::put('userTo', $userTo->id);
                        Mail::send(array('emails.html.tasks.devuelta', 'emails.text.tasks.devuelta'), array('task' => $task, 'user' => Sentry::getUser(), 'userTo' => $userTo, 'payment' => $task->payments()->first()), function($message) {
                            $user = User::find(Session::get('userTo'));
                            $message->from(Lang::get("email.from_email"), Lang::get("email.from_name"));
                            $message->to($user->email, $user->name)->subject(Lang::get("task.emails.titulos.devuelta"));
                        });
                    }
                } else {
                    $paraEntrega = true;
                }

                $pasa = true;
            } else {
                if (Input::get('formaction') == Lang::get('task.labels.edit')) {
                    $data = Input::except('payments', 'users', 'tasktype', 'formaction');
                    $data['type'] = Input::get("tasktype");
                    $validator = Validator::make($data, Task::$rules);

                    if ($validator->fails()) {
                        return "<pre>" . print_r($validator->messages, true) . "</pre>";
                        return Redirect::back()->withErrors($validator)->withInput();
                    }
                    $data = Input::except('_token', 'payments', 'users', 'tasktype', 'formaction');
                    $data['type'] = Input::get("tasktype");
                    $task->update($data);
                    if (Input::has('users')) {
                        $usuarios = Input::get('users');
                        $task->users()->sync($usuarios);
                    }

                    return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
                } else {
                    $data['dpercentage'] = Input::get('dpercentage');
                    $work = Work::findOrFail(Input::get('work_id'));
                    $datosWork = $work->getAttributes();
                    $datosWork['end'] = Input::get('end');
                    $datosWork['start'] = Input::get('start');
                    $validator = Validator::make($datosWork, Work::$rules);
                    if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator)->withInput();
                    }
                    $work->update($datosWork);
                    $workers = Input::get('work_users');
                    if (is_array($workers)) {
                        foreach ($workers as $worker_id => $valor_sel) {
                            $worker = User::find($worker_id);
                            $work->users()->save($worker, ['hours' => Input::get("work_users_c_" . $worker_id)]);
                        }
                    }
                    $machines = Input::get('work_machines');
                    if (is_array($machines)) {
                        foreach ($machines as $machine_id => $valor_sel) {
                            $machine = Machine::find($machine_id);
                            $work->machines()->save($machine, ['hours' => Input::get("work_machines_c_" . $machine_id)]);
                        }
                    }
                    $resources = Input::get('work_resources');
                    if (is_array($resources)) {
                        foreach ($resources as $resource_id => $valor_sel) {
                            $resource = Resource::find($resource_id);
                            $work->resources()->save($resource, ['cuantity' => Input::get("work_resources_c_" . $resource_id)]);
                        }
                    }
                    $pasa = true;
                }
            }
        }
        if ($pasa) {
            $validator = Validator::make($data, Task::$rules);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $task->update($data);
            if ($paraEntrega) {
                $payment = $task->payments()->first();
                if ($payment->advance() == 100) {
                    $payment->state = 'ent';
                    $payment->save();
                }
                $proyect = $task->proyect;
                if ($proyect->advance() == 100) {
                    $proyect->state = 'ter';
                    $proyect->save();
                }
            }
            return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
        } else {
            return Redirect::back()->with('message', Lang::get("task.mensajes.no_actualizado"));
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Task::destroy($id);

        return Redirect::route('modelos.tasks.index');
    }

}
