<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;
use Yormy\FootprintsLaravel\Exceptions\CacheTagSupportException;
use Yormy\FootprintsLaravel\Models\Log;

class LogItemRepository
{
    public function __construct(private ?Log $model = null)
    {
        if (! $model) {
            $this->model = new Log();
        }
    }

    public function getAllLoginForUser(Admin|Member $user): Collection
    {
        return $this->queryForUser($user)
            ->select([
                'xid',
                'log_type',
                'ip_address',
                'user_agent',
                'location',
                'created_at',
            ])
            ->whereIn('log_type', ['AUTH_LOGIN', 'AUTH_FAILED'])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getAllActivityForUser(Admin|Member $user): Collection
    {
        $select = [
            'xid',
            'log_type',
            'ip_address',
            'user_agent',
            'browser_fingerprint',
            'location',
            'created_at',
            'data',
            'impersonator_id',
            'route',
            'url',
            'method',
        ];

        return $this->queryForUser($user)
            ->select($select)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function createLogEntry(?Authenticatable $user, Request $request, array $props): void
    {
        $userFields = $this->getUserData($user);

        $requestFields = [];
        $requestFields['request_id'] = (string) $request->get('request_id');

        $payload = (string) $request->getContent();
        $payload = $this->cleanPayload($payload);

        $props['payload_base64'] = $payload;

        $remoteFields = $this->getRemoteDetails($request);
        $data = array_merge($props, $userFields, $requestFields, $remoteFields);

        $data['browser_fingerprint'] = $request->cookie('session_id');

        $data['impersonator_id'] = $request->get('impersonator_id');

        $logModel = $this->getLogItemModel();
        $logModel->create($data);
    }

    public function updateLogEntry(string $requestId, float $duration, string $response): void
    {
        $response = $this->cleanResponse($response);

        $logModel = $this->getLogItemModel();
        $table = $logModel->getTable();

        $userUpdate = $this->getUserUpdateStatement($table);

        $statement = "UPDATE {$table}
            SET {$table}.request_duration_sec = {$duration},
                {$table}.response_base64 = '{$response}'
                {$userUpdate}
            WHERE {$table}.request_id = '{$requestId}'";

        DB::statement($statement);
    }

    private function queryForUser(Admin|Member $user): Builder
    {
        $userType = '';
        if ($user instanceof Member) {
            $userType = '%Member';
        }

        if ($user instanceof Admin) {
            $userType = '%Admin';
        }

        return $this->model::where('user_id', $user->id)
            ->where('user_type', 'like', $userType);
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress MixedAssignment
     */
    private function getLogItemModel(): Model
    {
        $logModelClass = config('footprints.log_model');

        return new $logModelClass();
    }

    /**
     * @psalm-suppress UndefinedFunction
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedMethodCall
     */
    private function getRemoteDetails(Request $request): array
    {
        $data = [];

        if (config('footprints.content.ip')) {
            $data['ip_address'] = $request->ip();
        }

        if (config('footprints.content.user_agent')) {
            $data['user_agent'] = $request->userAgent();
        }

        if (config('footprints.content.geoip')) {
            $supportsTags = cache()->supportsTags();
            if (! $supportsTags) {
                throw new CacheTagSupportException();
            }

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
                'user_type' => $user::class,
            ];
        }

        return $userFields;
    }

    private static function cleanPayload(string $payload): string
    {
        if (! config('footprints.content.payload.enabled')) {
            return '';
        }

        $truncated = substr($payload, 0, (int) config('footprints.payload.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
    }

    private static function cleanResponse(string $response): string
    {
        if (! config('footprints.content.response.enabled')) {
            return '';
        }

        $truncated = substr($response, 0, (int) config('footprints.response.max_characters'));

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
            $userType = addslashes($user::class);

            $userUpdate = ",{$table}.user_id = {$userId},
                {$table}.user_type = '{$userType}'";
        }

        return $userUpdate;
    }
}
