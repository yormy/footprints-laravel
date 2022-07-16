<?php

namespace Yormy\LaravelFootsteps\Traits;

use Illuminate\Support\Facades\Auth;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;

trait Footsteps
{
    public function getFootstepsFields(): array
    {
        return ['*'];
    }

    public static function getFootstepsEvents(): array
    {
        return ['CREATED','UPDATED','DELETED'];
    }

    public static function bootFootsteps(): void
    {
        self::created(function ($model) {
            if (in_array('CREATED', self::getFootstepsEvents())) {
                event(new ModelCreatedEvent($model, Auth::user(), request()));
            }
        });

        static::updated(function ($model) {
            if (in_array('UPDATED', self::getFootstepsEvents())) {
                event(new ModelUpdatedEvent($model, Auth::user(), request()));
            }
        });

        static::deleted(function ($model) {
            if (in_array('DELETED', self::getFootstepsEvents())) {
                event(new ModelDeletedEvent($model, Auth::user(), request()));
            }
        });
    }
}
