<?php

class Userdatum extends \Eloquent {

    protected $table = 'userdatas';
    // Add your validation rules here
    public static $rules = [
        'charge' => 'required',
        'user_id' => 'required|exists:users,id',
        'cel' => 'required',
    ];

    //protected $fillable = [];

    protected $guarded = array();
    
    public function user() {
        return $this->belongsTo('User');
    }

}