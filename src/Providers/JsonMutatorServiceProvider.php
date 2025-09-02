<?php

namespace JsonMutator\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Laravel JsonMutatorDTO Cast Package
 */
class JsonMutatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/json-mutator.php' => config_path('json-mutator.php'),
            ], 'json-mutator-config');
        }

        // Load configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/json-mutator.php', 'json-mutator'
        );
    }
}

