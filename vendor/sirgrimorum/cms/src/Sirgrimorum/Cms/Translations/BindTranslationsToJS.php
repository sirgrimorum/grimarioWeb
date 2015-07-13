<?php

namespace Sirgrimorum\Cms\Translations;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Exception;
use Sirgrimorum\Cms\Article;

class BindTranslationsToJs{

    /**
     * @var Dispatcher
     */
    private $event;

    /**
     * @var string
     */
    private $viewToBind;

    /**
     * @var string
     */
    private $group;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $basevar;

    /**
     * @param Dispatcher $event
     * @param $viewToBindVariables
     */
    function __construct($app, $viewToBind, $group, $basevar) {
        $this->event = $app['events'];
        $this->viewToBind = $viewToBind;
        $this->app = $app;
        $this->basevar = $basevar;
        if ($group == "") {
            $this->group = "";
        } else {
            $this->group = $group;
        }
    }

    /**
     * Bind the given JavaScript to the
     * view using Laravel event listeners
     *
     * @param $langfile The language file to load
     */
    public function put($langfile) {
        $lang = $this->app->getLocale();
        $file = new Filesystem();
        $transP = $file->getRequire(base_path().'/app/lang/' . $lang . '/'. $langfile . '.php');
        $trans = $transP[$this->group];
        //$translator = new Translator();
        //$trans = $translator->get($langfile . $this->group);
        if (is_array($trans)) {
            $jsarray = json_encode($trans);
        } else {
            $jsarray = $trans;
        }
        $this->event->listen("composing: {$this->viewToBind}", function() use ($jsarray,$langfile) {
            echo "<script>window.{$this->basevar} = window.{$this->basevar} || {};{$this->basevar}.{$langfile} = {$jsarray};</script>";
        });
    }

    /**
     * Bind the given JavaScript to the
     * view using Laravel event listeners
     *
     * @param $scope The scope to load
     */
    public function putarticle($scope) {
        $lang = $this->app->getLocale();
        $listo = false;
        try {
            $articles = Article::where("scope", "=", $scope)->where("lang", "=", $lang)->get();
            if (count($articles)) {
                $listo = true;
            } else {
                $articles = Article::where("scope", "=", $scope)->get();
                if (count($articles)) {
                    $listo = true;
                    //return $article->content . "<span class='label label-warning'>" . $article->lang . "</span>";
                } else {
                    $jsarray = $langfile;
                }
            }
        } catch (Exception $ex) {
            return $scope . " - Error:" . print_r($ex, true);
        }
        if ($listo){
            if (count($articles)>1){
                $trans =  [];
                foreach($articles as $article){
                    $trans[$article->nickname] = $article->content;
                }
                $jsarray = json_encode($trans);
            }else{
                $jsarray = $article->content;
            }
        }
        $this->event->listen("composing: {$this->viewToBind}", function() use ($jsarray,$scope) {
            echo "<script>window.{$this->basevar} = window.{$this->basevar} || {};{$this->basevar}.{$scope} = {$jsarray};</script>";
        });
    }
}
