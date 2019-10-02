<?php
namespace App;

use Illuminate\Support\ServiceProvider;

class OptimizelyServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/optimizely.php' => config_path('optimizely.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
    }
}
