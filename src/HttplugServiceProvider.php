<?php

namespace Http\LaravelHttplug;

use Illuminate\Support\ServiceProvider;

/**
 * @author Daniel Nilsson <@danijeel>
 */
class HttplugServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-httplug.php' => $this->app->configPath().'/'.'laravel-httplug.php',
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-httplug.php', 'laravel-httplug');

        $this->app->singleton('httplug', function ($app) {
            return new HttplugManager($app);
        });

        $this->app->singleton('httplug.default', function ($app) {
            return $app['httplug']->driver();
        });
    }
}
