<?php

namespace Yormy\LaravelFootsteps\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogItemRepository
{
    public function createLogEntry(?Authenticatable $user, Request $request, array $props): void
    {
        $userFields = $this->getUserData($user);

        $requestFields = [];
        $requestFields['request_id'] = (string)$request->get('request_id');
        $requestFields['request_start'] = (float)$request->get('request_start');

        $payload = (string)$request->getContent();
        $payload = $this->cleanPayload($payload);

        $props['payload_base64'] = $payload;

        $remoteFields = $this->getRemoteDetails($request);
        $data = array_merge($props, $userFields, $requestFields, $remoteFields);

        $logModel = $this->getLogItemModel();
        $logModel->create($data);
    }

    public function updateLogEntry(string $requestId, float $duration, string $response): void
    {
        $response = $this->cleanResponse($response);

        $logModel = $this->getLogItemModel();
        $table = $logModel->getTable();

        $userUpdate = $this->getUserUpdateStatement($table);

        $statement = "UPDATE $table
            SET {$table}.request_duration_sec = $duration,
                {$table}.response_base64 = '$response'
                $userUpdate
            WHERE {$table}.request_id = '$requestId'";

        DB::statement($statement);
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress MixedAssignment
     */
    private function getLogItemModel(): Model
    {
        $logModelClass = config('footsteps.log_model');

        return new $logModelClass;
    }

    /**
     * @psalm-suppress UndefinedFunction
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedMethodCall
     */
    private function getRemoteDetails(Request $request): array
    {
        $data = [];
        $data['ip'] = $request->ip();
        $data['user_agent'] = $request->userAgent();

        if (config('footsteps.log_geoip')) {
            $location = geoip()->getLocation($request->ip());
            $data['location'] = json_encode($location->toArray());
        }

        return $data;
    }

    /**
     * @psalm-suppress NoInterfaceProperties
     */
    private function getUserData(?Authenticatable $user): array
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

    private static function cleanPayload(string $payload): string
    {
        if (! config('footsteps.content.payload.enabled')) {
            return '';
        }

        $truncated = substr($payload, 0, (int)config('footsteps.payload.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
    }


    private static function cleanResponse(string $response): string
    {
        if (! config('footsteps.content.response.enabled')) {
            return '';
        }

        $truncated = substr($response, 0, (int)config('footsteps.response.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
    }

    /**
     * @psalm-suppress NoInterfaceProperties
     */
    private function getUserUpdateStatement(string $table): string
    {
        $userUpdate = '';
        $user = Auth::user();
        if ($user) {

            /** @var int $userId */
            $userId = $user->id;
            $userType = addslashes(get_class($user));

            $userUpdate = ",{$table}.user_id = $userId,
                {$table}.user_type = '$userType'";
        }

        return $userUpdate;
    }
}
