<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Observers\Events\RequestTerminatedEvent;

class RequestTerminatedListener extends BaseListener
{
    public function handle(RequestTerminatedEvent $event): void
    {
        if (!$this->shouldLog()) {
            return;
        }

        $request = $event->getRequest();

        $duration = $this->getDuration();

        $response = $event->getResponse();

        $requestId = (string)$request->get('request_id');

        $this->logItemRepository->updateLogEntry($requestId, $duration, $response);
    }

    private function shouldLog(): bool
    {
        if (! config('footprints.enabled') ) {
            return false;
        }

        if (
            ! config('footprints.content.duration') &&
            ! config('footprints.content.response')
        ) {
            return false;
        }

        return true;
    }

    private function getDuration(): float
    {
        /**
         * @psalm-suppress UndefinedConstant
         */
        $requestStart = (float)LARAVEL_START;

        $duration = 0;
        if ($requestStart > 0) {
            $duration = round(microtime(true) - $requestStart, 3);
        }

        return $duration;
    }
}
