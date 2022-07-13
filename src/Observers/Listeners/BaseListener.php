<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Http\Request;
use Yormy\LaravelFootsteps\Repositories\LogItemRepository;

class BaseListener
{
    public function __construct(
        protected Request $request,
        protected LogItemRepository $logItemRepository
    ) {
        //
    }
}
