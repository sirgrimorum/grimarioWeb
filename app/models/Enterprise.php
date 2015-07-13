<?php

class Enterprise extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'nickname' => 'required|alpha_num|min:6|unique:enterprises,nickname',
        'fullname' => 'required',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
        'scale' => 'required|integer',
        'url' => 'active_url',
        'linkedin' => 'active_url',
    ];

    // Don't forget to fill this array
    //protected $fillable = [];

    public function teams() {
        return $this->belongsToMany('Team', 'enterprise_team');
    }
    public function games() {
        return $this->belongsToMany('Game', 'enterprise_game');
    }
    public function prices() {
        return $this->belongsToMany('Price', 'enterprise_price');
    }
    public function proyects() {
        return $this->belongsToMany('Proyect', 'enterprise_proyect');
    }
    public function users() {
        return $this->belongsToMany('User', 'enterprise_user');
    }
    
    public function machines() {
        return $this->hasMany('Machine');
    }
    public function resources() {
        return $this->hasMany('Resource');
    }

}
