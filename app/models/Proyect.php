<?php

class Proyect extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8|unique:proyects,name',
        'code' => 'min:6|unique:proyects,code',
        'state' => 'required',
        'type' => 'required',
        'priority' => 'required',
        'user_id' => 'required|integer|exists:users,id',
    ];

    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function teams() {
        return $this->belongsToMany('Team', 'proyect_team');
    }
    
    public function user() {
        return $this->belongsTo('User');
    }

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_proyect');
    }

    public function tasks() {
        return $this->hasMany('Task', 'proyect_id');
    }

    public function payments() {
        return $this->hasMany('Payment', 'proyect_id');
    }
    
    public function restrictions() {
        return $this->hasMany('Restriction', 'proyect_id');
    }
    
    public function value(){
        $total = 0;
        foreach ($this->payments()->get() as $payment){
            $total += $payment->value;
        }
        return $total;
    }
    
    public function totalcost(){
        $total = 0;
        foreach ($this->payments()->get() as $payment){
            $total += $payment->totalcost();
        }
        return $total;
    }
    
    public function totalplan(){
        $total = 0;
        foreach ($this->payments()->get() as $payment){
            $total += $payment->plan;
        }
        return $total;
    }
    
    public function saves(){
        $total = $this->totalplan() - $this->totalcost();
        return $total;
    }
    
    public function profit(){
        $total = $this->value() - $this->totalplan() + $this->totalcost() ;
        return $total;
    }
    
    public function advance(){
        $advance = 0;
        foreach ($this->payments()->get() as $payment){
            $advance += $payment->contribution();
        }
        return $advance;
    }

}
