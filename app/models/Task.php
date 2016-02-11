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
    protected $visible = array('id', 'name', 'result', 'state', 'percentage', 'type', 'proyect_id', 'totalhours');

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
        return $this->belongsToMany('User', 'task_user')->withPivot(array('valueph', 'hours', 'calification', 'responsability'));
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
        foreach ($this->works()->get() as $work) {
            $total += $work->othercost();
        }
        return $total;
    }

    public function totalcost() {
        $total = 0;
        foreach ($this->works()->get() as $work) {
            $total += $work->totalcost();
        }
        return $total;
    }

    public function profit() {
        return ($this->expenses - $this->othercosts());
    }

    public function workedhours($userId) {
        $total = 0;
        foreach ($this->works()->get() as $work) {
            $total += $work->workedhours($userId);
        }
        return $total;
    }

    public function totalhours() {
        $total = 0;
        foreach ($this->users()->get() as $user) {
            $total += $this->workedhours($user->id);
        }
        return $total;
    }

    public function contribution() {
        return ($this->percentage * $this->dpercentage) / 100;
    }

    public function taskpoints() {
        switch ($this->cuality) {
            case "noc":
                $cuality = 0;
                break;
            case "cum":
                $cuality = 0.9;
                break;
            case "sup":
                $cuality = 1.1;
                break;
            default:
                $cuality = 0;
        }
        switch ($this->satisfaction) {
            case "noc":
                $satisfaction = 0.5;
                break;
            case "cum":
                $satisfaction = 0.8;
                break;
            case "sup":
                $satisfaction = 1;
                break;
            case "ide":
                $satisfaction = 1.2;
                break;
            default:
                $satisfaction = 0;
        }
        if ($this->pcuantity < $this->dcuantity) {
            $cuantity = 1.1;
        } elseif ($this->pcuantity = $this->dcuantity) {
            $cuantity = 1;
        } else {
            $cuantity = 0.8;
        }
        $plan = new DateTime($this->plan);
        $end = new DateTime($this->end);
        $diff = $plan->diff($end);
        if ($diff->d > 0) {
            $time = 1.2;
        } elseif ($diff->d == 0) {
            $time = 1;
        } else {
            $time = 0.8;
        }
        return (1 + ((2 - $this->difficulty) / 10)) * $cuality * $satisfaction * $cuantity * $time;
    }

    public function teamPoints($userId) {
        if (($totalhours = $this->totalhours()) > 0) {
            $points = ($this->workedhours($userId) / $totalhours) * $this->taskpoints();
        } else {
            $points = 0;
        }
        return $points;
    }

    public function elapsedtime() {
        if ($this->end > 0) {
            $fin = new DateTime($this->end);
        } else {
            $fin = new DateTime();
        }
        $inicio = new DateTime($this->start);
        $dif = $inicio->diff($fin);
        return ($dif->days * 24) + $dif->h;
    }

    public function timeleft() {
        if ($this->state != 'des' && $this->state != 'pau' && $this->state != 'pla' && $this->end > 0) {
            $fin = new DateTime($this->end);
        } else {
            $fin = new DateTime();
        }
        $inicio = new DateTime($this->plan);
        $dif = $fin->diff($inicio, false);
        if ($dif->format("%r") == "-") {
            return $dif->days * -1;
        } else {
            return $dif->days;
        }
    }

}
