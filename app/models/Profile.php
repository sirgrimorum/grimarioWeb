<?php

class Profile extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
            // 'title' => 'required'
    ];

    public function user() {
        return $this->belongsTo('User');
    }

}
