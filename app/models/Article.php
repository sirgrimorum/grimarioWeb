<?php
class Article extends Eloquent {

    protected $table = 'articles';
    
    public function author()
    {
        return $this->belongsTo('User','author_user_id');
    }
    
}