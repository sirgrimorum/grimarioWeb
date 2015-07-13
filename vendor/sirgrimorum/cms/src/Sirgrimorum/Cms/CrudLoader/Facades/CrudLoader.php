<?php namespace Sirgrimorum\Cms\CrudLoader\Facades;

use Illuminate\Support\Facades\Facade;

class CrudLoader extends Facade {

    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CrudLoader';
    }

} 