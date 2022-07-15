<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Illuminate\Support\Facades\Auth;
use Throwable;
use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Events\ExceptionEvent;
use Yormy\LaravelFootsteps\Services\RequestParser;
use Yormy\LaravelFootsteps\Services\RuleService;

class ExceptionListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ExceptionEvent $event)
    {
        $exception = $event->getException();

        $request = $event->getRequest();

        if (! $this->shouldLog($exception))
        {
            return;
        }

        $this->logItemRepository->createLogEntry(
            Auth::user(),
            $this->request,
            [
                'route' => '',
                'url' => $request->fullUrl(),
                'log_type' => LogType::EXCEPTION_NOT_FOUND,
                'data' => json_encode($event),
            ]);
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
