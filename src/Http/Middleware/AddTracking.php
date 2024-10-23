<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Observers\Events\RequestTerminatedEvent;

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
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->startTime = microtime(true);
        $this->requestId = $this->generateKey();

        $request->attributes->add(['request_id' => $this->requestId]);

        $fingerprintCookieName = config('footprints.browser_fingerprint_cookie_name', 'browser_fingerprint');
        $request->attributes->add(['browser_fingerprint' => $request->cookie($fingerprintCookieName)]);

        return $next($request);
    }

    /**
     * @psalm-suppress MixedMethodCall
     */
    public function terminate(Request $request, mixed $response): void
    {
        if ($response instanceof RedirectResponse) {
            $responseString = 'redirect';
        } else {
            $responseString = (string) $response->getContent();
        }

        event(new RequestTerminatedEvent($request, $responseString));
    }

    private function generateKey(): string
    {
        $bytes = random_bytes(20);

        return bin2hex($bytes);
    }
}
