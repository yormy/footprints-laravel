<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class ModelBaseEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(private Model $model, protected ?Authenticatable $user, protected Request $request) {}

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
