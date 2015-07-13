<?php

class Price extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|min:8|unique:prices,name',
        'badge' => 'required',
        'type' => 'required|integer',
    ];

    // Don't forget to fill this array
    //protected $fillable = [];

    public function enterprises() {
        return $this->belongsToMany('Enterprise', 'enterprise_price');
    }

    public function games() {
        return $this->belongsToMany('Game', 'game_price');
    }

    public function teams() {
        return $this->belongsToMany('Team', 'price_team');
    }

}
