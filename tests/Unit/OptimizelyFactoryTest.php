<?php
namespace LeroyMerlin\Optimizely;

use Optimizely\Optimizely;
use Orchestra\Testbench\TestCase;
use Mockery;

class OptimizelyFactoryTest extends TestCase
{
    public function test_should_instanciate_optimizely_with_config()
    {
        //Set
        $this->instance(Optimizely::class, Mockery::mock(Optimizely::class));
        config(['optimizely.filepath' => 'storage/optimizely/datafile.json']);
        config(['optimizely.disk' => 'sideralspace']);



    }
}
