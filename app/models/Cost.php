<?php

class Cost extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
            'description' => 'required',
            'code' => 'required',
            'value' => 'required|numeric',
            'date' => 'required|date',
            'work_id' => 'required|integer|exists:works,id',
            'user_id' => 'required|integer|exists:users,id',
    ];
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function work() {
        return $this->belongsTo('Work', 'work_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
