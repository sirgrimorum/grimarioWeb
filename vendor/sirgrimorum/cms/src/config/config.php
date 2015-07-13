<?php

return [
    /*
      |--------------------------------------------------------------------------
      | View to Bind Translation Vars To
      |--------------------------------------------------------------------------
      |
      | Set this value to the name of the view (or partial) that
      | you want to prepend the Translation variables to.
      |
     */
    'bind_trans_vars_to_this_view' => 'layouts.includes.footer',
    /*
      |--------------------------------------------------------------------------
      | Group of the variables to bind
      |--------------------------------------------------------------------------
      |
      |
     */
    'trans_group' => 'mensajes',
    /*
      |--------------------------------------------------------------------------
      | Default name of the var for translations in js
      |--------------------------------------------------------------------------
      |
      |
     */
    'default_base_var' => 'translations',
    /*
      |--------------------------------------------------------------------------
      | Default language
      |--------------------------------------------------------------------------
      |
      |
     */
    'default_locale' => 'en',
    'list_locales' => ['en','es'],
    /*
      |--------------------------------------------------------------------------
      | Prefix for administrator
      |--------------------------------------------------------------------------
      |
      |
     */
    'admin_prefix' => 'admin_translations',
    /*
      |--------------------------------------------------------------------------
      |	  The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
      | is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
      |--------------------------------------------------------------------------
      |  @type closure
     */
    'permission' => function() {
return true;
return Auth::check();
},
    /**
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    'login_path' => 'user/login',
    /**
     * The logout path is the path where Administrator will send the user when they click the logout link
     *
     * @type string
     */
    'logout_path' => false,
    /**
     * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
     *
     * @type string
     */
    'login_redirect_key' => 'redirect',
    /**
     * Routes list for the administrator
     */
    'admin_routes' => [
        "Article" => [
            "tabla" => "articles",
            "plural" => "articles",
            "nombre" => "nickname",
            "id" => "id",
            "relaciones" => [
                "author_user_id" => [
                    "modelo" => "User",
                    "id" => "id",
                    "nombre" => "name",
                    "todos" => ""
                ]
            ],
            "campos" => [
                "content" => [
                    "tipo" => "html",
                ],
            ],
            "rules" => [
                
            ]
        ],
    ]
];
