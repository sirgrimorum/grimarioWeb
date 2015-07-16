<?php

use Khill\Lavacharts\Lavacharts;

class GamesController extends \BaseController {

    /**
     * Display a listing of games
     *
     * @return Response
     */
    public function index() {
        $games = Game::all();

        return View::make('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new game
     *
     * @return Response
     */
    public function create() {
        return View::make('games.create');
    }

    /**
     * Store a newly created game in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Game::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Game::create($data);

        return Redirect::route('games.index');
    }

    /**
     * Display the specified game.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("tasks")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $game = Game::findOrFail($id);


        $dtTeamsPoints = Lava::DataTable();
        $dtTeamsPoints->addStringColumn(Lang::get("team.labels.equipos"))
                ->addNumberColumn(Lang::get("team.labels.points"));
        //$colors = arrColors($game->teams()->get()->count() + 1);
        //array_shift($colors);
        $first = true;
        foreach ($game->teams()->get() as $team) {
            $nueusers = $team->users()->get();
            $dtTeamsPoints->addRow(array($team->name, $team->points($game)));
            if ($first) {
                $users = $nueusers;
            } else {
                $users = $users->merge($nueusers);
            }
            $first = false;
        }
        $barTeamsPoints = Lava::BarChart('points_teams')->setOptions(array(
            'datatable' => $dtTeamsPoints,
            'theme' => 'maximized',
            'legend' => Lava::Legend([
                'position' => 'none',
            ]),
            'colors' => ["orange"],
        ));

        $dtUsersPoints = Lava::DataTable();
        $dtUsersPoints->addStringColumn(Lang::get("user.labels.usuarios"))
                ->addNumberColumn(Lang::get("user.labels.points"));
        foreach ($users as $puser) {
            $dtUsersPoints->addRow(array($puser->name, $puser->gamepoints($game)));
        }
        $barUsersPoints = Lava::BarChart('points_users')->setOptions(array(
            'datatable' => $dtUsersPoints,
            'theme' => 'maximized',
            'legend' => Lava::Legend([
                'position' => 'none',
            ]),
            'colors' => ["orange"]
        ));

        return View::make('modelos.games.show', [
                    "game" => $game,
                    "userSen" => $userSen,
        ]);
    }

    /**
     * Show the form for editing the specified game.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $game = Game::find($id);

        return View::make('games.edit', compact('game'));
    }

    /**
     * Update the specified game in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $game = Game::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Game::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $game->update($data);

        return Redirect::route('games.index');
    }

    /**
     * Remove the specified game from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Game::destroy($id);

        return Redirect::route('games.index');
    }

}
