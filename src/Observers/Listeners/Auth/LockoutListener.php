<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;

class LockoutListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(Lockout $event)
    {
        if (! config('footsteps.enabled') ||
            ! config('footsteps.log_events.auth_login')
        ) {
            return;
        }

        $user = $this->findUser();
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            ['log_type' => LogType::AUTH_LOCKEDOUT]
        );
    }

    private function findUser(): ?Authenticatable
    {
        // how to locate the locked out user ?
        return null;
    }
}
