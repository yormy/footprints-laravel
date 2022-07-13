<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Http\Client\Request;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;

class RequestTerminatedListener extends BaseListener
{
    public function handle(RequestTerminatedEvent $event): void
    {
        $request = $event->getRequest();

        $duration = $this->getDuration($request);

        $response = $event->getResponse()->getContent();
        if (!$response) {
            $response = '';
        }

        $requestId = (string)$request->get('request_id');

        $this->logItemRepository->updateLogEntry($requestId, $duration, $response);
    }

    private function getDuration(Request $request): float
    {
        $requestStart = (int)$request->get('request_start');

        $duration = 0;
        if ($requestStart > 0) {
            $duration = round(microtime(true) - $requestStart, 3);
        }

        return $duration;
    }
}
