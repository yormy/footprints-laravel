<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class LockoutListener extends BaseListener
{
    public function handle(Lockout $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_login')
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
