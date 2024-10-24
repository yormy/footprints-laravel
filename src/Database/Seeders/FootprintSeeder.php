<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;
use Yormy\FootprintsLaravel\Models\Footprint;

class FootprintSeeder extends Seeder
{
    public function run(): void
    {
        $this->memberLogSeeder();
        $this->adminLogSeeder();
    }

    private function memberLogSeeder(): void
    {
        $member = Member::where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();

        $member = Member::where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();
    }

    private function adminLogSeeder(): void
    {
        $admin = Member::where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();

        $admin = Member::where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();
    }
}
