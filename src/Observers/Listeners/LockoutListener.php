<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;

class LockoutListener extends BaseListener
{

    public function handle(Lockout $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_login')
        ) {
            return;
        }

        $user = $this->findUser();
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            ['log_type'   => 'lockout']
        );
    }

    private function findUser() : ?Authenticatable
    {
        // how to locate the locked out user ?
        return null;
    }

}
