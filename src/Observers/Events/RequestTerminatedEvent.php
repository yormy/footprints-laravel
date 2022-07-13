<?php

namespace Yormy\LaravelFootsteps\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Client\Request;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Http\Response;

class RequestTerminatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(protected Request $request, protected Response $response)
    {
        //
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
