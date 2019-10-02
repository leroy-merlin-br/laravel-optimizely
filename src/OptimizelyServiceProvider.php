<?php

use Illuminate\Support\ServiceProvider;

class OptimizelyServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/path/to/config/courier.php' => config_path('courier.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
    }
}
