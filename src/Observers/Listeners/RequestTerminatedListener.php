<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Http\Request;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;

class RequestTerminatedListener extends BaseListener
{
    public function handle(RequestTerminatedEvent $event)
    {
        $request = $event->getRequest();

        $duration = $this->getDuration($request);

        $response = $event->getResponse()->getContent();

        $requestId = $request->get('request_id');

        $this->logItemRepository->updateLogEntry($requestId, $duration, $response);
    }

    private function getDuration(Request $request): ?float
    {
        $requestStart = $request->get('request_start');

        $duration = null;
        if ($requestStart > 0) {
            $duration = round(microtime(true) - $requestStart,3);
        }

        return $duration;
    }

}
