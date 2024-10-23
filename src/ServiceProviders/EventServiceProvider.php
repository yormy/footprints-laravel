<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Yormy\FootprintsLaravel\Observers\FootprintsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        FootprintsSubscriber::class,
    ];
}
