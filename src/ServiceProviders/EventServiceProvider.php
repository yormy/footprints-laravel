<?php

namespace Yormy\LaravelFootsteps\ServiceProviders;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Yormy\LaravelFootsteps\Observers\Events\CustomFootstepEvent;
use Yormy\LaravelFootsteps\Observers\Events\ExceptionEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\Auth\FailedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Auth\LockoutListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Auth\LoginListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Auth\LogoutListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Auth\OtherDeviceLogoutListener;
use Yormy\LaravelFootsteps\Observers\Listeners\CustomListener;
use Yormy\LaravelFootsteps\Observers\Listeners\ExceptionListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Model\ModelCreatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Model\ModelDeletedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\Model\ModelUpdatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\OtherListener;
use Yormy\LaravelFootsteps\Observers\Listeners\RequestTerminatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\RouteMatchListener;
use Illuminate\Contracts\Foundation\Application\App;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LoginListener::class,
        ],

        Failed::class => [
            FailedListener::class,
        ],

        OtherDeviceLogout::class => [
            OtherDeviceLogoutListener::class,
        ],

        Logout::class => [
            LogoutListener::class,
        ],

        Lockout::class => [
            LockoutListener::class,
        ],

        RouteMatched::class => [
            RouteMatchListener::class,
        ],

        RequestTerminatedEvent::class => [
            RequestTerminatedListener::class,
        ],

        ModelCreatedEvent::class => [
            ModelCreatedListener::class,
        ],

        ModelUpdatedEvent::class => [
            ModelUpdatedListener::class,
        ],

        ModelDeletedEvent::class => [
            ModelDeletedListener::class,
        ],

        CustomFootstepEvent::class => [
            CustomListener::class,
        ],

        ExceptionEvent::class => [
            ExceptionListener::class,
        ],
    ];

    public function __construct(Application $app)
    {
        $otherEvents = config('footsteps.log_events.other_events');

        foreach ($otherEvents as $event => $logType) {
            $this->listen[$event] = [OtherListener::class];
        }

        parent::__construct($app);
    }
}
