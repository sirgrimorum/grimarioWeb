<?php

class Team extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8|unique:teams,name',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
    ];

    // Don't forget to fill this array
    //protected $fillable = [];

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_team');
    }
    public function games() {
        return $this->belongsToMany('Game', 'game_team');
    }
    public function prices() {
        return $this->belongsToMany('Price', 'price_team');
    }
    public function proyects() {
        return $this->belongsToMany('Proyect', 'proyect_team');
    }
    public function users() {
        return $this->belongsToMany('User', 'team_user');
    }

}
