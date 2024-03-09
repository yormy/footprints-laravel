<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class BaseController
{
    protected $user;

    public function __construct(Request $request)
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        $this->user = $userResolver->getCurrent();
    }
}
