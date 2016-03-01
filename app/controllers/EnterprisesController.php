<?php

class EnterprisesController extends \BaseController {

    /**
     * Display a listing of enterprises
     *
     * @return Response
     */
    public function index() {
        $enterprises = Enterprise::all();

        return View::make('enterprises.index', compact('enterprises'));
    }

    /**
     * Show the form for creating a new enterprise
     *
     * @return Response
     */
    public function create() {
        return View::make('enterprises.create');
    }

    /**
     * Store a newly created enterprise in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Enterprise::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Enterprise::create($data);

        return Redirect::route('enterprises.index');
    }

    /**
     * Display the specified enterprise.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $enterprise = Enterprise::findOrFail($id);
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
            $proyects = $usuario->proyects()->where("state","!=","ter")->get();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
            $proyects = $enterprise->proyects()->where("state","!=","ter")->get();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
            $proyects = $enterprise->proyects()->where("state","!=","ter")->get();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'value', 'saves', 'profit', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.destroy', array("{ID}")) . "'>" . Lang::get("proyect.labels.eliminar") . "</a>",
            ];
        }
        return View::make('modelos.enterprises.show', ['enterprise'=> $enterprise, 'proyects' => $proyects, 'botonCrear' => $botonCrear, 'configCampos' => $configCampos, 'configBotones' => $configBotones, 'userSen' => $userSen]);
    }

    /**
     * Show the form for editing the specified enterprise.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $enterprise = Enterprise::find($id);

        return View::make('enterprises.edit', compact('enterprise'));
    }

    /**
     * Update the specified enterprise in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $enterprise = Enterprise::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Enterprise::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $enterprise->update($data);

        return Redirect::route('enterprises.index');
    }

    /**
     * Remove the specified enterprise from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Enterprise::destroy($id);

        return Redirect::route('enterprises.index');
    }

}
