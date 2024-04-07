<?php

namespace Yormy\FootprintsLaravel\ServiceProviders;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Yormy\FootprintsLaravel\Observers\Events\CustomFootprintEvent;
use Yormy\FootprintsLaravel\Observers\Events\ExceptionEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelCreatedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelDeletedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelUpdatedEvent;
use Yormy\FootprintsLaravel\Observers\Events\RequestTerminatedEvent;
use Yormy\FootprintsLaravel\Observers\Listeners\Auth\FailedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Auth\LockoutListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Auth\LoginListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Auth\LogoutListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Auth\OtherDeviceLogoutListener;
use Yormy\FootprintsLaravel\Observers\Listeners\CustomListener;
use Yormy\FootprintsLaravel\Observers\Listeners\ExceptionListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelCreatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelDeletedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelUpdatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\OtherListener;
use Yormy\FootprintsLaravel\Observers\Listeners\RequestTerminatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\RouteMatchListener;

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

        CustomFootprintEvent::class => [
            CustomListener::class,
        ],

        ExceptionEvent::class => [
            ExceptionListener::class,
        ],
    ];

    /**
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress MixedAssignment
     */
    public function __construct(Application $app)
    {
        $otherEvents = (array) config('footprints.log_events.other_events');

        foreach (array_keys($otherEvents) as $event) {
            $this->listen[$event] = [OtherListener::class];
        }

        parent::__construct($app);
    }
}
