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
        return $this->belongsToMany('Group','users_groups');
    }
    
    public function proyects() {
        return $this->hasMany('Proyect');
    }
    
    public function taskhours($task_id){
        $task = Task::findOrFail($task_id);
        $total = 0;
        foreach ($task->works()->get() as $work){
            $total += $work->users()->where("users.id","=",$this->id)->first()->pivot->hours;
        }
        return $total;
    }

}
