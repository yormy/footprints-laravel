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
        if ($this->shouldLog($event)) {
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

        if ($this->shouldIgnore($event)) {
            return false;
        }

        return true;
    }

    private function shouldIgnore(RouteMatched $event): bool
    {
        $url = $this->getUrl($event);

        /**
         * @var array $ignoreUrls
         */
        $ignoreUrls = config('footsteps.ignore_urls');
        if (RuleService::shouldIgnore($url, $ignoreUrls)) {
            return true;
        }

        $route = $this->getRouteName($event);
        if ($route) {
            /**
             * @var array $ignoreRoutes
             */
            $ignoreRoutes = config('footsteps.ignore_routes');
            if (RuleService::shouldIgnore($route, $ignoreRoutes)) {
                return true;
            }
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

//    private function shouldIgnoreRule(string $route, array $ignoreRoutes): bool
//    {
//        /**
//         * @var array<array-key, string> $ignoreRoutes
//         */
//        foreach ($ignoreRoutes as $ignorePattern) {
//            if (fnmatch($ignorePattern, $route)) {
//                return true;
//            }
//        }
//
//        return false;
//    }
}
