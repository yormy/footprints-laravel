<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Yormy\FootprintsLaravel\Tests\Stubs\Models\Admin;
use Yormy\FootprintsLaravel\Tests\Stubs\Models\Member;

class UserResolver
{
    public static function getCurrent(): Admin|Member|null
    {
        /** @var Member $user */
        $user = Auth::guard('customer')->user();
        if (! $user) {
            /** @var Admin $user */
            $user = Auth::guard('admin')->user();
        }

        return $user;
    }

    public static function getMember(string $field, string $xid): ?Member
    {
        /** @var Member $user */
        $user = Member::where($field, $xid)->firstOrFail();

        return $user;
    }

    public static function getAdmin(string $field, string $xid): ?Admin
    {
        /** @var Admin $user */
        $user = Admin::where($field, $xid)->firstOrFail();

        return $user;
    }
}
