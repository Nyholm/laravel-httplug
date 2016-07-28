<?php

/*
 * This file is part of the laravel-httplug Project.
 *
 * (c) laravel-httplug <mathieu.santostefano@gmail.com>
 */

namespace Http\LaravelHttplug;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Illuminate\Support\ServiceProvider;

class HttplugServiceProvider extends ServiceProvider
{
    /**
     * Factories by type.
     *
     * @var array
     */
    private $factoryClasses = [
        'client' => HttpClientDiscovery::class,
        'message_factory' => MessageFactoryDiscovery::class,
        'uri_factory' => UriFactoryDiscovery::class,
        'stream_factory' => StreamFactoryDiscovery::class,
    ];

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
        $this->app->bind(Httplug::class, function () {
            return new Httplug();
        });

        $config = config('laravel-httplug');

        foreach ($config['classes'] as $service => $class) {
            if (!empty($class)) {
                $this->app->register(sprintf('httplug.%s.default', $service), function() use($class) {
                    return new $class();
                });
            } else {
                // Find by auto discovery
                $factoryClass = $this->factoryClasses[$class];
                $this->app->register(sprintf('httplug.%s.default', $service), function() use($factoryClass) {
                    return $factoryClass::find();
                });
            }
        }

        foreach ($config['main_alias'] as $type => $id) {
            $this->app->alias(sprintf('httplug.%s', $type), $id);
        }

        $this->app->alias(Httplug::class, 'laravel-httplug');
    }
}
