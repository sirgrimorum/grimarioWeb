<?php

class Tasktype extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required',
        'enterprise_id' => 'required|integer|exists:enterprises,id',
    ];

    //protected $fillable = [];

    protected $guarded = array();
    
    public function tasks() {
        return $this->hasMany('Task','type');
    }

    public function enterprise() {
        return $this->belongsTo('Enterprise');
    }
    
    public function commenttypes() {
        return $this->belongsToMany('Commenttype', 'commenttype_tasktype');
    }


}