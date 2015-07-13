<?php

class Resource extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:6',
        'description' => 'required',
        'value' => 'required',
        'measure' => 'required',
        'enterprise_id' => 'required|integer|exists:enterprises,id',
    ];
    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function works() {
        return $this->belongsToMany('Work', 'resource_work')->withPivot(array('cuantity'));
    }
    
    public function enterprise() {
        return $this->belongsTo('Enterprise');
    }

}
