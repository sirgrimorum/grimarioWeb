<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
            $userSen = Sentry::getUser();
            $user = User::findOrFail($userSen->id);
            $noProyects = false;
            if ($userSen->inGroup(Sentry::findGroupByName('Cliente'))) {
                $proyects = $user->ownerof()->get();
                if ($proyects->count()==1){
                    return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyects->first()->id));
                }
                $esCliente = true;
            }else{
                $works = $user->coordworks()->where("end","=","0000-00-00 00:00:00")->get();
                $proyects = $user->userPros(" proyects.state = 'act' ");
                $tasks = $user->tasks()->where("state","=","des")->orWhere("state","=","pau")->get();
                $taskspen = $user->tasks()->where("state","=","pla")->where("planstart","<", date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))))->get();
                if (!$userSen->hasAccess("proyects")) {
                    $noProyects = true;
                }
                if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
                    $proyects = $user->userPros(" proyects.state = 'act' ");
                    foreach ($user->teams()->get() as $team){
                        $tasksT = $team->teamtasks(" tasks.state = 'des' or tasks.state = 'pau' ");
                        $taskspenT = $team->teamtasks(" tasks.state = 'pla' and tasks.planstart < '" . date("Y-m-d 00:00:00",mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))) . "' ");
                        if ($tasksT){
                            $tasks = $tasks->merge($tasksT);
                        }
                        if ($taskspenT){
                            $taskspen = $taskspen->merge($taskspenT);
                        }
                    }
                }
                if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
                    //$proyects = Proyect::whereRaw(" proyects.state = 'act' ")->get();
                    $proyectsa = $user->proyects()->whereRaw(" proyects.state = 'act' ")->get();
                    $proyectsb = $user->userPros(" proyects.state = 'act' ");
                    $proyects = $proyectsa->merge($proyectsb);
                }
                if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                    $proyects = Proyect::whereRaw(" proyects.state = 'act' ")->get();
                }
                
                $esCliente = false;
            }
            
            return View::make('hello', ["user"=>$user, "tasks"=> $tasks, "taskspen"=> $taskspen, "proyects"=>$proyects, "works" => $works, "esCliente" => $esCliente, "noProyects" => $noProyects]);
	}

}
