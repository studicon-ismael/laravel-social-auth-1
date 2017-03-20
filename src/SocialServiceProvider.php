<?php

namespace Social;

use Social\Console\SocialDataTablesMigrationCommand;
use Social\Models\SocialProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SocialServiceProvider extends ServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // регитсрировать ли миграцию если они есть у пакета - посомтреть в сторонних пакетах



        $resourceFolder = __DIR__ . '/../../resources/';

        // Views
        $this->loadViewsFrom($resourceFolder . 'views', 'social');

        $this->publishes([
            $resourceFolder . 'views' =>resource_path('views/vendor/social'),
        ], 'views');

        // Routes
        require $resourceFolder . 'routes/routes.php';

        // Share social Providers for views
        $views[] = view()->exists('vendor.social.attach')
            ? 'vendor.social.attach'
            : 'social::attach';

        $views[] = view()->exists('vendor.social.buttons')
            ? 'vendor.social.buttons'
            : 'social::buttons';

        view()->composer($views, function ($view) {
            $view->with('socialProviders', SocialProvider::all());
        });
    }


    /**
     *
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
