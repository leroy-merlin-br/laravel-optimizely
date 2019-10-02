<?php

namespace Tests\Integration;

use App\OptimizelyServiceProvider;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class OptimizelyControllerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [OptimizelyServiceProvider::class];
    }

    /**
     * @dataProvider invalidPayloadProvider
     */
    public function test_should_try_to_get_datafile_url_and_fail(array $payload, int $responseCode, array $message): void
    {
        // Actions
        $response = $this->post('/webhooks/optimizely', $payload);

        // Assertions
        $response->assertStatus($responseCode);
        $response->assertJson($message);
    }

    public function test_should_download_datafile_succesfully()
    {
        // Set
        config(['optimizely.disk' => 'local']);
        config(['optimizely.path' => 'storage']);
        Storage::fake();

        $payload = [
            'project_id' => 1234,
            'timestamp' => 1468447113,
            'event' => 'project.datafile_updated',
            'data' => [
                'revision' => 1,
                'origin_url' => 'https =>//optimizely.s3.amazonaws.com/json/1234.json',
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
        $response = $this->post('/webhooks/optimizely', $payload);

        // Assertions
        $response->assertStatus(201);

    }

    public function invalidPayloadProvider()
    {
        return [
            'No Datafile URL' => [
                'payload' => [
                    'project_id' => 1234,
                    'timestamp' => 1468447113,
                    'event' => 'project.datafile_updated',
                    'data' => [
                        'revision' => 1,
                        'origin_url' => 'https =>//optimizely.s3.amazonaws.com/json/1234.json',
                        'cdn_url' => '',
                        'environment' => 'Production'
                    ]
                ],
                'responseCode' => 400,
                'responseMessage' => ['Could not get datafile URL']
            ],
            'Invalid Datafile' => [
                'payload' => [
                    'project_id' => 1234,
                    'timestamp' => 1468447113,
                    'event' => 'project.datafile_updated',
                    'data' => [
                        'revision' => 1,
                        'origin_url' => 'https =>//optimizely.s3.amazonaws.com/json/1234.json',
                        'cdn_url' => '../fixtures/invalid_datafile',
                        'environment' => 'Production'
                    ]
                ],
                'responseCode' => 400,
                'responseMessage' => ['Could not get datafile contents']
            ],
        ];
    }
}
