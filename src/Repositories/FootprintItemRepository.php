<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yormy\FootprintsLaravel\Exceptions\CacheTagSupportException;
use Yormy\FootprintsLaravel\Models\Footprint;

class FootprintItemRepository
{
    private Footprint $model;

    public function __construct(?Footprint $model = null)
    {
        if (! $model) {
            $this->model = new Footprint;
        }
    }

    public function getAllLoginForUser(Authenticatable $user): Collection
    {
        /** @var Collection $results */
        $results = $this->queryForUser($user)
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

        return $results;
    }

    public function getAllActivityForUser(Authenticatable $user): Collection
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

        /** @var Collection $results */
        $results = $this->queryForUser($user)
            ->select($select)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $results;
    }

    public function createLogEntry(array $data, array $props): void
    {
        $data = array_merge($data, $props);

        /** @var Footprint $logModel */
        $logModel = $this->getLogItemModel();

        $sessionId = $data['session_id'];

        /** @var Footprint $previous */
        $previous = $logModel->where('session_id', $sessionId)->latest()->first();

        /** @var Footprint $new */
        $new = $logModel->create($data);

        if ($new && $previous) {
            $pageVisitSec = $new->created_at->diffInSeconds($previous->created_at);
            $previous->page_visit_sec = $pageVisitSec;
            $previous->update();
        }
    }

    public function updateLogEntry(string $requestId, float $duration, string $response): void
    {
        $response = $this->cleanResponse($response);

        $logModel = $this->getLogItemModel();
        $table = $logModel->getTable();

        $statement = "UPDATE {$table}
            SET {$table}.request_duration_sec = {$duration},
                {$table}.response = '{$response}'
            WHERE {$table}.request_id = '{$requestId}'";

        DB::statement($statement);
    }

    private function queryForUser(Authenticatable $user): Builder
    {
        $userType = '';
        /** @phpstan-ignore-next-line  */
        if ($user instanceof Member) {
            $userType = '%Member';
        }

        /** @phpstan-ignore-next-line  */
        if ($user instanceof Admin) {
            $userType = '%Admin';
        }

        // @phpstan-ignore-next-line
        $userId = $user->id;

        return $this->model::where('user_id', $userId)
            ->where('user_type', 'like', $userType);
    }

    private function getLogItemModel(): Footprint
    {
        $logModelClass = config('footprints.models.footprint');

        /** @var Footprint $model */
        $model = new $logModelClass;

        return $model;
    }

    /**
     * @psalm-suppress UndefinedFunction
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedMethodCall
     */
    //    private function getRemoteDetails(Request $request): array
    //    {
    //        $data = [];
    //
    //        if (config('footprints.content.ip')) {
    //            $data['ip_address'] = $request->ip();
    //        }
    //
    //        if (config('footprints.content.user_agent')) {
    //            $data['user_agent'] = $request->userAgent();
    //        }
    //
    //        if (config('footprints.content.geoip')) {
    //            $supportsTags = cache()->supportsTags();
    //            if (! $supportsTags) {
    //                throw new CacheTagSupportException;
    //            }
    //
    //            $location = geoip()->getLocation($request->ip());
    //            $data['location'] = json_encode($location->toArray());
    //        }
    //
    //        return $data;
    //    }

    /**
     * @psalm-suppress NoInterfaceProperties
     */
    //    private function getUserData(?Authenticatable $user): array
    //    {
    //        $userFields = [];
    //        if ($user) {
    //            $userFields = [
    //                'user_id' => $user->id,
    //                'user_type' => $user::class,
    //            ];
    //        }
    //
    //        return $userFields;
    //    }

    //    private static function cleanPayload(string $payload): string
    //    {
    //        if (! config('footprints.content.payload.enabled')) {
    //            return '';
    //        }
    //
    //        $truncated = substr($payload, 0, (int) config('footprints.payload.max_characters'));
    //
    //        return base64_encode($truncated);  // base64 encode to prevent sqli
    //    }

    private static function cleanResponse(string $response): string
    {
        if (! config('footprints.content.response.enabled')) {
            return '';
        }

        /** @var int $maxCharacters */
        $maxCharacters = (int) config('footprints.content.response.max_characters'); // @phpstan-ignore-line

        return substr($response, 0, $maxCharacters);
    }
}
