<?php
namespace Tests\Unit;

use Illuminate\Contracts\Foundation\Application as ApplicationInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use LeroyMerlin\Optimizely\OptimizelyFactory;
use Optimizely\Optimizely;
use Orchestra\Testbench\TestCase;
use Mockery;

class OptimizelyFactoryTest extends TestCase
{
    public function test_should_instanciate_optimizely_with_config()
    {
        //Set
        Storage::fake();
        $this->instance(Optimizely::class, Mockery::mock(Optimizely::class));

        $factory = new OptimizelyFactory();

        config(['optimizely.filepath' => __DIR__ . '/../fixtures/datafile']);
        config(['optimizely.disk' => 'sideralspace']);

        // Expectations
        Storage::shouldReceive('disk')
            ->with('sideralspace')
            ->andReturnSelf();

        Storage::shouldReceive('get')
            ->with(__DIR__ . '/../fixtures/datafile')
            ->andReturn(file_get_contents(__DIR__ . '/../fixtures/datafile'));

        // Actions
        $result = $factory($this->app);

        // Assertions
        $this->assertInstanceOf(Optimizely::class, $result);
    }
}
