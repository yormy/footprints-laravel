<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;

class LoginListener extends BaseListener
{

    /**
     * @return void
     */
    public function handle(Login $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.auth_login')
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
