<?php namespace Sirgrimorum\Cms\Translations\Facades;

use Illuminate\Support\Facades\Facade;

class Translations extends Facade {

    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Translations';
    }

} 