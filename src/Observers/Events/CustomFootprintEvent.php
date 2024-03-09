<?php

declare(strict_types=1);

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
        protected string $logType,
        protected array $data = [],
    ) {
    }

    public function getLogType(): string
    {
        return $this->logType;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
