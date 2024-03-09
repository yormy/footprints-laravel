<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\ServiceProviders;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Yormy\FootprintsLaravel\Observers\FootprintsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        FootprintsSubscriber::class,
    ];
}
