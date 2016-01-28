<?php

class Team extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8|unique:teams,name',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
    ];

    // Don't forget to fill this array
    //protected $fillable = [];

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_team');
    }

    public function games() {
        return $this->belongsToMany('Game', 'game_team');
    }

    public function prices() {
        return $this->belongsToMany('Price', 'price_team');
    }

    public function proyects() {
        return $this->belongsToMany('Proyect', 'proyect_team');
    }

    public function users() {
        return $this->belongsToMany('User', 'team_user')->withPivot(array('valueph'));
    }

    public function gametasks($game) {
        if ($game) {
            $strWhere = "";
            $preWhere = "(";
            foreach ($this->users()->get() as $user) {
                $strWhere .= $preWhere . " users.id = '" . $user->id . "' ";
                $preWhere = " or ";
            }
            if ($strWhere != "") {
                $strWhere .= ")";

                $tasks = $game->tasks()->get()->filter(function($task) use ($strWhere) {
                    if (!$task->users()->whereRaw($strWhere)->get()->isEmpty()) {
                        return true;
                    }
                });
                return $tasks;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function teamtasks($strWherePri = "") {
        if ($this) {
            $strWhere = "";
            $preWhere = "(";
            foreach ($this->users()->get() as $user) {
                $strWhere .= $preWhere . " users.id = '" . $user->id . "' ";
                $preWhere = " or ";
            }
            if ($strWhere != "") {
                $strWhere .= ")";
                if ($strWherePri != ""){
                    $preTask = Task::whereRaw($strWherePri)->orderBy("proyect_id", "DESC")->orderBy("planstart", "ASC")->get();
                }else{
                    $preTask = Task::orderBy("proyect_id", "DESC")->orderBy("planstart", "ASC")->get();
                }
                $tasks = $preTask->filter(function($task) use ($strWhere) {
                    if (!$task->users()->whereRaw($strWhere)->get()->isEmpty()) {
                        return true;
                    }
                });
                return $tasks;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function taskpoints($task, $game) {
        $total = 0;
        if ($tasks = $this->gametasks($task->game)) {
            if ($tasks->count() > 0) {
                foreach ($this->users()->get() as $user) {
                    $total += $task->teamPoints($user->id);
                }
                $total = $total / $tasks->count();
            }
        }
        return $total * $game->difficulty;
    }

    public function points($game) {
        $points = 0;
        if ($tasks = $this->gametasks($game)) {
            foreach ($tasks as $task) {
                $points+=$this->taskpoints($task, $game);
            }
        }
        return $points;
    }

}
