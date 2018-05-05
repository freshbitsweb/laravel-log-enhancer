<?php

namespace Freshbitsweb\LaravelLogEnhancer;

use Illuminate\Support\ServiceProvider;

class LaravelLogEnhancerServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel_log_enhancer.php' => config_path('laravel_log_enhancer.php'),
        ], 'laravel-log-enhancer-config');
    }

    /**
     * Make config publishment optional by merge the config from the package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel_log_enhancer.php',
            'laravel_log_enhancer'
        );
    }
}
