<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Yormy\LaravelFootsteps\Services\Resolvers\UserResolver;

class BaseController
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = UserResolver::getCurrent();
    }
}
