<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Lockout;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class LockoutListener extends BaseListener
{
    public function handle(Lockout $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_login')
        ) {
            return;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        $props = [
            'log_type' => LogType::AUTH_LOCKEDOUT,
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
