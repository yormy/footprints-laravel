<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;


use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Events\Routing;

use Yormy\LaravelFootsteps\Observers\Events\RequestTerminatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;

class RequestTerminatedListener
{
    use LoggingTrait;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(RequestTerminatedEvent $event)
    {
        $request = $event->getRequest();

        $requestId = $request->get('request_id');
        $requestStart = $request->get('request_start');

        $duration = null;
        if ($requestStart > 0) {
            $duration = round(microtime(true) - $requestStart,3);
        }

        $payload = $event->getResponse()->getContent();
        $payload = $this->cleanPayload($payload);

        $logModelClass = config('footsteps.log_model');
        $logModel = new $logModelClass;
        $table = $logModel->getTable();

        $userUpdate = $this->getUserUpdateStatement($table);

        $statement = "UPDATE $table
            SET {$table}.request_duration_sec = $duration,
                {$table}.payload_base64 = '$payload'
                $userUpdate
            WHERE {$table}.request_id = '$requestId'";

        DB::statement($statement);
    }

    private function getUserUpdateStatement(string $table)
    {
        $userUpdate = '';
        $user = auth()->user();
        if ($user) {
            $userId = $user->id;
            $userType = addslashes(get_class($user));

            $userUpdate = ",{$table}.user_id = $userId,
                {$table}.user_type = '$userType'";
        }

        return $userUpdate;
    }
}
