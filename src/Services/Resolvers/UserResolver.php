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
        $user = Auth::guard('customer')->user();
        if (!$user) {
            $user = Auth::guard('admin')->user();
        }

        return $user;
    }

    public static function getMember(string $field, string $xid): Member
    {
        return Member::where($field, $xid)->firstOrFail();
    }

    public static function getAdmin(string $field, string $xid): Admin
    {
        return Admin::where($field, $xid)->firstOrFail();
    }
}
