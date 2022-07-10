<?php

namespace Yormy\LaravelFootsteps;

use Illuminate\Support\Facades\Facade;

class FootstepsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Footsteps::class;
    }
}
