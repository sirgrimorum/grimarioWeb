<?php

Route::group(array('prefix' => Config::get('cms::admin_prefix'), 'before' => 'validate_admin_translate'), function() {
    //Admin Dashboard
    Route::get('/', array(
        'as' => 'admin_translations_home',
        'uses' => 'Sirgrimorum\Cms\AdminTransController@home',
    ));
    Route::get('/relocate/{lang?}', array(
        'as' => 'admin_translations_relocate',
        'uses' => 'Sirgrimorum\Cms\AdminTransController@relocate',
    ));
    $routes = Config::get('cms::admin_routes');
    foreach ($routes as $model => $opciones) {
        $classname = "{$model}adminController";
        $modelo = "Sirgrimorum\Cms\\{$model}";
        $code = "namespace Sirgrimorum\Cms;
                use Sirgrimorum\Cms;
                class {$classname} extends CrudController { 
                    protected \$tabla = '{$opciones['tabla']}'; 
                    protected \$plural = '{$opciones['plural']}'; 
                    protected \$nombre = '{$opciones['nombre']}'; 
                    protected \$id = '{$opciones['id']}'; 
                    protected \$modelo = '{$modelo}';
                    protected \$relaciones = [";
        foreach ($opciones['relaciones'] as $campo => $parametros) {
            $code.= "'{$campo}'=>[";
            foreach ($parametros as $key => $value) {
                $code.= "'{$key}'=>'{$value}',";
            }
            $code .= "],";
        }
        $code .= "];
                    protected \$campos = [";
        foreach ($opciones['campos'] as $campo => $parametros) {
            $code.= "'{$campo}'=>[";
            foreach ($parametros as $key => $value) {
                $code.= "'{$key}'=>'{$value}',";
            }
            $code .= "],";
        }
        $code .= "];
                }";
        eval($code);

        Route::resource($opciones['plural'], "Sirgrimorum\Cms\\" . $classname);
    }
});

