<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
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
use Yormy\FootprintsLaravel\Observers\Listeners\Http\RequestHandledListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelCreatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelDeletedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\Model\ModelUpdatedListener;
use Yormy\FootprintsLaravel\Observers\Listeners\RequestTerminatedListener;

class FootprintsSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $this->httpEvents($events);

        $this->authEvents($events);
        $this->modelEvents($events);

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

    private function httpEvents(Dispatcher $events): void
    {
        $events->listen(
            RequestHandled::class,
            RequestHandledListener::class
        );
    }

    private function authEvents(Dispatcher $events): void
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

    private function modelEvents(Dispatcher $events): void
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
}
