<?php

namespace Yormy\LaravelFootsteps\Database\Seeders;


use Illuminate\Database\Seeder;
use Mexion\BedrockUsersv2\Domain\Standby\Models\StandbyReason;
use Mexion\BedrockUsersv2\Domain\User\Models\PersonalAccessToken;
use Mexion\TestappCore\Domain\Billing\Database\Seeders\BillingMainSeeder;
use Yormy\ChaskiLaravel\Domain\Shared\Models\NotificationSent;
use Yormy\LaravelFootsteps\Models\Log;

class LogSeeder extends Seeder
{
    public function run()
    {
        Log::factory(4)->loginFailed()->create();
        Log::factory(3)->loginSuccess()->create();

        Log::factory(4)->loginFailed()->forAdmin()->create();
        Log::factory(3)->loginSuccess()->forAdmin()->create();
    }
}
