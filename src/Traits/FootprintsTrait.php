<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Traits;

use Illuminate\Support\Facades\Auth;
use Yormy\FootprintsLaravel\Observers\Events\ModelCreatedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelDeletedEvent;
use Yormy\FootprintsLaravel\Observers\Events\ModelUpdatedEvent;

trait FootprintsTrait
{
    public function getFootprintsFields(): array
    {
        return ['*'];
    }

    public static function getFootprintsEvents(): array
    {
        return ['CREATED', 'UPDATED', 'DELETED'];
    }

    public static function bootFootprintsTrait(): void
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        self::created(function ($model) use ($userResolver): void {
            if (in_array('CREATED', self::getFootprintsEvents())) {
                event(new ModelCreatedEvent($model, $userResolver->getCurrent(), request()));
            }
        });

        static::updated(function ($model) use ($userResolver): void {
            if (in_array('UPDATED', self::getFootprintsEvents())) {
                event(new ModelUpdatedEvent($model, $userResolver->getCurrent(), request()));
            }
        });

        static::deleted(function ($model) use ($userResolver): void {
            if (in_array('DELETED', self::getFootprintsEvents())) {
                event(new ModelDeletedEvent($model, $userResolver->getCurrent(), request()));
            }
        });
    }
}
