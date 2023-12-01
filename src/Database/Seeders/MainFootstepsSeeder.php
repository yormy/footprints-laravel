<?php

namespace Yormy\LaravelFootsteps\Database\Seeders;


use Illuminate\Database\Seeder;


class MainFootstepsSeeder extends Seeder
{
    public function run()
    {
        (new LogSeeder())->run();
    }
}
