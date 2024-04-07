<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Members;

use Illuminate\Database\Eloquent\Model;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;
use Yormy\FootprintsLaravel\Services\Resolvers\UserResolver;

class MemberLoginHistoryController extends LoginHistoryController
{
    protected function getUser($member_xid): Model
    {
        return UserResolver::getMemberOnXId($member_xid);
    }
}
