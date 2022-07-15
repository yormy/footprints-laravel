<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Support\Facades\Auth;
use Throwable;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Events\ExceptionEvent;
use Yormy\LaravelFootsteps\Services\RuleService;

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
        $logExceptions = config('footsteps.log_exceptions.exceptions');
        $exceptionClass = get_class($exception);

        if (array_key_exists($exceptionClass, $logExceptions)) {
            return $logExceptions[$exceptionClass];
        }
        return LogType::EXCEPTION_UNSPECIFIED->value;
    }

    private function shouldLog(Throwable $exception): bool
    {
        if (! config('footsteps.enabled') ) {
            return false;
        }

        if (! config('footsteps.log_exceptions.enabled') ) {
            return false;
        }

        $exceptionClass = get_class($exception);
        if (RuleService::shouldInclude($exceptionClass, config('footsteps.log_exceptions.exceptions'))) {
            return false;
        }

        return true;
    }
}
