<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Admins;

use Illuminate\Database\Eloquent\Model;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;

class AdminLoginHistoryController extends LoginHistoryController
{
    protected function getUser($member_xid): Model
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        return $userResolver->getAdmin('xid', $member_xid);
    }
}
