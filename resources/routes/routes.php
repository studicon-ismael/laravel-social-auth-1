<?php

Route::group(
    [
        'namespace' => 'ZFort\SocialAuth\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::get('social/{social}', 'SocialAuthController@getAccount')->name('social.auth');
        Route::get('social/callback/{social}', 'SocialAuthController@callback')->name('social.callback');
        Route::get('social/detach/{social}', 'SocialAuthController@detachAccount')->name('social.detach');
    }
);
