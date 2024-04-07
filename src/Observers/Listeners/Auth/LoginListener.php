<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class LoginListener extends BaseListener
{
    public function handle(Login $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_login')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
                'log_type' => LogType::AUTH_LOGIN,
            ]
        );
    }
}
