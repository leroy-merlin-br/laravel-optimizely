<?php

use Orchestra\Testbench\TestCase;

class OptimizelyControllerTest extends TestCase
{
    public function test_should_try_to_get_datafile_url_and_fail()
    {
        // Set
        $payload = [
            [
                'project_id' => 1234,
                'timestamp' => 1468447113,
                'event' => 'project.datafile_updated',
                'data' => [
                    'revision' => 1,
                    'origin_url' => 'https =>//optimizely.s3.amazonaws.com/json/1234.json',
//                    'cdn_url' => 'https =>//cdn.optimizely.com/json/1234.json',
                    'environment' => 'Production'
                ]
            ]
        ];

        // Actions
        $response = $this->post('/webhook/optimizely', $payload);

        // Assertions
        $response->assertStatus(400);
        $response->assertJson(['Could not get datafile URL']);
    }
}
