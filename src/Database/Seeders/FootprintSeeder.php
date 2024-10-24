<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
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
        $memberClass = config('footprints.models.member');

        /** @var Model $memberModel */
        $memberModel = new $memberClass;

        $member = $memberModel->where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();

        $member = $memberModel->where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();
    }

    private function adminLogSeeder(): void
    {
        $adminClass = config('footprints.models.admin');

        /** @var Model $adminModel */
        $adminModel = new $adminClass;

        $admin = $adminModel->where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();

        $admin = $adminModel->where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();
    }
}
