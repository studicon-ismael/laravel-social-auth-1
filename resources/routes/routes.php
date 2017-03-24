<?php

$this->app['router']->group(
    [
        'namespace' => 'ZFort\SocialAuth\Controllers',
        'middleware' => ['web']
    ],
    function ($router) {
        $router->get('social/{social}', 'SocialAuthController@getAccount')->name('social.auth');
        $router->get('social/callback/{social}', 'SocialAuthController@callback')->name('social.callback');
        $router->get('social/detach/{social}', 'SocialAuthController@detachAccount')->name('social.detach');
    }
);
