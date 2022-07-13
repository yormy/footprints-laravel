<?php

namespace Yormy\LaravelFootsteps\Http\Middleware;

use Closure;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;

class AddTracking
{
    protected $requestId;

    protected $startTime;

    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        $this->requestId = $this->generateKey();

        $request->attributes->add(["request_id" => $this->requestId]);
        $request->attributes->add(["request_start" => $this->startTime]);

        return $next($request);
    }

    private function generateKey(): string
    {
        $bytes = random_bytes(20);
        return bin2hex($bytes);
    }

    public function terminate($request, $response): void
    {
        event(new RequestTerminatedEvent($request, $response));
    }
}



