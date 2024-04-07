<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Events\ExceptionEvent;
use Yormy\FootprintsLaravel\Services\RuleService;

class ExceptionListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ExceptionEvent $event)
    {
        $exception = $event->getException();

        if (! $this->shouldLog($exception)) {
            return;
        }

        $request = $event->getRequest();

        $this->logItemRepository->createLogEntry(
            Auth::user(),
            $request,
            [
                'route' => '',
                'url' => $request->fullUrl(),
                'log_type' => $this->getLogType($exception),
                'data' => json_encode($event),
            ]);
    }

    private function getLogType(Throwable $exception): string
    {
        $logExceptions = (array) config('footprints.log_exceptions.exceptions');
        $exceptionClass = get_class($exception);

        if (array_key_exists($exceptionClass, $logExceptions)) {
            return (string) $logExceptions[$exceptionClass];
        }

        return LogType::EXCEPTION_UNSPECIFIED->value;
    }

    private function shouldLog(Throwable $exception): bool
    {
        if (App::runningInConsole()) {
            return false;
        }

        if (! config('footprints.enabled')) {
            return false;
        }

        if (! config('footprints.log_exceptions.enabled')) {
            return false;
        }

        $exceptionClass = get_class($exception);
        if (RuleService::shouldInclude($exceptionClass, (array) config('footprints.log_exceptions.exceptions'))) {
            return false;
        }

        return true;
    }
}
