<?php

return array(
    /*
      |--------------------------------------------------------------------------
      | oAuth Config
      |--------------------------------------------------------------------------
     */

    /**
     * Storage
     */
    'storage' => 'Session',
    /**
     * Consumers
     */
    'consumers' => array(
        'Facebook' => array(
            'client_id' => 'Your Facebook client ID',
            'client_secret' => 'Your Facebook Client Secret',
            'scope' => array('email', 'read_friendlists', 'user_online_presence'),
        ),
        'Google' => array(
            'client_id' => '642895069480-q1g801gp14qjqjsu7o31uptiisstl6ao.apps.googleusercontent.com',
            'client_secret' => 'Y7tBD_pvyM00eUd5hVk7gWk4',
            'scope' => array('userinfo_email', 'userinfo_profile', 'calendar'),
            'config_path' => asset("images/client_secret_642895069480-q1g801gp14qjqjsu7o31uptiisstl6ao.apps.googleusercontent.com.json"),
            'credentials_path' => asset("images/credentials_calendar.json")
        ),
        'Twitter' => array(
            'client_id' => 'Your Twitter client ID',
            'client_secret' => 'Your Twitter Client Secret',
        // No scope - oauth1 doesn't need scope
        ),
        'Linkedin' => array(
            'client_id' => '78mh2ndwy8dsxg',
            'client_secret' => 'OLJbDhDG9zK83Wjv',
            'scope' => array('r_basicprofile','r_emailaddress'),
        ),
        'Yahoo' => array(
            'client_id' => 'Your Yahoo API KEY',
            'client_secret' => 'Your Yahoo API Secret',
        ),
    )
);
