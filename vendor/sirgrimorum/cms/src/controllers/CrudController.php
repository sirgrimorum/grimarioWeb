<?php

namespace Sirgrimorum\Cms;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use AdministratorBaseController as Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\File\File as SFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Sirgrimorum\Cms;

class CrudController extends Controller {

    protected $tabla = 'articles';
    protected $modelo = 'Article';
    protected $plural = 'articles';
    protected $nombre = 'nickname';
    protected $id = 'id';
    protected $relaciones = array();

    public function index() {
        $modeloM = ucfirst($this->modelo);
        $registros = $modeloM::all();
        $auxarr_modelo = explode("\\", $this->modelo);
        return View::make('cms::crud.index', array('tabla' => $this->tabla, 'modelo' => array_pop($auxarr_modelo), 'plural' => $this->plural, 'registros' => $registros, 'relaciones' => $this->relaciones, 'identificador' => $this->id, 'base_url' => Config::get('cms::admin_prefix')));
    }

    public function create() {
        foreach ($this->relaciones as $clave => $relacion) {
            if (!is_array($this->relaciones[$clave]['todos'])) {
                $lista = array("-" => "-");
                $modeloM = ucfirst($relacion["modelo"]);
                foreach ($modeloM::all() as $elemento) {
                    $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['nombre']};
                }
                $this->relaciones[$clave]['todos'] = $lista;
            }
        }
        $auxarr_modelo = explode("\\", $this->modelo);
        return View::make('cms::crud.create', array('tabla' => $this->tabla, 'modelo' => array_pop($auxarr_modelo), 'plural' => $this->plural, 'relaciones' => $this->relaciones, 'campos' => $this->campos, 'base_url' => Config::get('cms::admin_prefix')));
    }

    public function store() {
        $rules = array(
                /* 'name' => 'required',
                  'email' => 'required|email',
                  'nerd_level' => 'required|numeric' */
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to(Config::get('cms::admin_prefix') . '/' . $this->plural . '/create')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {
            $selects = array('column_name as field', 'column_type as type', 'is_nullable as null', 'column_key as key', 'column_default as default', 'extra as extra');
            $table_describes = DB::table('information_schema.columns')
                    ->where('table_name', '=', $this->tabla)
                    ->get($selects);
            $modeloM = ucfirst($this->modelo);
            $nuevo = new $modeloM;
            foreach ($table_describes as $key => $columna) {
                if ($columna->field != "created_at" && $columna->field != "updated_at" && $columna->key != "PRI") {
                    if (Input::has($columna->field)) {
                        $nuevo->{$columna->field} = Input::get($columna->field);
                    }
                }
            }
            $nuevo->save();

            // redirect
            Session::flash('message', $modeloM . ' creado exitosamente');
            return Redirect::to(Config::get('cms::admin_prefix') . '/' . $this->plural);
        }
    }

    public function show($id) {
        $modeloM = ucfirst($this->modelo);
        $registro = $modeloM::find($id);

        $auxarr_modelo = explode("\\", $this->modelo);
        return View::make('cms::crud.show', array('tabla' => $this->tabla, 'modelo' => array_pop($auxarr_modelo), 'plural' => $this->plural, 'registro' => $registro, 'relaciones' => $this->relaciones, 'nombre' => $this->nombre, 'base_url' => Config::get('cms::admin_prefix')));
    }

    public function edit($id) {
        foreach ($this->relaciones as $clave => $relacion) {
            $lista = array("-" => "-");
            $modeloM = ucfirst($relacion["modelo"]);
            foreach ($modeloM::all() as $elemento) {
                $lista[$elemento->{$relacion['id']}] = $elemento->{$relacion['nombre']};
            }
            $this->relaciones[$clave]['todos'] = $lista;
        }
        $modeloM = ucfirst($this->modelo);
        $registro = $modeloM::find($id);

        $auxarr_modelo = explode("\\", $this->modelo);
        return View::make('cms::crud.edit', array('tabla' => $this->tabla, 'modelo' => array_pop($auxarr_modelo), 'plural' => $this->plural, 'registro' => $registro, 'relaciones' => $this->relaciones, 'nombre' => $this->nombre, 'id' => $this->id, 'campos' => $this->campos, 'base_url' => Config::get('cms::admin_prefix')));
    }

    public function update($id) {
        $rules = array(
                /* 'name' => 'required',
                  'email' => 'required|email',
                  'nerd_level' => 'required|numeric' */
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to(Config::get('cms::admin_prefix') . '/' . $this->plural . '/edit')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {
            $selects = array('column_name as field', 'column_type as type', 'is_nullable as null', 'column_key as key', 'column_default as default', 'extra as extra');
            $table_describes = DB::table('information_schema.columns')
                    ->where('table_name', '=', $this->tabla)
                    ->get($selects);
            $modeloM = ucfirst($this->modelo);
            $viejo = $modeloM::find($id);
            foreach ($table_describes as $key => $columna) {
                if ($columna->field != "created_at" && $columna->field != "updated_at" && $columna->key != "PRI") {
                    if (Input::has($columna->field)) {
                        $viejo->{$columna->field} = Input::get($columna->field);
                    }
                }
            }
            $viejo->save();

            // redirect
            Session::flash('message', $modeloM . ' actualizado exitosamente');
            return Redirect::to(Config::get('cms::admin_prefix') . '/' . $this->plural);
        }
    }

    public function destroy($id) {
        $modeloM = ucfirst($this->modelo);
        $muerto = $modeloM::find($id);
        $muerto->delete();

        Session::flash('message', $modeloM . ' eliminado exitosamente');
        return Redirect::to('admin' . $this->plural);
    }

}
