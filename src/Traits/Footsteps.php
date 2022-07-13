<?php

namespace Yormy\LaravelFootsteps\Traits;

use Illuminate\Support\Facades\Auth;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;

trait Footsteps
{
    public static function bootFootsteps(): void
    {
        self::created(function ($model) {
            event(new ModelCreatedEvent($model, Auth::user(), request()));
        });

        static::updated(function ($model) {
            event(new ModelUpdatedEvent($model, Auth::user(), request()));
        });

        static::deleted(function ($model) {
            event(new ModelDeletedEvent($model, Auth::user(), request()));
        });
    }
}
