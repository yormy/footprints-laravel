<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Services\Resolvers\UserResolver;

class BaseController
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = UserResolver::getCurrent();
    }
}
