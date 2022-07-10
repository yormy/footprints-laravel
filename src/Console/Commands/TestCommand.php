<?php

namespace Yormy\LaravelFootsteps\Console\Commands;

use Illuminate\Console\Command;
use Yormy\LaravelFootsteps\Services\FootstepsService;
use App\Core\Models\Customer;
use App\Pripost\Customer\Models\Postbox;
use Mexion\BountyCore\Models\Concerns\Concern;
use Mexion\BountyCore\Models\Concerns\Url;

/** @psalm-suppress PropertyNotSetInConstructor */
class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'footsteps:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates cve';

    /**
     * @psalm-suppress UnusedVariable
     *
     * @return void
     */
    public function handle(): void
    {
//        $customer = Customer::find(1);
//        $postbox = Postbox::find(1);
//        $postbox2 = Postbox::find(2);

//        $main = Concern::first();
//        $relation = Url::find(1);
//        $newRelation = Url::find(2);
//
//
//        FootstepsService::recordCreated($main, $relation, $newRelation,  'dddd');
    }

}
