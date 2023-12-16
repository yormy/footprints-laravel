<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Services\Resolvers;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;

class UserResolver
{
    public static function getCurrent() : Admin | Member | null
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        return $user;
    }

    public static function getMemberOnXId(string $xid): ?Member
    {
        return Member::where('xid', $xid)->first();
    }
}
