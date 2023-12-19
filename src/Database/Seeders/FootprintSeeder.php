<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Yormy\FootprintsLaravel\Models\Footprint;

class FootprintSeeder extends Seeder
{
    private $memberModel; // @phpstan-ignore-line

    public function __construct()
    {
        $memberModelClass = config('footprint.models.footprint');
        $this->memberModel = new $memberModelClass;
    }

    public function run(): void
    {
        $this->memberLogSeeder();
        $this->adminLogSeeder();
    }

    private function memberLogSeeder(): void
    {
        $member = $this->memberModel->where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();

        $member = $this->memberModel->where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forMember($member)->create();
        Footprint::factory(3)->loginSuccess()->forMember($member)->create();
    }

    private function adminLogSeeder(): void
    {
        $admin = $this->memberModel->where('id', 1)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();

        $admin = $this->memberModel->where('id', 2)->first();
        Footprint::factory(4)->loginFailed()->forAdmin($admin)->create();
        Footprint::factory(3)->loginSuccess()->forAdmin($admin)->create();
    }
}
