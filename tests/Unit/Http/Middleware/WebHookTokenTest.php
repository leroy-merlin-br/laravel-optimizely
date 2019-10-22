<?php
namespace LeroyMerlin\Optimizely\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Mockery;
use Orchestra\Testbench\TestCase;

class WebHookTokenTest extends TestCase
{
    public function test_handle_should_not_authenticate()
    {
        $config = Mockery::mock(Repository::class);
        $request = Mockery::mock(Request::class);
        $webHookToken = new WebHookToken($config);

        $config
            ->expects('get')
            ->once()
            ->with('optimizely.webhook_secret')
            ->andReturn('SECRET_KEY');

        $request
            ->expects('getContent')
            ->once()
            ->andReturn('some_payload');

        $request
            ->expects('header')
            ->once()
            ->with('X-Hub-Signature')
            ->andReturn('sha1=14122c9cb8adf1b2948aa690aa70d086');

        $this->expectException(AuthenticationException::class);

        $webHookToken->handle($request, function () {});
    }

    public function test_handle_should_authenticate()
    {
        $config = Mockery::mock(Repository::class);
        $request = Mockery::mock(Request::class);
        $webHookToken = new WebHookToken($config);

        $config
            ->expects('get')
            ->once()
            ->with('optimizely.webhook_secret')
            ->andReturn('yIRFMTpsBcAKKRjJPCIykNo6EkNxJn_nq01-_r3S8i4');

        $request
            ->expects('getContent')
            ->once()
            ->andReturn(
                json_encode(json_decode(file_get_contents(__DIR__ . '/../../../fixtures/valid_webhook_payload.json')))
            );

        $request
            ->expects('header')
            ->once()
            ->with('X-Hub-Signature')
            ->andReturn("sha1=470aa687e4674516708fb8299b1458855e19c69b");

        $result = $webHookToken->handle($request, function ($request) {
            return true;
        });

        $this->assertTrue($result);
    }
}
