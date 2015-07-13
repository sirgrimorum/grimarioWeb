<?php

class Game extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8|unique:games,name',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
        'start' => 'required|date',
        'end' => 'required|date',
    ];
    // Don't forget to fill this array
    protected $fillable = [];
    
    public function tasks() {
        return $this->hasMany('Task');
    }

    public function prices() {
        return $this->belongsToMany('Price', 'game_price');
    }
    
    public function teams() {
        return $this->belongsToMany('Team', 'game_team');
    }

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_game');
    }

}
