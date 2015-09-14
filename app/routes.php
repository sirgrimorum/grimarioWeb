<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::filter('loged', function() {
    if (!Sentry::check()) {
        return Redirect::to(Lang::get("principal.menu.links.usuario") . '/login');
    }
});
Route::group(array('before' => 'loged'), function() {
    Route::get('/', array('as' => 'home', 'uses' => 'HomeController@showWelcome'));
    Route::resource(Lang::get("principal.menu.links.empresa"), 'EnterprisesController');
    Route::resource(Lang::get("principal.menu.links.equipo"), 'TeamsController');
    Route::resource(Lang::get("principal.menu.links.juego"), 'GamesController');
    Route::resource(Lang::get("principal.menu.links.premio"), 'PricesController');
    Route::resource(Lang::get("principal.menu.links.proyecto"), 'ProyectsController');
    Route::resource(Lang::get("principal.menu.links.tarea"), 'TasksController');
    Route::resource(Lang::get("principal.menu.links.pago"), 'PaymentsController');
    Route::resource(Lang::get("principal.menu.links.trabajo"), 'WorksController');
    Route::resource(Lang::get("principal.menu.links.comentario"), 'CommentsController');
    Route::resource(Lang::get("principal.menu.links.costo"), 'CostsController');
    Route::resource(Lang::get("principal.menu.links.grupo"), 'GroupsController');
    Route::resource(Lang::get("principal.menu.links.indicador"), 'IndicatorsController');
    Route::resource(Lang::get("principal.menu.links.riesgo"), 'RisksController');
    Route::resource(Lang::get("principal.menu.links.restriccion"), 'RestrictionsController');
    Route::resource(Lang::get("principal.menu.links.userdata"), 'UserdatasController');
    //Route::get(, array('as' => 'profile', 'uses' => 'UserController@showProfile'));
});
Route::controller(Lang::get("principal.menu.links.usuario"), 'UsersController');
Route::controller(Lang::get("principal.menu.links.auth"), 'OauthController');
