<?php
namespace Tests\Integration;

use LeroyMerlin\Optimizely\OptimizelyServiceProvider;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class OptimizelyControllerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [OptimizelyServiceProvider::class];
    }

    public function test_should_try_to_get_datafile_url_and_fail(): void
    {
        config(['optimizely.webhook_secret' => 'verysecret']);
        $payload = [
            'project_id' => 1234,
            'timestamp' => 1468447113,
            'event' => 'project.datafile_updated',
            'data' => [
                'revision' => 1,
                'origin_url' => 'https://optimizely.s3.amazonaws.com/json/1234.json',
                'cdn_url' => '../fixtures/invalid_path',
                'environment' => 'Production'
            ]
        ];

        // Actions
        $response = $this->post('/webhooks/optimizely', $payload, [
            'X-Hub-Signature' => 'sha1=fec4ff24a565056b701bbd105f99c268f725451c'
        ]);

        // Assertions
        $response->assertStatus(400);
        $response->assertJson(['Could not get datafile contents']);
    }

    public function test_should_download_datafile_succesfully()
    {
        // Set
        config(['optimizely.disk' => 'local']);
        config(['optimizely.webhook_secret' => 'verysecret']);
        config(['optimizely.filepath' => 'storage/optimizely_datafile']);

        Storage::fake();

        $payload = [
            'project_id' => 1234,
            'timestamp' => 1468447113,
            'event' => 'project.datafile_updated',
            'data' => [
                'revision' => 1,
                'origin_url' => 'https://optimizely.s3.amazonaws.com/json/1234.json',
                'cdn_url' => __DIR__ . '/../fixtures/datafile',
                'environment' => 'Production'
            ]
        ];

        // Expectations
        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->with('storage/optimizely_datafile', file_get_contents(__DIR__ . '/../fixtures/datafile'))
            ->andReturn(true);


        // Actions
        $response = $this->post('/webhooks/optimizely', $payload, [
            'X-Hub-Signature' => 'sha1=fec4ff24a565056b701bbd105f99c268f725451c'
        ]);

        // Assertions
        $response->assertStatus(201);

    }
}
