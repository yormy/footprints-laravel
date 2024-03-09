<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Model;

use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Events\ModelCreatedEvent;

class ModelCreatedListener extends ModelBaseListener
{
    public function handle(ModelCreatedEvent $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.model_created')
        ) {
            return;
        }

        $request = $event->getRequest();
        $requestDto = RequestDto::fromRequest($request);

        $props = $this->getData($event);
        $props['log_type'] = LogType::MODEL_CREATED;

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
