<?php

class Restriction extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8',
        'type' => 'required',
        'state' => 'required',
        'proyect_id' => 'required|integer|exists:proyects,id',
        'user_id' => 'required|integer|exists:users,id',
    ];
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function proyect() {
        return $this->belongsTo('Proyect');
    }

    public function user() {
        return $this->belongsTo('User');
    }

}
