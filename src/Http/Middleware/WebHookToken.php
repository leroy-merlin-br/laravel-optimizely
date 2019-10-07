<?php

declare(strict_types=1);

namespace LeroyMerlin\Optimizely\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;

final class WebHookToken
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;

    public function __construct(Repository $repository)
    {
        $this->config = $repository;
    }

    /**
     * @param mixed     $request
     * @param \Closure  $next
     *
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $hash = hash_hmac(
            'sha1',
            $request->getContent(),
            $this->config->get('optimizely.webhook_secret')
        );

        if (! hash_equals("sha1={$hash}", $request->header('X-Hub-Signature'))) {
            throw new AuthenticationException();
        }

        return $next($request);
    }
}
