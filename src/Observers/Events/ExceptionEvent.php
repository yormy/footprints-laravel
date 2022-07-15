<?php

namespace Yormy\LaravelFootsteps\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExceptionEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(private Throwable $exception, private Request $request)
    {
        //
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
