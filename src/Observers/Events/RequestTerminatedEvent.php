<?php

namespace Yormy\FootprintsLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class RequestTerminatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(protected Request $request, protected string $response)
    {
        //
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
