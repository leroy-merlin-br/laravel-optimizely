<?php
namespace LeroyMerlin\Optimizely;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Optimizely\Optimizely;

class OptimizelyFactory
{
    /**
     * OptimizelyFactory invocable.
     *
     * @param Application $app
     * @return Optimizely
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __invoke(Application $app): Optimizely
    {
        $optimizelyConfig = $app['config']['optimizely'];

        $file = Storage::disk($optimizelyConfig['disk'])->get($optimizelyConfig['filepath']);

        return new Optimizely($file);
    }
}
