<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Repositories\LogItemRepository;

class BaseListener
{
    public function __construct(
        protected Request $request,
        protected LogItemRepository $logItemRepository
    ) {
        //
    }
}
