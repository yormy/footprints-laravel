<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class FailedListener extends BaseListener
{
    public function handle(Failed $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_failed')
        ) {
            return;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        $props = [
            'log_type' => LogType::AUTH_FAILED,
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
