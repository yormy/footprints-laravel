<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Logout;
use LogType;

class LogoutListener extends BaseListener
{

    public function handle(Logout $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_logout')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
                'log_type'   => LogType::LOGOUT,
            ]);
    }
}
