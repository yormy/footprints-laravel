<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Events\RouteMatched;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;


class RouteMatchListener
{
    use LoggingTrait;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(RouteMatched $event)
    {
        if (!config('footsteps.log_events.on_route')) {
            return;
        }

        $url = $event->request->fullUrl();
        $ignoreUrls = config('footsteps.ignore_urls');
        if ($this->shouldIgnore($url, $ignoreUrls)) {
            return;
        }

        $route = '';
        if (array_key_exists('as', $event->route->action)) {
            $route = $event->route->action['as'];

            $ignoreRoutes = config('footsteps.ignore_routes');
            if ($this->shouldIgnore($route, $ignoreRoutes)) {
                return;
            }
        }

        $requestId = $event->request->get('request_id');
        $requestStart = $event->request->get('request_start');
        $methods = implode(',', $event->route->methods);

        $payload = $event->request->getContent();
        $payload = $this->cleanPayload($payload);

        $data = [
            'methods' => $methods,
        ];

        static::createLogEntry(
            null,
            $this->request,
            [
            'route' => "$route",
            'url' => $url,
            'request_id' => $requestId,
            'request_start' => $requestStart,
            'payload_base64' => $payload,
            'table_name' => '',
            'log_type'   => 'route',
            'data'       => json_encode($data)
        ]);

    }

    private function shouldIgnore(string $route, array $ignoreRoutes)
    {
        foreach ($ignoreRoutes as $ignorePattern) {
            if (fnmatch($ignorePattern, $route)) {
                return true;
            }
        }

        return false;
    }
}
