<?php

$this->app['router']->group(
    [
        'namespace' => 'ZFort\SocialAuth\Controllers',
        'middleware' => ['web'],
        'as' => 'social.'
    ],
    function ($router) {
        $router->get('social/{social}', 'SocialAuthController@getAccount')->name('auth');
        $router->get('social/callback/{social}', 'SocialAuthController@callback')->name('callback');
        $router->get('social/detach/{social}', 'SocialAuthController@detachAccount')->name('detach');
    }
);
