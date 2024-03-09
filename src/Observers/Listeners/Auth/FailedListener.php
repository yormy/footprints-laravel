<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class FailedListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(Failed $event)
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_failed')
        ) {
            return;
        }

        $user = $event->user;
        $this->logItemRepository->createLogEntry(
            $user,
            $this->request,
            [
                'log_type' => LogType::AUTH_FAILED,
            ]);
    }
}
