<?php

class Group extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|alpha_num|min:4|unique:groups,name',
    ];

    public function users() {
        return $this->belongsToMany('User','users_groups');
    }

}
