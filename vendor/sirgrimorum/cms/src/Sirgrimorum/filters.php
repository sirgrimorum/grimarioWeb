<?php

//validate_admin filter
Route::filter('validate_admin_translate', function ()
{
	//get the admin check closure that should be supplied in the config
	$permission = Config::get('cms::permission');
	$response = $permission();

	//if this is a simple false value, send the user to the login redirect
	if (!$response)
	{
		$loginUrl = URL::to(Config::get('cms::login_path', 'user/login'));
		$redirectKey = Config::get('cms::login_redirect_key', 'redirect');
		$redirectUri = Request::url();

		return Redirect::guest($loginUrl)->with($redirectKey, $redirectUri);
	}
	//otherwise if this is a response, return that
	else if (is_a($response, 'Illuminate\Http\JsonResponse') || is_a($response, 'Illuminate\Http\Response'))
	{
		return $response;
	}
	//if it's a redirect, send it back with the redirect uri
	else if (is_a($response, 'Illuminate\\Http\\RedirectResponse'))
	{
		return $response->with($redirectKey, $redirectUri);
	}
});

