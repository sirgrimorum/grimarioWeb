<?php

class CostsController extends BaseController {

    /**
     * Display a listing of costs
     *
     * @return Response
     */
    public function index() {
        $costs = Cost::all();

        return View::make('modelos.costs.index', compact('costs'));
    }

    /**
     * Show the form for creating a new cost
     *
     * @return Response
     */
    public function create() {
        if (Input::has('wk')) {
            $workId = Input::get('wk');
            $work = Work::find($workId);
            if (Input::has('tk')) {
                $taskId = Input::get('tk');
                $task = Task::find($taskId);
            } else {
                $task = $work->task;
            }
            $user = Sentry::getUser();
            return View::make('modelos.costs.create', ['work' => $work, 'task' => $task, 'user' => $user]);
        } elseif (Input::has('tk')) {
            $taskId = Input::get('tk');
            $task = Task::find($taskId);
            $user = Sentry::getUser();
            return View::make('modelos.costs.create', ['task' => $task, 'user' => $user]);
        } else {
            return View::make('modelos.costs.create');
        }
    }

    /**
     * Store a newly created cost in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Cost::$rules);

        if ($validator->fails()) {
            //return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token', 'redirect');

        Cost::create($data);
        if (Input::has('redirect')) {
            return Redirect::to(Input::get('redirect'));
        } else {
            return Redirect::route(Lang::get("principal.menu.links.costo") . '.index');
        }
    }

    /**
     * Display the specified cost.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $cost = Cost::findOrFail($id);

        return View::make('modelos.costs.show', compact('cost'));
    }

    /**
     * Show the form for editing the specified cost.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $cost = Cost::find($id);

        return View::make('modelos.costs.edit', compact('cost'));
    }

    /**
     * Update the specified cost in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $cost = Cost::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Cost::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $cost->update($data);

        return Redirect::route('costs.index');
    }

    /**
     * Remove the specified cost from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Cost::destroy($id);

        return Redirect::route('costs.index');
    }

}
