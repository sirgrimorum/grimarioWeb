<?php

function getMensajesValidacion() {
    $mensajes = [
        'required' => 'The :attribute field is required.',
    ];
    return $mensajes;
}

/**
 * Get the value of a property using reflection.
 *
 * @param object|string $class
 *     The object or classname to reflect. An object must be provided
 *     if accessing a non-static property.
 * @param string $propertyName The property to reflect.
 * @return mixed The value of the reflected property.
 */
function getReflectedPropertyValue($class, $propertyName) {
    $reflectedClass = new ReflectionClass($class);
    $property = $reflectedClass->getProperty($propertyName);
    $property->setAccessible(true);

    return $property->getValue($class);
}

function toDataObj($myObj) {
    $ref = new ReflectionClass($myObj);
    $data = array();
    foreach (array_values($ref->getMethods()) as $method) {
        if ((0 === strpos($method->name, "get"))
                && $method->isPublic()) {
            $name = substr($method->name, 3);
            $name[0] = strtolower($name[0]);
            $value = $method->invoke($myObj);
            if ("object" === gettype($value)) {
                $value = toDataObj($value);
            }
            $data[$name] = $value;
        }
    }
    return $data;
}

function getGoogleClient($user) {
    $profile = $user->profiles()->where("type","=","Google")->first();
    $client = new Google_Client();
    $client->setApplicationName("Grimario");
    $client->setScopes(Config::get('oauth-4-laravel::consumers.Google.Google.scope'));
    $client->setAuthConfigFile(Config::get('oauth-4-laravel::consumers.Google.config_path'));
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = Config::get('oauth-4-laravel::consumers.Google.credentials_path');
    if ($profile) {
        $auxToken = json_decode($profile->token_json,true);
        $accessToken = json_encode([
            "access_token" => $auxToken["accessToken"],
            "refresh_token" => $auxToken["accessToken"],
            "token_type" => $auxToken["extraParams"]["token_type"],
            "id_token" => $auxToken["extraParams"]["id_token"],
            "created" => $profile->update_c,
            "expires_in" => $auxToken["extraParams"]["expires_in"],
        ]);
    } else {
        return false;
        /*
          // Request authorization from the user.
          $authUrl = $client->createAuthUrl();
          printf("Open the following link in your browser:\n%s\n", $authUrl);
          print 'Enter verification code: ';
          $authCode = trim(fgets(STDIN));

          // Exchange authorization code for an access token.
          $accessToken = $client->authenticate($authCode);

          // Store the credentials to disk.
          /* if(!file_exists(dirname($credentialsPath))) {
          mkdir(dirname($credentialsPath), 0700, true);
          }
          file_put_contents($credentialsPath, $accessToken);
          printf("Credentials saved to %s\n", $credentialsPath); */
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->refreshToken($client->getRefreshToken());
        $profile->access_token = $client->getAccessToken();
        $profile->save();
        //file_put_contents($credentialsPath, $client->getAccessToken());
    }
    return $client;
}
