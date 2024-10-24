<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;

class OtherListener extends BaseListener
{
    /**
     * @psalm-suppress MissingParamType
     */
    public function handle($event): void
    {
        if (! config('footprints.enabled')) {
            return;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        $props = [
            'log_type' => $this->getLogType($event),
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }

    /**
     * @psalm-suppress MissingParamType
     * @psalm-suppress MixedArgument
     */
    private function getLogType($event): string
    {
        $logEvents = (array) config('footprints.log_events.other_events');

        $eventClass = $event::class;

        if (array_key_exists($eventClass, $logEvents)) {
            return (string) $logEvents[$eventClass];
        }

        return LogType::UNKNOWN->value;
    }
}
