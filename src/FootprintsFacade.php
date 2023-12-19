<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel;

use Illuminate\Support\Facades\Facade;

class FootprintsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Footprints::class;
    }
}
