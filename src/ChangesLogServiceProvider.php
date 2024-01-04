<?php

namespace ChangesLog;

use Illuminate\Support\ServiceProvider;

class ChangesLogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Load the default config values
        $this->mergeConfigFrom(__DIR__ . '/../config/changeslog.php', 'changeslog');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
//        $this->loadViewsFrom(__DIR__ . '/views', 'simple');
//        if (!$this->app->routesAreCached()) {
//            require __DIR__ . '/routes.php';
//        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish the config/changeslog.php file
        $this->publishes([
            __DIR__ . '/../config/changeslog.php' => config_path('changeslog.php'),
        ], 'changeslog-config');

        // Publish Model
        $this->publishes([
            __DIR__ . '/../Models/CustomChangesLog.php' => app_path('Models/ChangesLog.php'),
        ], 'changeslog-config');
    }
}
