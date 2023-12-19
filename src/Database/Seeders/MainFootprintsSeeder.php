<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Database\Seeders;

use Illuminate\Database\Seeder;

class MainFootprintsSeeder extends Seeder
{
    public function run(): void
    {
        (new FootprintSeeder)->run();
    }
}
