<?php

class TaskUser extends \Eloquent {
    
    protected $table = 'task_user';

    // Add your validation rules here
    public static $rules = [
        'calification' => 'double',
        'start' => 'date',
        'end' => 'date',
    ];

    public function task() {
        return $this->belongsTo('Task');
    }

    public function user() {
        return $this->belongsTo('User');
    }

}

