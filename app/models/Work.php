<?php

class Work extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'start' => 'required|date',
        'task_id' => 'required|integer|exists:tasks,id',
    ];
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function task() {
        return $this->belongsTo('Task');
    }

    public function coordinator() {
        return $this->belongsTo('User', 'user_id');
    }

    public function users() {
        return $this->belongsToMany('User', 'user_work')->withPivot(array('hours'));
    }

    public function machines() {
        return $this->belongsToMany('Machine', 'machine_work')->withPivot(array('hours'));
    }

    public function resources() {
        return $this->belongsToMany('Resource', 'resource_work')->withPivot(array('cuantity'));
    }

    public function costs() {
        return $this->hasMany('Cost');
    }

    public function userscost() {
        $total = 0;
        foreach ($this->users()->get() as $user) {
            $userTask = $this->task->users()->where("users.id", "=", $user->id)->first();
            if ($userTask) {
                $total += $user->pivot->hours * $userTask->pivot->valueph;
            }
        }
        return $total;
    }

    public function machinescost() {
        $total = 0;
        foreach ($this->machines()->get() as $machine) {
            $total += $machine->pivot->hours * $machine->valueph;
        }
        return $total;
    }

    public function resourcescost() {
        $total = 0;
        foreach ($this->resources()->get() as $resource) {
            $total += $resource->pivot->cuantity * $resource->value;
        }
        return $total;
    }

    public function othercost() {
        $total = 0;
        foreach ($this->costs()->get() as $cost) {
            $total += $cost->value;
        }
        return $total;
    }

    public function totalcost() {
        return $this->resourcescost() + $this->userscost() + $this->machinescost() + $this->othercost();
    }

    public function workedhours($userId) {
        if ($user = $this->users()->where("user_id", "=", $userId)->first()) {
            return $user->pivot->hours;
        } else {
            return 0;
        }
    }
    
    public function totalworkedhours() {
        $total = 0;
        foreach ($this->users()->get() as $user) {
            $total += $user->pivot->hours;
        }
        return $total;
        
    }
    
    public function elapsedtime(){
        if ($this->end > 0){
            $fin = new DateTime($this->end);
        }else{
            $fin = new DateTime();
        }
        $inicio = new DateTime($this->start);
        $dif = $inicio->diff($fin);
        return $dif->h;
    }

}
