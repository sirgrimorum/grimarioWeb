<?php

class Commenttype extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required',
        'tasktype_id' => 'required|integer|exists:tasktypes,id',
    ];

    //protected $fillable = [];

    protected $guarded = array();
    
    public function comments() {
        return $this->hasMany('Comment','type');
    }
    
    public function tasktypes() {
        return $this->belongsToMany('Tasktype', 'commenttype_tasktype');
    }


}