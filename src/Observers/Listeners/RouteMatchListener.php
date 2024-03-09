<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Routing\Events\RouteMatched;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Services\RuleService;

class RouteMatchListener extends BaseListener
{

    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    /**
     * @return void
     */
    public function handle(RouteMatched $event)
    {
        if (!$this->shouldLog($event)) {
            return;
        }

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $data = [
            'methods' => implode(',', $event->route->methods),
        ];

        $method = '';
        if (in_array(self::METHOD_GET, $event->route->methods)) {
            $method = self::METHOD_GET;
        } elseif (in_array(self::METHOD_PUT, $event->route->methods)) {
            $method = self::METHOD_GET;
        } elseif (in_array(self::METHOD_PATCH, $event->route->methods)) {
            $method = self::METHOD_PATCH;
        } elseif (in_array(self::METHOD_POST, $event->route->methods)) {
            $method = self::METHOD_POST;
        } elseif (in_array(self::METHOD_DELETE, $event->route->methods)) {
            $method = self::METHOD_DELETE;
        }


        $url = substr($this->getUrl($event),0, 150);
        $route = $this->getRouteName($event);
        $this->logItemRepository->createLogEntry(
            null, // no user at this time in the request cycle, update with user in the termination of the request
            $this->request,
            [
                'method' => $method,
                'route' => $route,
                'url' => $url,
                'log_type' => LogType::ROUTE_VISIT,
                'data' => json_encode($data),
            ]);
    }

    private function shouldLog(RouteMatched $event): bool
    {
        if (! config('footprints.enabled') ) {
            return false;
        }

        if (! config('footprints.log_events.route_visit') ) {
            return false;
        }

        $url = $this->getUrl($event);
        $route = $this->getRouteName($event);

        if ($url && RuleService::shouldIgnore($url, (array)config('footprints.log_visits.urls_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldIgnore($route, (array)config('footprints.log_visits.routes_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldInclude($route, (array)config('footprints.log_visits.routes_include'))) {
            return true;
        }

        if ($url && RuleService::shouldInclude($route, (array)config('footprints.log_visits.urls_include'))) {
            return true;
        }

        return false;
    }

    private function getRouteName(RouteMatched $event): string
    {
        $route = '';
        if (array_key_exists('as', $event->route->action)) {
            $route = (string)$event->route->action['as'];
        }

        return $route;
    }

    private function getUrl(RouteMatched $event): string
    {
        return $event->request->fullUrl();
    }
}
