<?php

class WorksController extends BaseController {

    /**
     * Display a listing of works
     *
     * @return Response
     */
    public function index() {
        $works = Work::all();

        return View::make('modelos.works.index', compact('works'));
    }

    /**
     * Show the form for creating a new work
     *
     * @return Response
     */
    public function create() {
        $user = Sentry::getUser();
        if (Input::has('tk')) {
            $taskId = Input::get('tk');
            $task = Task::find($taskId);
            $users = $task->users()->where("users.id", "<>", $user->id)->get();
            return View::make('modelos.works.create', ['task' => $task, 'user' => $user, 'users' => $users]);
        } else {
            return View::make('modelos.works.create', ['user' => $user]);
        }
    }

    /**
     * Store a newly created work in storage.
     *
     * @return Response
     */
    public function store() {
        //return "<pre>" . print_r(Input::all(), true) . "</pre>";
        $task = Task::findOrFail(Input::get("task_id"));
        $validator = Validator::make($data = Input::all(), Work::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token', 'calendario', 'sala', 'users');

        $work = Work::create($data);

        if (Input::get("calendario")) {
            $user = User::findOrFail(Sentry::getUser()->id);
            $profile = $user->profiles()->where("type", "=", "Google")->first();
            $googleClient = getGoogleClient($user);
            $service = new \Google_Service_Calendar($googleClient);
            $calendarId = 'primary';
            $end = new DateTime(Input::get("start"));
            $end->modify('+60 minutes');
            $eventData = array(
                'summary' => Input::get("name"),
                'location' => 'Grimorum',
                'description' => Lang::get("work.mensajes.descripcion_calendario") . $task->name . " - " . $task->code,
                'start' => array(
                    'dateTime' => date("c", strtotime(Input::get("start"))),
                    'timeZone' => 'America/Bogota',
                ),
                'end' => array(
                    'dateTime' => $end->format('c'),
                    'timeZone' => 'America/Bogota',
                ),
                'reminders' => array(
                    'useDefault' => TRUE,
                /* 'overrides' => array(
                  //array('method' => 'email', 'minutes' => 24 * 60),
                  array('method' => 'popup', 'minutes' => 15),
                  ), */
                ),
            );
            if (Input::get("sala")) {
                $eventData['attendees'] = array(
                    array('email' => 'grimorum@grimorum.com'),
                );
            }
            if (Input::has("users")) {
                if (is_array(Input::get("users"))) {
                    foreach (Input::get("users") as $user_id) {
                        $auxUser = User::find($user_id);
                        if ($user_id != $user->id) {
                            array_push($eventData['attendees'], ['email' => $auxUser->email]);
                        }
                        $work->users()->save($auxUser);
                    }
                } else {
                    $auxUser = User::find(Input::get("users"));
                    if (Input::get("users") != $user->id) {
                        array_push($eventData['attendees'], ['email' => $auxUser->email]);
                    }
                    $work->users()->save($auxUser);
                }
            }
            $event = new Google_Service_Calendar_Event($eventData);

            $event = $service->events->insert($calendarId, $event);
            /* $optParams = array(
              'maxResults' => 3,
              'orderBy' => 'startTime',
              'singleEvents' => TRUE,
              'timeMin' => date('c'),
              );
              $results = $service->events->listEvents($calendarId, $optParams); */
            //return "<pre>" . print_r($event, true) . "</pre>";
        } else {
            if (is_array(Input::get("users"))) {
                foreach (Input::get("users") as $user_id) {
                    $auxUser = User::find($user_id);
                    $work->users()->save($auxUser);
                }
            } else {
                $auxUser = User::find(Input::get("users"));
                $work->users()->save($auxUser);
            }
        }

        return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($work->task->id));
    }

    /**
     * Display the specified work.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $work = Work::findOrFail($id);
        $task = $work->task;
        if ($work->end == 0) {
            return Redirect::to(URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array($task->id)) . "?st=pau");
        } else {
            $parametros = [
                'task' => $task,
                'usuarios' => $work->users()->get(),
                'maquinas' => $work->machines()->get(),
                'recursos' => $work->resources()->get(),
                'proyect' => $task->proyect,
                'payment' => $task->payments()->first(),
                'work' => $work,
                'comments' => $task->comments,
                'costs' => $work->costs()->get(),
            ];

            return View::make('modelos.works.show', $parametros);
        }
    }

    /**
     * Show the form for editing the specified work.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $work = Work::find($id);

        return View::make('modelos.works.edit', compact('work'));
    }

    /**
     * Update the specified work in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $work = Work::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Work::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $work->update($data);

        return Redirect::route('works.index');
    }

    /**
     * Remove the specified work from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Work::destroy($id);

        return Redirect::route('works.index');
    }

}
