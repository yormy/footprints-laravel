<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners\Http;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;
use Yormy\FootprintsLaravel\Services\RuleService;

class RequestHandledListener extends BaseListener
{
    public function handle(RequestHandled $event): void
    {
        $request = $event->request;
        $response = $event->response;

        if (! $this->shouldLog($request)) {
            return;
        }

        $requestDto = RequestDto::fromRequest($request);

        $props = [
            'log_type' => LogType::ROUTE_VISIT,
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }

    private function shouldLog(Request $request): bool
    {
        if (! config('footprints.enabled')) {
            return false;
        }

        if (! config('footprints.log_events.route_visit')) {
            return false;
        }

        $url = $request->fullUrl();
        $route = null;
        if (is_object($request->route())) {
            $route = $request->route()->getName();
        }

        if ($url && RuleService::shouldIgnore($url, (array) config('footprints.log_visits.urls_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldIgnore($route, (array) config('footprints.log_visits.routes_exclude'))) {
            return false;
        }

        if ($route && RuleService::shouldInclude($route, (array) config('footprints.log_visits.routes_include'))) {
            return true;
        }

        if ($route && $url && RuleService::shouldInclude($route, (array) config('footprints.log_visits.urls_include'))) {
            return true;
        }

        return false;
    }
}
