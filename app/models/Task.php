<?php

class Task extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:6',
        'result' => 'required',
        'state' => 'required',
        'difficulty' => 'required',
        'percentage' => 'required',
        'plan' => 'required|date',
        'expenses' => 'required|min:0',
        'proyect_id' => 'required|integer|exists:proyects,id',
        'game_id' => 'required|integer|exists:games,id',
        'type' => 'required|integer|exists:tasktypes,id',
    ];
    
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function proyect() {
        return $this->belongsTo('Proyect');
    }

    public function game() {
        return $this->belongsTo('Game');
    }
    
    public function tasktype() {
        return $this->belongsTo('Tasktype', 'type');
    }

    public function users() {
        return $this->belongsToMany('User','task_user')->withPivot(array('valueph', 'calification', 'responsability'));
    }

    public function comments() {
        return $this->hasMany('Comment');
    }
    
    public function works() {
        return $this->hasMany('Work');
    }
    
    public function payments() {
        return $this->belongsToMany('Payment', 'payment_task');
    }
    
    public function othercosts() {
        $total = 0;
        foreach ($this->works()->get() as $work){
            $total += $work->othercost();
        }
        return $total;
    }
    
    public function totalcost() {
        $total = 0;
        foreach ($this->works()->get() as $work){
            $total += $work->totalcost();
        }
        return $total;
    }
    
    public function profit() {
        return ($this->expenses - $this->othercosts());
    }
    
    public function workedhours($userId){
        $total = 0;
        foreach ($this->works()->get() as $work){
            $total += $work->workedhours($userId);
        }
        return $total;
    }

    public function contribution(){
        return ($this->percentage * $this->dpercentage)/100;
    }

}
