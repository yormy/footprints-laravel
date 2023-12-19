<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\FootprintsLaravel\Enums\LogType;
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

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
                'log_type' => LogType::AUTH_OTHER_DEVICE_LOGOUT,
            ]
        );
    }
}
