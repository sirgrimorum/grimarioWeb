<?php

class RestrictionsController extends BaseController {

    /**
     * Display a listing of restrictions
     *
     * @return Response
     */
    public function index() {
        $restrictions = Restriction::all();

        return View::make('modelos.restrictions.index', compact('restrictions'));
    }

    /**
     * Show the form for creating a new restriction
     *
     * @return Response
     */
    public function create() {
        if (Input::has('pr')) {
            $proyectId = Input::get('pr');
            $proyect = Proyect::find($proyectId);
            $user = Sentry::getUser();

            return View::make('modelos.restrictions.create', ['proyect' => $proyect, 'user' => $user]);
        } else {
            return View::make('modelos.restrictions.create');
        }
    }

    /**
     * Store a newly created restriction in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Restriction::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token','tk','st');

        $restriction = Restriction::create($data);
        
        if (Input::has('tk') && Input::has('st')){
            return Redirect::to(URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array(Input::get('tk'))) . "?st=" . Input::get('st'));
        }else{
            return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($restriction->proyect->id));
        }
    }

    /**
     * Display the specified restriction.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $restriction = Restriction::findOrFail($id);

        return View::make('modelos.restrictions.show', compact('restriction'));
    }

    /**
     * Show the form for editing the specified restriction.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $restriction = Restriction::find($id);

        return View::make('modelos.restrictions.edit', compact('restriction'));
    }

    /**
     * Update the specified restriction in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $restriction = Restriction::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Restriction::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        
        $data = Input::except('_token','tk','st');

        $restriction->update($data);
        
        if (Input::has('tk') && Input::has('st')){
            return Redirect::to(URL::route(Lang::get("principal.menu.links.tarea"). '.edit', array(Input::get('tk'))) . "?st=" . Input::get('st'));
        }else{
            return Redirect::route(Lang::get("principal.menu.links.proyecto"). '.show', array($restriction->proyect->id));
        }
    }

    /**
     * Remove the specified restriction from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $restriction = Restriction::findOrFail($id);
        $proyect_id = $restriction->proyect->id;
        Restriction::destroy($id);

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($proyect_id));
    }

}
