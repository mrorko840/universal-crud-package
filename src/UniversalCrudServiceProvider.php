<?php

namespace UniversalCrud;

use Illuminate\Support\ServiceProvider;

class UniversalCrudServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/universal-crud.php' => config_path('universal-crud.php'),
        ], 'universal-crud-config');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/universal-crud.php',
            'universal-crud'
        );
    }
}
