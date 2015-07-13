<?php

namespace Sirgrimorum\Cms\CrudLoader;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Exception;

class CrudGenerator {

    /**
     * 
     * @param string $app Ipara nada
     */
    function __construct($app) {
        
    }

    /**
     * Generate create view for a model
     * @param array $config Configuration array
     * @return HTML Create form
     */
    function create($config) {
        if (isset($config['render'])) {
            foreach ($config['relaciones'] as $clave => $relacion) {
                if (!is_array($relacion['todos'])) {
                    $lista = array("-" => "-");
                    $modeloM = ucfirst($relacion["modelo"]);
                    foreach ($modeloM::all() as $elemento) {
                        if (is_array($relacion['nombre'])) {
                            $strNombre = "";
                            $preNombre = "";
                            foreach ($relacion['nombre'] as $nombreRelacion) {
                                $strNombre .= $preNombre . $elemento->{$nombreRelacion};
                                $preNombre = ", ";
                            }
                            $lista[$elemento->{$relacion['id']}] = $strNombre;
                        } else {
                            $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['nombre']};
                        }
                    }
                    $config['relaciones'][$clave]['todos'] = $lista;
                }
            }
        } else {
            foreach ($config['campos'] as $clave => $relacion) {
                if ($relacion['tipo'] == "relationship" || $relacion['tipo'] == "relationships") {
                    if (!is_array($config['campos'][$clave]['todos'])) {
                        if ($relacion['tipo'] == "relationship") {
                            $lista = array("-" => "-");
                        }
                        if ($config['campos'][$clave]['todos'] == ""){
                            $modeloM = ucfirst($relacion["modelo"]);
                            $modelosM = $modeloM::all();
                        }else{
                            $modelosM = $config['campos'][$clave]['todos'];
                        }
                        foreach ($modelosM as $elemento) {
                            if (is_array($relacion['campo'])) {
                                $strNombre = "";
                                $preNombre = "";
                                foreach ($relacion['campo'] as $nombreRelacion) {
                                    $strNombre .= $preNombre . $elemento->{$nombreRelacion};
                                    $preNombre = ", ";
                                }
                                $lista[$elemento->{$relacion['id']}] = $strNombre;
                            } else {
                                $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['campo']};
                            }
                        }
                        $config['campos'][$clave]['todos'] = $lista;
                    }
                }
            }
        }
        $view = View::make('cms::crudgen.create', array('config' => $config));
        return $view->render();
    }

    /**
     * Generate view to show a model
     * @param array $config Configuration array
     * @param integer $id Key of the object
     * @param Model $registro Optional The Object
     * @return HTML the Object
     */
    public function show($config, $id, $registro = null) {
        if ($registro == null) {
            $modeloM = ucfirst($config['modelo']);
            $registro = $modeloM::find($id);
        }

        $view = View::make('cms::crudgen.show', array('config' => $config, 'registro' => $registro));
        return $view->render();
    }

    /**
     * Generate de edit view of a model
     * @param array $config Configuration array
     * @param integer $id Key of the object
     * @param Model $registro Optional The object
     * @return HTML Edit form
     */
    public function edit($config, $id, $registro = null) {
        if (isset($config['render'])) {
            foreach ($config['relaciones'] as $clave => $relacion) {
                $lista = array("-" => "-");
                $modeloM = ucfirst($relacion["modelo"]);
                foreach ($modeloM::all() as $elemento) {
                    $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['nombre']};
                }
                $config['relaciones'][$clave]['todos'] = $lista;
            }
        } else {
            foreach ($config['campos'] as $clave => $relacion) {
                if ($relacion['tipo'] == "relationship" || $relacion['tipo'] == "relationships") {
                    if (!is_array($config['campos'][$clave]['todos'])) {
                        if ($relacion['tipo'] == "relationship") {
                            $lista = array("-" => "-");
                        }
                        if ($config['campos'][$clave]['todos'] == ""){
                            $modeloM = ucfirst($relacion["modelo"]);
                            $modelosM = $modeloM::all();
                        }else{
                            $modelosM = $config['campos'][$clave]['todos'];
                        }
                        foreach ($modelosM as $elemento) {
                            if (is_array($relacion['campo'])) {
                                $strNombre = "";
                                $preNombre = "";
                                foreach ($relacion['campo'] as $nombreRelacion) {
                                    $strNombre .= $preNombre . $elemento->{$nombreRelacion};
                                    $preNombre = ", ";
                                }
                                $lista[$elemento->{$relacion['id']}] = $strNombre;
                            } else {
                                $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['campo']};
                            }
                        }
                        $config['campos'][$clave]['todos'] = $lista;
                    }
                }
            }
        }
        if ($registro == null) {
            $modeloM = ucfirst($config['modelo']);
            $registro = $modeloM::find($id);
        }
        $view = View::make('cms::crudgen.edit', array('config' => $config, 'registro' => $registro));
        return $view->render();
    }

    /**
     * Generate a list of objects of a model
     * @param array $config Configuration array
     * @param Model() $registros Optional Array of objects to show
     * @return HTML Table with the objects
     */
    public function lists($config, $registros = null) {
        if ($registros == null) {
            $modeloM = ucfirst($config['modelo']);
            $registros = $modeloM::all();
        }
        $view = View::make('cms::crudgen.list', array('config' => $config, 'registros' => $registros));
        return $view->render();
    }

}
