<?php namespace Sirgrimorum\Cms\TransArticles\Facades;

use Illuminate\Support\Facades\Facade;

class TransArticle extends Facade {

    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TransArticles';
    }

} 