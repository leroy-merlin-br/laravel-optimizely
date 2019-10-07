<?php
namespace LeroyMerlin\Optimizely\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Config\Repository;
use Mockery;
use Orchestra\Testbench\TestCase;

class WebHookTokenTest extends TestCase
{
    public function test_handle_should_not_authenticate()
    {
        $config = Mockery::mock(Repository::class);
        $request = Mockery::mock(Repository::class);
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
        $request = Mockery::mock(Repository::class);
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
            ->andReturnUsing(function () {
                return sprintf("sha1=%s", hash_hmac('sha1', 'some_payload', 'SECRET_KEY'));
            });

        $result = $webHookToken->handle($request, function ($request) {
            return true;
        });

        $this->assertTrue($result);
    }
}
