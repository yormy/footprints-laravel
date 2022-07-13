<?php

namespace Yormy\LaravelFootsteps\ServiceProviders;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;

use Illuminate\Routing\Events\Routing;
use Yormy\LaravelFootsteps\Observers\Events\CustomFootstepEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\CustomListener;
use Yormy\LaravelFootsteps\Observers\Listeners\ModelCreatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\ModelDeletedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\ModelUpdatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\RequestTerminatedListener;
use Yormy\LaravelFootsteps\Observers\Listeners\LockoutListener;
use Yormy\LaravelFootsteps\Observers\Listeners\LoginListener;
use Yormy\LaravelFootsteps\Observers\Listeners\LogoutListener;
use Yormy\LaravelFootsteps\Observers\Listeners\RouteMatchListener;
use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class   => [
            LoginListener::class
        ],

        Logout::class   => [
            LogoutListener::class
        ],

        Lockout::class => [
            LockoutListener::class
        ],

        RouteMatched::class => [
            RouteMatchListener::class
        ],

        RequestTerminatedEvent::class => [
            RequestTerminatedListener::class
        ],

        ModelCreatedEvent::class => [
            ModelCreatedListener::class
        ],

        ModelUpdatedEvent::class => [
            ModelUpdatedListener::class
        ],

        ModelDeletedEvent::class => [
            ModelDeletedListener::class
        ],

        CustomFootstepEvent::class => [
            CustomListener::class
        ]
    ];
}
