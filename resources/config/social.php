<?php


return [


    'table_names' => [

       /*
       |--------------------------------------------------------------------------
       | Users Table
       |--------------------------------------------------------------------------
       |
       | The table for saving relation between users and social providers.
       | Also there is a place for saving "user social network id" and "token" if it exist
       |
       */
        'user_has_social_provider' => 'user_has_social_provider',

        /*
        |--------------------------------------------------------------------------
        | Social Providers Table
        |--------------------------------------------------------------------------
        |
        | The table that your save all social network which ypur applicatio use.
        |
        */
        'social_providers' => 'social_providers'

    ]

];
