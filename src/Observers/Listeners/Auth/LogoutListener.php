<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;

class LogoutListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(Logout $event)
    {
        if (! config('footsteps.enabled') ||
            ! config('footsteps.log_events.auth_logout')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
                'log_type' => LogType::AUTH_LOGOUT,
            ]);
    }
}
