<?php

class GroupsController extends BaseController {

    /**
     * Display a listing of tasks
     *
     * @return Response
     */
    public function index() {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("groups")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        //$groups = Sentry::findAllGroups();
        $groups = Group::all();
        return View::make('modelos.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new task
     *
     * @return Response
     */
    public function create() {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("groups")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        return View::make('modelos.groups.create');
    }

    /**
     * Store a newly created task in storage.
     *
     * @return Response
     */
    public function store() {
        $messagesV = array_merge(Lang::get("group.mensajes.validation"), Lang::get("principal.mensajes.validation"));
        $validator = Validator::make($data = Input::all(), Group::$rules, $messagesV);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        try {
            $messages = new Illuminate\Support\MessageBag;
            // Create the group
            $group = Sentry::createGroup(array(
                        'name' => Input::get('name'),
                        'permissions' => array(
                            "permissions" => Input::get('permissions'),
                            "users" => Input::get('users'),
                            "admin" => Input::get('admin'),
                            "groups" => Input::get('groups'),
                            "articles" => Input::get('articles'),
                            "gamers" => Input::get('gamers'),
                            "comments" => Input::get('comments'),
                            "enterprises" => Input::get('enterprises'),
                            "games" => Input::get('games'),
                            "prices" => Input::get('prices'),
                            "proyects" => Input::get('proyects'),
                            "tasks" => Input::get('tasks'),
                            "teams" => Input::get('teams'),
                        ),
            ));
            return Redirect::action('GroupsController@index')->with('message', Lang::get("group.mensajes.grupo_creado"));
        } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
            $messages->add('name.required', Lang::get("group.mensajes.validation.name.required"));
            return Redirect::back()->withErrors($messages)->withInput();
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            $messages->add('name.exist', Lang::get("group.mensajes.validation.name.exists"));
            return Redirect::back()->withErrors($messages)->withInput();
        }
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("groups")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        try {
            $group = Sentry::findGroupById($id);
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('not_found', Lang::get("group.mensajes.not_found"));
            return Redirect::back()->withErrors($messages)->withInput();
        }
        return View::make('modelos.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("groups")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        try {
            $group = Sentry::findGroupById($id);
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('not_found', Lang::get("group.mensajes.not_found"));
            return Redirect::back()->withErrors('Group was not found.')->withInput();
        }
        return View::make('modelos.groups.edit', compact('group'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $messagesV = array_merge(Lang::get("group.mensajes.validation"), Lang::get("principal.mensajes.validation"));
        $validator = Validator::make($data = Input::all(), Group::$rules, $messagesV);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $messages = new Illuminate\Support\MessageBag;
            // Find the group using the group id
            $group = Sentry::findGroupById($id);

            // Update the group details
            $group->name = Input::get('name');
            $group->permissions = array(
                "permissions" => Input::get('permissions'),
                "users" => Input::get('users'),
                "admin" => Input::get('admin'),
                "groups" => Input::get('groups'),
                "articles" => Input::get('articles'),
                "gamers" => Input::get('gamers'),
                "comments" => Input::get('comments'),
                "enterprises" => Input::get('enterprises'),
                "games" => Input::get('games'),
                "prices" => Input::get('prices'),
                "proyects" => Input::get('proyects'),
                "tasks" => Input::get('tasks'),
                "teams" => Input::get('teams'),
            );

            // Update the group
            if ($group->save()) {
                return Redirect::back()->with('message', Lang::get("group.mensajes.guardado"));
            } else {
                $messages->add('no_actualizado', Lang::get("group.mensajes.no_actualizado"));
                return Redirect::back()->withErrors($messages)->withInput();
            }
        } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
            $messages->add('name.required', Lang::get("group.mensajes.validation.name.required"));
            return Redirect::back()->withErrors($messages)->withInput();
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            $messages->add('name.exists', Lang::get("group.mensajes.validation.name.exists"));
            return Redirect::back()->withErrors($messages)->withInput();
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            $messages->add('not_found', Lang::get("group.mensajes.not_found"));
            return Redirect::back()->withErrors($messages)->withInput();
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        try {
            // Find the group using the group id
            $group = Sentry::findGroupById($id);

            // Delete the group
            $group->delete();
            return Redirect::route('modelos.groups.index')->with('message', Lang::get("group.mensajes.eliminado"));
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            $messages->add('not_found', Lang::get("group.mensajes.not_found"));
            return Redirect::back()->withErrors($messages)->withInput();
        }
    }

}
