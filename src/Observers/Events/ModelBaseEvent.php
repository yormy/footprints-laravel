<?php

namespace Yormy\LaravelFootsteps\Observers\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class ModelBaseEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(private Model $model, protected ?Authenticatable $user, protected Request $request)
    {
        //
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

}