<?php

namespace Yormy\LaravelFootsteps\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogItemRepository
{
    public function createLogEntry(?Model $user, $request, array $props)
    {
        $userFields = $this->getUserData($user);

        $requestFields['request_id'] = $request->get('request_id');
        $requestFields['request_start'] = $request->get('request_start');

        $payload = $request->getContent();
        $payload = $this->cleanPayload($payload);

        $props['payload_base64'] = $payload;

        $remoteFields = $this->getRemoteDetails($request);
        $data =  array_merge($props, $userFields, $requestFields, $remoteFields);

        $logModel = $this->getLogItemModel();
        $logModel->create($data);
    }

    public function updateLogEntry(string $requestId, float $duration, string $payload)
    {
        $payload = $this->cleanPayload($payload);

        $logModel = $this->getLogItemModel();
        $table = $logModel->getTable();

        $userUpdate = $this->getUserUpdateStatement($table);

        $statement = "UPDATE $table
            SET {$table}.request_duration_sec = $duration,
                {$table}.response_base64 = '$payload'
                $userUpdate
            WHERE {$table}.request_id = '$requestId'";

        DB::statement($statement);
    }

    private function getLogItemModel(): Model
    {
        $logModelClass = config('footsteps.log_model');
        return new $logModelClass;
    }

    private function getRemoteDetails($request): array
    {
        $data['ip'] = $request->ip();
        $data['user_agent'] = $request->userAgent();

        if (config('footsteps.log_geoip')) {
            $location = geoip()->getLocation($request->ip());
            $data['location'] = json_encode($location->toArray());
        }

        return $data;
    }

    private function getUserData(?Model $user): array
    {
        $userFields = [];
        if ($user) {
            $userFields = [
                'user_id' => $user->id,
                'user_type' => get_class($user),
            ];
        }

        return $userFields;
    }

    private static function cleanPayload(string $payload)
    {
        if (!config('footsteps.log_response.enabled')) {
            return '';
        }

        $truncated = substr($payload, 0,config('footsteps.log_response.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
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
