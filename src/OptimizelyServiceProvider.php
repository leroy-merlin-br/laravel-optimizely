<?php
namespace LeroyMerlin\Optimizely;

use Closure;
use Illuminate\Support\ServiceProvider;
use Optimizely\Optimizely;

class OptimizelyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            Optimizely::class,
            Closure::fromCallable(new OptimizelyFactory())
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/optimizely.php' => config_path('optimizely.php'),
        ]);
    }
}
