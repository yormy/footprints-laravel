<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;

class OtherDeviceLogoutListener extends BaseListener
{

    /**
     * @return void
     */
    public function handle(Login $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.auth_other_device_logout')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
            'log_type'   => LogType::AUTH_OTHER_DEVICE_LOGOUT,
        ]);
    }
}
