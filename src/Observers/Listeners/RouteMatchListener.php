<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Routing\Events\RouteMatched;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Services\RuleService;

class RouteMatchListener extends BaseListener
{
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

        $url = $this->getUrl($event);
        $route = $this->getRouteName($event);
        $this->logItemRepository->createLogEntry(
            null, // no user at this time in the request cycle, update with user in the termination of the request
            $this->request,
            [
                'route' => $route,
                'url' => $url,
                'log_type' => LogType::ROUTE_VISIT,
                'data' => json_encode($data),
            ]);
    }

    private function shouldLog(RouteMatched $event): bool
    {
        if (! config('footsteps.enabled') ) {
            return false;
        }

        if (! config('footsteps.log_events.route_visit') ) {
            return false;
        }

        $url = $this->getUrl($event);
        $route = $this->getRouteName($event);

        if ($url && RuleService::shouldIgnore($url, config('footsteps.log_visits.urls_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldIgnore($route, config('footsteps.log_visits.routes_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldInclude($route, config('footsteps.log_visits.routes_include'))) {
            return true;
        }

        if ($url && RuleService::shouldInclude($route, config('footsteps.log_visits.urls_include'))) {
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
