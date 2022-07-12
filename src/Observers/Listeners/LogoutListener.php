<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;

class LogoutListener
{
    use LoggingTrait;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Logout $event)
    {
        if (!config('footsteps.log_events.on_logout', false)
            || !config('footsteps.activated', true)) return;

        $user = $event->user;

        static::createLogEntry(
            $user,
            $this->request,
            [
            'table_name' => '',
            'log_type'   => 'logout',
        ]);
    }
}
