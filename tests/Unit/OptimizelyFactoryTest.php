<?php
namespace Tests\Unit;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use LeroyMerlin\Optimizely\InvalidDatafileException;
use LeroyMerlin\Optimizely\OptimizelyFactory;
use Optimizely\Optimizely;
use Orchestra\Testbench\TestCase;

class OptimizelyFactoryTest extends TestCase
{
    public function test_should_instanciate_optimizely_with_config()
    {
        //Set
        config(['optimizely.key' => 'optimizely_datafile']);
        $factory = new OptimizelyFactory();

        // Expectations
        Redis::shouldReceive('get')
            ->with('optimizely_datafile')
            ->andReturn(file_get_contents(__DIR__ . '/../fixtures/datafile'));

        // Actions
        $result = $factory($this->app);

        // Assertions
        $this->assertInstanceOf(Optimizely::class, $result);
    }

    public function test_should_try_to_instanciate_and_throw_invalid_datafile_exception()
    {
        //Set
        config(['optimizely.key' => 'optimizely_datafile']);
        $factory = new OptimizelyFactory();

        // Expectations
        Redis::shouldReceive('get')
            ->with('optimizely_datafile')
            ->andReturn();

        $this->expectException(InvalidDatafileException::class);

        // Actions
        $result = $factory($this->app);

        // Assertions
        $this->assertInstanceOf(Optimizely::class, $result);
    }
}
