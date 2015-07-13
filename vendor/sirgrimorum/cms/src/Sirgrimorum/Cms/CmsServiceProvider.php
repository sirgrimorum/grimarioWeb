<?php

namespace Sirgrimorum\Cms;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Sirgrimorum\Cms\Translations\BindTranslationsToJs;
use Sirgrimorum\Cms\TransArticles\GetArticleFromDataBase;
use Sirgrimorum\Cms\CrudLoader\CrudGenerator;
use Sirgrimorum\Cms\ValidatorExtension;
use Config;

class CmsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('sirgrimorum/cms');
        AliasLoader::getInstance()->alias(
                'TransArticle', 'Sirgrimorum\Cms\TransArticles\Facades\TransArticle'
        );
        AliasLoader::getInstance()->alias(
                'Translations', 'Sirgrimorum\Cms\Translations\Facades\Translations'
        );
        AliasLoader::getInstance()->alias(
                'CrudLoader', 'Sirgrimorum\Cms\CrudLoader\Facades\CrudLoader'
        );
        //define a constant that the rest of the package can use to conditionally use pieces of Laravel 4.1.x vs. 4.0.x
        $this->app['administrator.4.1'] = version_compare(\Illuminate\Foundation\Application::VERSION, '4.1') > -1;

        //set up an alias for the base laravel controller to accommodate >=4.1 and <4.1
        if (!class_exists('AdministratorBaseController')) { // Verify alias is not already created
            if ($this->app['administrator.4.1'])
                class_alias('Illuminate\Routing\Controller', 'AdministratorBaseController');
            else
                class_alias('Illuminate\Routing\Controllers\Controller', 'AdministratorBaseController');
        }
        // Registering the validator extension with the validator factory
        $this->app['validator']->resolver(function($translator, $data, $rules, $messages) {
            return new ValidatorExtension(
                    $translator, $data, $rules, $messages
            );
        });
        //include our filters, view composers, and routes
        include __DIR__ . '/../filters.php';
        include __DIR__ . '/../routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('TransArticles', function($app) {
            return new GetArticleFromDataBase($app->getLocale());
        });
        $this->app->bind('CrudLoader', function($app) {
            return new CrudGenerator($app);
        });
        $this->app->bind('Translations', function($app) {
            $view = Config::get('cms::config.bind_trans_vars_to_this_view');
            $group = Config::get('cms::config.trans_group');
            $basevar = Config::get('cms::config.default_base_var');

            return new BindTranslationsToJs($app, $view, $group, $basevar);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('TransArticles', 'Translations','CrudLoader');
    }

}
