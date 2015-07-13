<?php

class Comment extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'date' => 'required|date',
        'task_id' => 'required|integer|exists:tasks,id',
        'user_id' => 'required|integer|exists:users,id',
        'type' => 'required|integer|exists:commenttypes,id',
        'comment' => 'required',
        'url' => 'active_url',
        'image' => 'image|max:2000|mimes:jpeg,png'
    ];

    // Don't forget to fill this array
    //protected $fillable = ['name','code','proyect_id',];
    protected $guarded = array();

    public function user() {
        return $this->belongsTo('User');
    }

    public function task() {
        return $this->belongsTo('Task');
    }
    
    public function commenttype() {
        return $this->belongsTo('Commenttype', 'type');
    }



}
