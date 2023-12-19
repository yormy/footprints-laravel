<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Events\CustomFootprintEvent;

class CustomListener extends BaseListener
{
    public function handle(CustomFootprintEvent $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.on_custom')
        ) {
            return;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        $data = $event->getData();
        $props = [
            'log_type' => $event->getType(),
            'custom_data' => json_encode($data),
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
