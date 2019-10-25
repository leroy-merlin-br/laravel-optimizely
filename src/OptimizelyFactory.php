<?php
namespace LeroyMerlin\Optimizely;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redis;
use Optimizely\Optimizely;

class OptimizelyFactory
{
    /**
     * OptimizelyFactory invocable.
     *
     * @param Application $app
     * @return Optimizely
     * @throws InvalidDatafileException
     */
    public function __invoke(Application $app): Optimizely
    {
        $optimizelyConfig = $app['config']['optimizely'];

        $file = Redis::get($optimizelyConfig['key']);

        if (!$file) {
            throw new InvalidDatafileException();
        }

        return new Optimizely($file);
    }
}
