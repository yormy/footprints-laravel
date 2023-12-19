<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomFootprintEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        protected string $logType,
        protected array $data = [],
    ) {}

    public function getType(): string
    {
        return $this->logType;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
