<?php namespace Sirgrimorum\Cms;

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
use Illuminate\Support\Facades\Validator as LValidator;


class AdminTransController extends Controller{
    
    public function home() {
        return View::make("cms::layouts.home");
    }
    public function relocate($lang= null) {
        if ($lang != null){
            App::setLocale($lang);
        }
        return View::make("cms::layouts.home");
    }
}
