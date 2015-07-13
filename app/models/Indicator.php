<?php

class Indicator extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8',
        'type' => 'required',
        'state' => 'required',
        'priority' => 'required',
        'payment_id' => 'required|integer|exists:payments,id',
        'user_id' => 'required|integer|exists:users,id',
    ];
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();
    
    public function payment() {
        return $this->belongsTo('Payment');
    }
    
    public function user() {
        return $this->belongsTo('User');
    }

}
