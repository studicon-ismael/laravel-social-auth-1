<?php

namespace ZFort\SocialAuth;

use Illuminate\Support\ServiceProvider;

class SocialAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param SocialProvidersLoader $loader
     * @return void
     */
    public function boot(SocialProvidersLoader $loader)
    {
        $resource_folder = __DIR__ . '/../resources';

        $this->publishes([
            $resource_folder . '/config/social-auth.php' => $this->app->configPath().'/'.'social-auth.php',
        ], 'config');

        if (!class_exists('CreateSocialProvidersTable')) {
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                $resource_folder . '/database/migrations/create_social_providers_table.php.stub' =>
                $this->app->databasePath().'/migrations/'.$timestamp.'_create_social_providers_table.php',
            ], 'migrations');
        }

        // Views
        $this->loadViewsFrom(resource_path('views/vendor/social'), 'social-auth');
        $this->loadViewsFrom($resource_folder . '/views', 'social-auth');

        $this->publishes([
            $resource_folder . '/views' => resource_path('views/vendor/social'),
        ], 'views');

        // Routes
        require $resource_folder . '/routes/routes.php';

        // Share social Providers for views
        view()->composer(['social-auth::buttons', 'social-auth::attach'], function ($view) use ($loader) {
            /**
             * @var \Illuminate\View\View $view
             */
            $view->with('socialProviders', $loader->getSocialProviders());
        });

        $loader->registerSocialProviders();

        $this->app->register(\SocialiteProviders\Manager\ServiceProvider::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/social-auth.php',
            'social-auth'
        );
    }
}
