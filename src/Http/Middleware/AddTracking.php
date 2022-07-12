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

    private function generateKey()
    {
        return md5(uniqid(mt_rand(), true));
    }

    public function terminate($request, $response)
    {
        event(new RequestTerminatedEvent($request, $response));
    }
}



