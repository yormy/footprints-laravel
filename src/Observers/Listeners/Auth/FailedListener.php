<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;

class FailedListener extends BaseListener
{

    public function handle(Login $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.auth_failed')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
            'log_type'   => LogType::AUTH_LOGIN,
        ]);
    }
}
