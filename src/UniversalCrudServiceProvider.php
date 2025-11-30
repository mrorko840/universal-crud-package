<?php

namespace Hashtag\UniversalCrud;

use Illuminate\Support\ServiceProvider;

class UniversalCrudServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // config publish
        $this->publishes([
            __DIR__.'/../config/universal-crud.php' => config_path('universal-crud.php'),
        ], 'universal-crud-config');

        // routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    public function register(): void
    {
        // default config merge
        $this->mergeConfigFrom(
            __DIR__.'/../config/universal-crud.php',
            'universal-crud'
        );
    }
}
