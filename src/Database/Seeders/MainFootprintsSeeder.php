<?php

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Database\Seeder;

class MainFootprintsSeeder extends Seeder
{
    public function run()
    {
        (new LogSeeder())->run();
    }
}
