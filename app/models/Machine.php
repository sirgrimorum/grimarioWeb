<?php

class Machine extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:6',
        'description' => 'required',
        'valueph' => 'required',
        'enterprise_id' => 'required|integer|exists:enterprises,id',
    ];
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function works() {
        return $this->belongsToMany('Work', 'machine_work')->withPivot(array('hours'));
    }
    
    public function enterprise() {
        return $this->belongsTo('Enterprise');
    }

}
