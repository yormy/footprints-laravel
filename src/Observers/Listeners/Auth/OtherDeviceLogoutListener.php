<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class OtherDeviceLogoutListener extends BaseListener
{
    public function handle(Login $event): void
    {
        // @phpstan-ignore-next-line
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_other_device_logout')
        ) {
            return;
        }

        $props = [
            'log_type' => LogType::AUTH_OTHER_DEVICE_LOGOUT,
        ];

        $requestDto = RequestDto::fromRequest($this->request);

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
