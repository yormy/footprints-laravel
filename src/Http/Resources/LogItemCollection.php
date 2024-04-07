<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LogItemCollection extends ResourceCollection
{
    public $collects = LogItemResource::class;
}
