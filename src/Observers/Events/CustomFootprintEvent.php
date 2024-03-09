<?php

namespace Yormy\FootprintsLaravel\Observers\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class CustomFootprintEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        protected ?Authenticatable $user,
        protected Request $request,
        protected string $logType,
        protected array $data = [],
        protected string $tableName = ''
    ) {
        //
    }

    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getLogType(): string
    {
        return $this->logType;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
