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
        if ((0 === strpos($method->name, "get")) && $method->isPublic()) {
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
    $profile = $user->profiles()->where("type", "=", "Google")->first();
    $client = new Google_Client();
    $client->setApplicationName("Grimario");
    $client->setScopes(Config::get('oauth-4-laravel::consumers.Google.Google.scope'));
    $client->setAuthConfigFile(Config::get('oauth-4-laravel::consumers.Google.config_path'));
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = Config::get('oauth-4-laravel::consumers.Google.credentials_path');
    if ($profile) {
        $auxToken = json_decode($profile->token_json, true);
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

function adjustColor($color,$factor) {
    if ($color == 0.0) {
        return 0;
    } else {
        return round(255.0 * pow(($color * $factor), 0.80));
    }
}

function setColors($wavelength,$rgb) {
    $red = $rgb["r"];
    $green = $rgb["g"];
    $blue = $rgb["b"];
    if ($wavelength >= 380 && $wavelength <= 439) {
        $red = - ($wavelength - 440.0) / (440.0 - 380.0);
        $green = 0.0;
        $blue = 1.0;
    } elseif ($wavelength >= 440 && $wavelength <= 489) {
        $red = 0.0;
        $green = ($wavelength - 440.0) / (490.0 - 440.0);
        $blue = 1.0;
    } elseif ($wavelength >= 490 && $wavelength <= 509) {
        $red = 0.0;
        $green = 1.0;
        $blue = - ($wavelength - 510.0) / (510.0 - 490.0);
    } elseif ($wavelength >= 510 && $wavelength <= 579) {
        $red = ($wavelength - 510.0) / (580.0 - 510.0);
        $green = 1.0;
        $blue = 0.0;
    } elseif ($wavelength >= 580 && $wavelength <= 644) {
        $red = 1.0;
        $green = - ($wavelength - 645.0) / (645.0 - 580.0);
        $blue = 0.0;
    } elseif ($wavelength >= 645 && $wavelength <= 780) {
        $red = 1.0;
        $green = 0.0;
        $blue = 0.0;
    } else {
        $red = 0.0;
        $green = 0.0;
        $blue = 0.0;
    }
    $rgb["r"]=$red;
    $rgb["g"]=$green;
    $rgb["b"]=$blue;
    return $rgb;
}

function setFactor($wavelength,$rgb) {
    $factor = $rgb["f"];
    if ($wavelength >= 380 && $wavelength <= 419) {
        $factor = 0.3 + 0.7 * ($wavelength - 380.0) / (420.0 - 380.0);
    } elseif ($wavelength >= 420 && $wavelength <= 700) {
        $factor = 1.0;
    } elseif ($wavelength >= 701 && $wavelength <= 780) {
        $factor = 0.3 + 0.7 * (780.0 - $wavelength) / (780.0 - 700.0);
    } else {
        $factor = 0.0;
    }
    $rgb["f"] = $factor;
    return $rgb;
}

function arrColors($numSteps) {
    $colors = [];
    $rgb = [
        "r"=>0,
        "g"=>0,
        "b"=>0,
        "f"=>0
    ];
    
    for ($i = 0; $i < $numSteps; $i ++) {
        $lambda = round(380 + 400 * ($i / ($numSteps - 1)));
        $rgb=setColors($lambda,$rgb);
        $rgb=setFactor($lambda,$rgb);
        $rgb["r"] = adjustColor($rgb["r"], $rgb["f"]);
        $rgb["g"] = adjustColor($rgb["g"], $rgb["f"]);
        $rgb["b"] = adjustColor($rgb["b"], $rgb["f"]);
        $redHex = dechex($rgb["r"]);
        $redHex = (strlen($redHex) < 2) ? "0" . $redHex : $redHex;
        $greenHex = dechex($rgb["g"]);
        $greenHex = (strlen($greenHex) < 2) ? "0" . $greenHex : $greenHex;
        $blueHex = dechex($rgb["b"]);
        $blueHex = (strlen($blueHex) < 2) ? "0" . $blueHex : $blueHex;
        $bgcolor = "#" . $redHex . $greenHex . $blueHex;
        array_push($colors, $bgcolor);
    }
    return $colors;
}
