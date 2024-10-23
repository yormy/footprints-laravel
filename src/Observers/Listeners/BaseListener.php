<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Http\Request;

class BaseListener
{
    public function __construct(
        protected Request $request
    ) {}
}
