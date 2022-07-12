<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;

class LockoutListener
{
    use LoggingTrait;

    private $userInstance = "\App\User";

    public function __construct(Request $request)
    {
        $this->request = $request;

        $userInstance = config('footsteps.model.user');
        if(!empty($userInstance)) $this->userInstance = $userInstance;
    }


    public function handle($event)
    {
        if (!config('footsteps.log_events.on_lockout', false)
            || !config('footsteps.activated', true)) return;

        if (!$event->request->has('email')) return;
        $user = $this->userInstance::where('email', $event->request->input('email'))->first();
        if (!$user) return;

        $user = $event->user;

        static::createLogEntry(
            $user,
            $this->request,
            [
                'table_name' => '',
                'log_type'   => 'lockout',
            ]);


    }
}
