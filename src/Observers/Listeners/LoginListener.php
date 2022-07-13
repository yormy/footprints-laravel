<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;

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
