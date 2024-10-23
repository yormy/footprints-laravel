<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Illuminate\Support\Facades\App;
use Throwable;
use Yormy\FootprintsLaravel\DataObjects\RequestDto;
use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Jobs\FootprintsLogJob;
use Yormy\FootprintsLaravel\Observers\Events\ExceptionEvent;
use Yormy\FootprintsLaravel\Services\RuleService;

class ExceptionListener extends BaseListener
{
    public function handle(ExceptionEvent $event): void
    {
        $exception = $event->getException();

        if (! $this->shouldLog($exception)) {
            return;
        }

        $request = $event->getRequest();
        $requestDto = RequestDto::fromRequest($request);

        $logtype = $this->getLogType($exception);

        if (! $logtype) {
            return;
        }

        $props = [
            'log_type' => $this->getLogType($exception),
        ];

        FootprintsLogJob::dispatch($requestDto->toArray(), $props);
    }

    private function getLogType(Throwable $exception): ?string
    {
        $logExceptions = (array) config('footprints.log_exceptions.exceptions');
        $exceptionClass = $exception::class;

        if (array_key_exists($exceptionClass, $logExceptions)) {
            return (string) $logExceptions[$exceptionClass];
        }

        //return LogType::EXCEPTION_UNSPECIFIED->value; // only log specified exceptions
        return null;
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

        $exceptionClass = $exception::class;
        if (RuleService::shouldInclude($exceptionClass, (array) config('footprints.log_exceptions.exceptions'))) {
            return false;
        }

        return true;
    }
}
