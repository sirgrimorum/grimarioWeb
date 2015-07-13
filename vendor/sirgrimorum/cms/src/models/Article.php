<?php

namespace Sirgrimorum\Cms;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

    
    public function scopeFindArticle($query,$name) {
        $segments = explode(".", $name);
        $scope = array_shift($segments);
        $nickname = implode(".", $segments);
        return $query->where("activated","=","1")->where("scope", "=", $scope)->where("nickname", "=", $nickname);
    }

}
