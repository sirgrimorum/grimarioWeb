<?php

class ProyectsController extends \BaseController {

    /**
     * Display a listing of proyects
     *
     * @return Response
     */
    public function index() {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if (!$userSen->hasAccess("proyects")) {
            return Redirect::route("home");
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
            $proyects = $usuario->proyects()->get();
            $botonCrear = false;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Director'))) {
            $proyects = Proyect::all();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
            $proyects = Proyect::all();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'value', 'saves', 'profit', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.destroy', array("{ID}")) . "'>" . Lang::get("proyect.labels.eliminar") . "</a>",
            ];
        }

        return View::make('modelos.proyects.index', ['proyects'=>$proyects,'botonCrear'=>$botonCrear,'configCampos'=>$configCampos,'configBotones'=>$configBotones, 'userSen'=>$userSen]);
    }

    /**
     * Show the form for creating a new proyect
     *
     * @return Response
     */
    public function create() {
        if (Input::has('en')) {
            $enterpriseId = Input::get('en');
            $enterpise = Enterprise::find($enterpriseId);
            return View::make('modelos.proyects.create', ['enterprise' => $enterpise]);
        } else {
            return View::make('modelos.proyects.create');
        }
    }

    /**
     * Store a newly created proyect in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::except('pop', 'teams', 'enterprises'), Proyect::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $file = Input::file('pop');

        if ($file) {

            $destinationPath = public_path() . '/images/proyects/';
            $filename = $file->getClientOriginalName();
            $filename = str_random(20) . "." . $file->getClientOriginalExtension();


            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {

                // resizing an uploaded file
                Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . "thumb/" . $filename, 100);

                //return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
            $data = Input::except('_token', 'pop', 'teams', 'enterprises');
            $data['pop'] = $filename;
        } else {
            $data = Input::except('_token', 'pop', 'teams', 'enterprises');
        }

        $proyect = Proyect::create($data);

        if (Input::has('enterprises')) {
            if (is_array(Input::get('enterprises'))) {
                foreach (Input::get('enterprises') as $enterprise_id) {
                    $enterprise = Enterprise::find($enterprise_id);
                    $proyect->enterprises()->attach($enterprise);
                }
            } else {
                $enterprise = Enterprise::find(Input::get('enterprises'));
                $proyect->enterprises()->attach($enterprise);
            }
        }

        if (Input::has('teams')) {
            if (is_array(Input::get('teams'))) {
                foreach (Input::get('teams') as $team_id) {
                    $team = Team::find($team_id);
                    $proyect->teams()->attach($team);
                }
            } else {
                $team = Team::find(Input::get('teams'));
                $proyect->teams()->attach($team);
            }
        }

        return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id));
    }

    /**
     * Display the specified proyect.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $proyect = Proyect::findOrFail($id);

        return View::make('modelos.proyects.show', compact('proyect'));
    }

    /**
     * Show the form for editing the specified proyect.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $proyect = Proyect::find($id);
        if (Input::has('en')) {
            $enterpriseId = Input::get('en');
            $enterpise = Enterprise::find($enterpriseId);
            return View::make('modelos.proyects.edit', ['proyect' => $proyect, 'enterprise' => $enterpise]);
        } else {
            return View::make('modelos.proyects.edit', ['proyect' => $proyect]);
        }
    }

    /**
     * Update the specified proyect in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $proyect = Proyect::findOrFail($id);

        $validator = Validator::make($data = Input::except('pop_nue'), array_except(Proyect::$rules, 'name', 'code'));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $file = Input::file('pop_nue');

        if ($file) {

            $destinationPath = public_path() . '/images/proyects/';
            $filename = $file->getClientOriginalName();
            $filename = str_random(20) . "." . $file->getClientOriginalExtension();


            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {

                // resizing an uploaded file
                Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . "thumb/" . $filename, 100);

                //return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
            $data = Input::except('_token', 'pop', 'teams', 'enterprises');
            $data['pop'] = $filename;
        } else {
            $data = Input::except('_token', 'teams', 'enterprises');
        }


        $proyect->update($data);

        return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.index');
    }

    /**
     * Remove the specified proyect from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Proyect::destroy($id);

        return Redirect::route('modelos.proyects.index');
    }

}
