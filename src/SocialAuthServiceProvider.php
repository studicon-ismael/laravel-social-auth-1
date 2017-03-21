<?php

namespace ZFort\SocialAuth;

use Illuminate\Support\ServiceProvider;
use ZFort\SocialAuth\Console\SocialDataTablesMigrationCommand;

class SocialAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
        __DIR__.'/../resources/config/laravel-permission.php' => $this->app->configPath().'/'.'laravel-permission.php',
        ], 'config');

        $resource_folder = __DIR__ . '/../../resources/';

        // Views
        $this->loadViewsFrom($resource_folder . 'views', 'social');

        $this->publishes([
            $resource_folder . 'views' =>resource_path('views/vendor/social'),
        ], 'views');

        // Routes
        require $resource_folder . 'routes/routes.php';

        // Share social Providers for views
        $views[] = view()->exists('vendor.social.attach')
            ? 'vendor.social.attach'
            : 'social::attach';

        $views[] = view()->exists('vendor.social.buttons')
            ? 'vendor.social.buttons'
            : 'social::buttons';

        view()->composer($views, function ($view) {
            $social_model = config('social.models.social');

            $view->with('socialProviders', $social_model::all());
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // register command for migration to version 2.0
        $this->app->singleton('command.social.migrate', function ($app) {
            return new SocialDataTablesMigrationCommand();
        });

        $this->commands('command.social.migrate');
    }
}