<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Members;

use Illuminate\Database\Eloquent\Model;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;

class MemberLoginHistoryController extends LoginHistoryController
{
    protected function getUser($member_xid): Model
    {
        // @phpstan-ignore-next-line
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        return $userResolver->getMember('xid', $member_xid);
    }
}
