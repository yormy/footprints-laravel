<?php

namespace Yormy\LaravelFootsteps\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;
use \Illuminate\Http\Response;

class AddTracking
{
    protected string $requestId;

    protected float $startTime = 0;

    public function __construct()
    {
        $this->requestId = $this->generateKey();
    }

    /**
     * @psalm-suppress UndefinedPropertyFetch
     * @psalm-suppress MixedMethodCall
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->startTime = microtime(true);
        $this->requestId = $this->generateKey();

        $request->attributes->add(['request_id' => $this->requestId]);
        $request->attributes->add(['request_start' => $this->startTime]);

        return $next($request);
    }

    private function generateKey(): string
    {
        $bytes = random_bytes(20);

        return bin2hex($bytes);
    }

    public function terminate(Request $request, Response $response): void
    {
        event(new RequestTerminatedEvent($request, $response));
    }
}
