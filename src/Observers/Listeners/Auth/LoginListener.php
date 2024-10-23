<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
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

        $props = [
            'log_type' => LogType::AUTH_LOGIN,
        ];

        $loginSessionIdCookieName = config('footprints.cookies.login_session_id', false);
        if ($loginSessionIdCookieName) {
            $sessionId = Str::random(64);
            Cookie::queue($loginSessionIdCookieName, $sessionId, 60 * 24 * 7);

            $props['session_id'] = $sessionId;
        }

        $requestDto = RequestDto::fromRequest($this->request);

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }
}
