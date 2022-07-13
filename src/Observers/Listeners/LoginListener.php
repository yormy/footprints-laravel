<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Login;

class LoginListener extends BaseListener
{

    public function handle(Login $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_login')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
            'log_type'   => 'login',
        ]);
    }
}
