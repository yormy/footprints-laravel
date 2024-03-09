<?php

namespace Yormy\FootprintsLaravel;

use Illuminate\Support\Facades\Facade;

class FootprintsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Footprints::class;
    }
}
