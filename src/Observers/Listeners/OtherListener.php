<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Repositories\LogItemRepository;

class OtherListener
{
    public function __construct(protected LogItemRepository $logItemRepository, protected Request $request)
    {
        //
    }

    /**
     * @psalm-suppress MissingParamType
     * @return void
     */
    public function handle($event)
    {
        if (! config('footprints.enabled')) {
            return;
        }

        $this->logItemRepository->createLogEntry(
            Auth::user(),
            $this->request,
            [
                'route' => '',
                'url' => substr($this->request->fullUrl(), 0, 150),
                'log_type' => $this->getLogType($event),
                'data' => json_encode($event),
            ]);
    }

    /**
     * @psalm-suppress MissingParamType
     * @psalm-suppress MixedArgument
     */
    private function getLogType($event): string
    {
        $logEvents = (array) config('footprints.log_events.other_events');

        $eventClass = get_class($event);

        if (array_key_exists($eventClass, $logEvents)) {
            return (string) $logEvents[$eventClass];
        }

        return LogType::UNKNOWN->value;
    }
}
