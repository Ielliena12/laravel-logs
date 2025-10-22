<?php

namespace Ielliena12\LaravelLogs;

use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/laravel-logs'),
        ], 'laravel-logs-views');

        $this->publishes([
            __DIR__ . '/../config/laravel-logs.php' => config_path('laravel-logs.php'),
        ], 'laravel-logs-config');

        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'laravel-logs');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-logs.php', 'laravel-logs'
        );
    }
}
