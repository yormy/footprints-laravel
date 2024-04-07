<?php

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;
use Yormy\FootprintsLaravel\Models\Log;

class LogSeeder extends Seeder
{
    public function run()
    {
        $this->memberLogSeeder();
        $this->adminLogSeeder();
    }

    private function memberLogSeeder()
    {
        $member = Member::where('id', 1)->first();
        Log::factory(4)->loginFailed()->forMember($member)->create();
        Log::factory(3)->loginSuccess()->forMember($member)->create();

        $member = Member::where('id', 2)->first();
        Log::factory(4)->loginFailed()->forMember($member)->create();
        Log::factory(3)->loginSuccess()->forMember($member)->create();
    }

    private function adminLogSeeder()
    {
        $admin = Member::where('id', 1)->first();
        Log::factory(4)->loginFailed()->forAdmin($admin)->create();
        Log::factory(3)->loginSuccess()->forAdmin($admin)->create();

        $admin = Member::where('id', 2)->first();
        Log::factory(4)->loginFailed()->forAdmin($admin)->create();
        Log::factory(3)->loginSuccess()->forAdmin($admin)->create();
    }
}
