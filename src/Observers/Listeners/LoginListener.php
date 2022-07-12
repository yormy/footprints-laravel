<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;

class LoginListener
{
    use LoggingTrait;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        if (!config('footsteps.log_events.on_login', false)
            || !config('footsteps.activated', true)) return;

        $user = $event->user;
        static::createLogEntry(
            $user,
            $this->request,
            [
            'table_name' => '',
            'log_type'   => 'login',
        ]);
    }
}
