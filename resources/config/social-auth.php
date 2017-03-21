<?php

return [

    'models' => [
        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */
        'social' => ZFort\SocialAuth\Models\SocialProvider::class,
    ],

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
        | The table that your save all social network which your application use.
        |
        */
        'social_providers' => 'social_providers'
    ],

    'foreign_keys' => [

        /*
         * The name of the foreign key to the users table.
         */
        'users' => 'user_id',

        /*
         * The name of the foreign key to the socials table
         */
        'socials' => 'social_id'
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication redirection
    |--------------------------------------------------------------------------
    |
    | Redirect path after successful login via social network
    |
    */
    'redirect' => '/home'
];
