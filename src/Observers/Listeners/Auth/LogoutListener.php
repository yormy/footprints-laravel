<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;

class LogoutListener extends BaseListener
{
    public function handle(Logout $event): void
    {
        $loginSessionIdCookieName = config('footprints.cookies.login_session_id', false);
        if ($loginSessionIdCookieName) {
            Cookie::queue($loginSessionIdCookieName, '', 0); // clear tracking cookie
        }

        // @phpstan-ignore-next-line
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.auth_logout')
        ) {
            return;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        $props = [
            'log_type' => LogType::AUTH_LOGOUT,
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
