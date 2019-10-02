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
        $filepath = Storage::disk($app['config']['optimizely']['disk'])->get($app['config']['optimizely']['path']);

        return new Optimizely(file_get_contents($filepath));
    }
}
