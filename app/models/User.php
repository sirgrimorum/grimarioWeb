<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait;

    // Add your validation rules here
    public static $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'first_name' => 'required',
        'last_name' => 'required',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    public function articles() {
        return $this->hasMany('Article', 'author_user_id');
    }

    public function comments() {
        return $this->hasMany('Comment');
    }

    public function profiles() {
        return $this->hasMany('Profile');
    }
    
    public function userdatum() {
        return $this->hasMany('Userdatum');
    }
    
    public function ownerof() {
        return $this->belongsToMany('Proyect', 'proyect_user');
    }

    public function tasks() {
        return $this->belongsToMany('Task', 'task_user')->withPivot(array('valueph', 'calification', 'responsability'));
    }

    public function works() {
        return $this->belongsToMany('Work', 'user_work')->withPivot(array('hours'));
    }

    public function costs() {
        return $this->hasMany('Cost');
    }

    public function coordworks() {
        return $this->hasMany('Work');
    }

    public function teams() {
        return $this->belongsToMany('Team', 'team_user');
    }

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_user');
    }

    public function groups() {
        return $this->belongsToMany('Group', 'users_groups');
    }

    public function proyects() {
        return $this->hasMany('Proyect');
    }

    public function taskhours($task) {
        $total = 0;
        foreach ($task->works()->get() as $work) {
            $userwork = $work->users()->where("users.id", "=", $this->id)->get();
            if (!$userwork->isEmpty()) {
                $total += $userwork->first()->pivot->hours;
            }
        }
        return $total;
    }

    public function taskpoints($task, $game) {
        $total = 0;
        $user_id = $this->id;
        $team = $task->proyect->teams()->get()->filter(function($team) use ($user_id) {
                    if (!$team->users()->where("users.id", "=", $user_id)->get()->isEmpty()) {
                        return true;
                    }
                })->first();
        $tasks = $this->tasks()->where("tasks.id", "=", $task->id)->first();
        if ($tasks) {
            switch ($tasks->pivot->calification) {
                case 0:
                    $cuality = 0.6;
                    break;
                case 1:
                    $cuality = 1;
                    break;
                case 2:
                    $cuality = 1.2;
                    break;
                default:
                    $cuality = 0;
            }
        } else {
            $cuality = 0;
        }
        if ($team){
            $total = $team->taskpoints($task, $game) * $cuality;
        }else{
            $total = 0;
        }
        return $total;
    }

    public function gamepoints($game) {
        $total = 0;
        $totalh = 0;
        foreach ($game->tasks()->get() as $task) {
            $total += $this->taskpoints($task, $game);
            $totalh += $this->taskhours($task);
        }
        if ($totalh > 160) {
            $multp = 1.1;
        } else {
            $multp = 1;
        }
        return $total * $multp;
    }

}
