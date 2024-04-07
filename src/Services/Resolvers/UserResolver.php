<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;

class UserResolver
{
    public static function getCurrent(): Admin|Member|null
    {
        /**
         * @var User $user
         */
        return Auth::user();
    }

    public static function getMemberOnXId(string $xid): Member
    {
        return Member::where('xid', $xid)->firstOrFail();
    }

    public static function getAdminOnXId(string $xid): Admin
    {
        return Admin::where('xid', $xid)->firstOrFail();
    }
}
