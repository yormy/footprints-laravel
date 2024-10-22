<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Traits;

use Illuminate\Support\Facades\Auth;
use Yormy\FootprintsLaravel\Observers\Events\ModelCreatedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelDeletedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelUpdatedEvent;

trait Footprints
{
    public function getFootprintsFields(): array
    {
        return ['*'];
    }

    public static function getFootprintsEvents(): array
    {
        return ['CREATED', 'UPDATED', 'DELETED'];
    }

    public static function bootFootprints(): void
    {
        self::created(function ($model): void {
            if (in_array('CREATED', self::getFootprintsEvents())) {
                event(new ModelCreatedEvent($model, Auth::user(), request()));
            }
        });

        static::updated(function ($model): void {
            if (in_array('UPDATED', self::getFootprintsEvents())) {
                event(new ModelUpdatedEvent($model, Auth::user(), request()));
            }
        });

        static::deleted(function ($model): void {
            if (in_array('DELETED', self::getFootprintsEvents())) {
                event(new ModelDeletedEvent($model, Auth::user(), request()));
            }
        });
    }
}
