<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Yormy\FootprintsLaravel\Jobs\FootprintsUpdateLogJob;
use Yormy\FootprintsLaravel\Observers\Events\RequestTerminatedEvent;

class RequestTerminatedListener extends BaseListener
{
    public function handle(RequestTerminatedEvent $event): void
    {
        if (! $this->shouldLog()) {
            return;
        }

        $duration = $this->getDuration();
        $response = $event->getResponse();

        $requestId = (string) $this->request->get('request_id');

        FootprintsUpdateLogJob::dispatch($requestId, $duration, $response);

    }

    private function shouldLog(): bool
    {
        if (! config('footprints.enabled')) {
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
        $requestStart = (float) LARAVEL_START;

        $duration = 0;
        if ($requestStart > 0) {
            $duration = round(microtime(true) - $requestStart, 3);
        }

        return $duration;
    }
}
