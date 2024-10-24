<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Yormy\FootprintsLaravel\Repositories\FootprintItemRepository;

class FootprintsLogJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected FootprintItemRepository $logItemRepository;

    public function __construct(protected array $data, protected array $props)
    {
        $this->logItemRepository = new FootprintItemRepository;
    }

    public function handle(): void
    {
        $this->logItemRepository->createLogEntry(
            $this->data,
            $this->props,
        );
    }
}