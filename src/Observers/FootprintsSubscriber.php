<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Events\Dispatcher;
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
use Yormy\FootprintsLaravel\Observers\Listeners\RequestTerminatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\RouteMatchListener;

class FootprintsSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $this->authEvents($events);
        $this->modelEvents($events);
        $this->routeEvents($events);
        $this->otherEvents($events);

        $events->listen(
            CustomFootprintEvent::class,
            CustomListener::class
        );

        $events->listen(
            RequestTerminatedEvent::class,
            RequestTerminatedListener::class,
        );

        $events->listen(
            ExceptionEvent::class,
            ExceptionListener::class,
        );
    }

    private function otherEvents(Dispatcher $events)
    {
        $otherEvents = (array) config('footprints.log_events.other_events');

        foreach (array_keys($otherEvents) as $event) {
            $events->listen(
                $event,
                OtherListener::class
            );
        }
    }

    private function authEvents(Dispatcher $events)
    {
        $events->listen(
            Login::class,
            LoginListener::class
        );

        $events->listen(
            Logout::class,
            LogoutListener::class
        );

        $events->listen(
            Failed::class,
            FailedListener::class,
        );

        $events->listen(
            Lockout::class,
            LockoutListener::class,
        );

        $events->listen(
            OtherDeviceLogout::class,
            OtherDeviceLogoutListener::class,
        );
    }

    private function modelEvents(Dispatcher $events)
    {
        $events->listen(
            ModelCreatedEvent::class,
            ModelCreatedListener::class,
        );

        $events->listen(
            ModelUpdatedEvent::class,
            ModelUpdatedListener::class
        );

        $events->listen(
            ModelDeletedEvent::class,
            ModelDeletedListener::class,
        );
    }

    private function routeEvents(Dispatcher $events)
    {
        $events->listen(
            RouteMatched::class,
            RouteMatchListener::class
        );
    }
}
