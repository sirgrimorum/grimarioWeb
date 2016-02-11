<?php

class UserdatasController extends BaseController {

    /**
     * Display a listing of userdatas
     *
     * @return Response
     */
    public function index() {
        $userdatas = Userdata::all();

        return View::make('modelos.userdatas.index', compact('userdatas'));
    }

    /**
     * Show the form for creating a new userdata
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

        if (Input::has('pr')) {
            $proyectId = Input::get('pr');
            $proyect = Proyect::find($proyectId);
            $user = Sentry::getUser();
            return View::make('modelos.userdatas.create', ['proyect' => $proyect, 'user' => $user]);
        } else {
            return View::make('modelos.userdatas.create');
        }
    }

    /**
     * Store a newly created userdata in storage.
     *
     * @return Response
     */
    public function store() {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $data = Input::only('_token', 'first_name', 'last_name', 'email');
        $data['name'] = Input::get("first_name") . " " . Input::get("last_name");
        $password = str_random(8);
        $data['password'] = $password;
        $validator = Validator::make($data, User::$rules);
        //return "<pre>" . print_r(Input::all(),true) . "</pre>";
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $nuserSen = Sentry::createUser(array(
                    'email' => $data['email'],
                    'password' => $password,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'name' => $data['name'],
                    'activated' => true
        ));

        $user = User::find($nuserSen->id);
        $adminGroup = Sentry::findGroupByName('Cliente');
        $nuserSen->addGroup($adminGroup);

        $data = Input::except('_token', 'first_name', 'last_name', 'email', 'proyect_id');
        $data['user_id'] = $user->id;
        $userdata = Userdatum::create($data);

        if (Input::has("proyect_id")) {
            $user->ownerof()->attach(Input::get("proyect_id"));
        }

        Session::put('userTo', $userSen->id);
        Mail::send(array('emails.html.userdatas.creado', 'emails.text.userdatas.creado'), array('clave' => $password, 'userTo' => $userSen, 'user' => $user), function($message) {
            $user = User::find(Session::get('userTo'));
            $message->from(Lang::get("email.from_email"), Lang::get("email.from_name"));
            $message->to($user->email, $user->name)->subject(Lang::get("userdata.emails.titulos.nuevo_cliente"));
        });
        $proyect = Proyect::find(Input::get("proyect_id"));
        Session::put('userTo', $user->id);
        Mail::send(array('emails.html.userdatas.creadocli', 'emails.html.userdatas.creadocli'), array('clave' => $password, 'user' => $user, 'proyect' => $proyect), function($message) {
            $user = User::find(Session::get('userTo'));
            $message->from(Lang::get("email.from_email"), Lang::get("email.from_name"));
            $message->to($user->email, $user->name)->subject(Lang::get("userdata.emails.titulos.nuevo_proyecto"));
        });

        if (Input::has("proyect_id")) {
            return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array(Input::get("proyect_id")));
        } else {
            return Redirect::route('modelos.userdatas.index');
        }
    }

    /**
     * Display the specified userdata.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $userdata = Userdata::findOrFail($id);

        return View::make('modelos.userdatas.show', compact('userdata'));
    }

    /**
     * Show the form for editing the specified userdata.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userdata = Userdata::find($id);

        return View::make('modelos.userdatas.edit', compact('userdata'));
    }

    /**
     * Update the specified userdata in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $userdata = Userdata::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Userdata::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $userdata->update($data);

        return Redirect::route('userdatas.index');
    }

    /**
     * Remove the specified userdata from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Userdata::destroy($id);

        return Redirect::route('userdatas.index');
    }

}
