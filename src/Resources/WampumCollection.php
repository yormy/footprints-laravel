<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FootstepsCollection extends ResourceCollection
{
    public $collects = FootstepsResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
