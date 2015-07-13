<?php

class Payment extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:6',
        'conditions' => 'required',
        'plan' => 'required',
        'plandate' => 'required|date',
        'paymentdate' => 'date',
        'proyect_id' => 'required|integer|exists:proyects,id',
    ];
    
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function proyect() {
        return $this->belongsTo('Proyect');
    }
    
    public function tasks() {
        return $this->belongsToMany('Task', 'payment_task');
    }
    
    public function indicators() {
        return $this->hasMany('Indicator');
    }
    
    public function risks() {
        return $this->hasMany('Risk');
    }
    
    public function totalcost(){
        $total = 0;
        foreach ($this->tasks()->get() as $task){
            $total += $task->totalcost();
        }
        return $total;
    }
    
    public function profit(){
        $total = $this->plan - $this->totalcost();
        return $total;
    }
    
    public function advance(){
        $advance = 0;
        foreach ($this->tasks()->get() as $task){
            $advance += $task->contribution();
        }
        return $advance;
    }
    
    public function contribution(){
        return ($this->percentage * $this->advance())/100;
    }

}
