<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Admins;

use Illuminate\Contracts\Auth\Authenticatable;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;
use Yormy\FootprintsLaravel\Services\Resolvers\UserResolver;

class AdminLoginHistoryController extends LoginHistoryController
{
    protected function getUser($member_xid): ?Authenticatable
    {
        $userResolverClass = config('footprints.resolvers.user');

        /** @var UserResolver $userResolver */
        $userResolver = new $userResolverClass;

        return $userResolver->getAdmin('xid', $member_xid);
    }
}
