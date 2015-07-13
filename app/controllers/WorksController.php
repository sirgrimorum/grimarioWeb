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
        return View::make('modelos.works.create');
    }

    /**
     * Store a newly created work in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Work::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Work::create($data);

        return Redirect::route('works.index');
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
            return Redirect::to(URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array($task->id)) . "?st=pau");
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
