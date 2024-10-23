<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Members;

use Illuminate\Contracts\Auth\Authenticatable;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;
use Yormy\FootprintsLaravel\Services\Resolvers\UserResolver;

class MemberLoginHistoryController extends LoginHistoryController
{
    protected function getUser($member_xid): ?Authenticatable
    {
        // @phpstan-ignore-next-line
        $userResolverClass = config('footprints.resolvers.user');

        /** @var UserResolver $userResolver */
        $userResolver = new $userResolverClass;

        return $userResolver->getMember('xid', $member_xid);
    }
}
