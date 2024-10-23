<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\FootprintsLaravel\Http\Resources\LogItemCollection;
use Yormy\FootprintsLaravel\Repositories\FootprintItemRepository;

class ActivityController extends BaseController
{
    public function indexForUser(Request $request, $member_xid)
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;
        $user = $userResolver->getMember('xid', $member_xid);

        return $this->returnForUser($request, $user);
    }

    private function returnForUser($request, $user)
    {
        $logItemRepository = new FootprintItemRepository;
        $logins = $logItemRepository->getAllActivityForUser($user);

        $logins = (new LogItemCollection($logins))->toArray($request);

        $logins = $this->decorateWithStatus($logins);

        return ApiResponse::withData($logins)
            ->successResponse();
    }

    private function decorateWithStatus($values): array
    {
        foreach ($values as $index => $data) {
            $status = '';
            if ($data['log_type'] === 'AUTH_LOGIN') {
                $status = [
                    'key' => 'Login',
                    'nature' => 'success',
                    'text' => __('footprints::activity.auth_login'),
                ];
            }
            if ($data['log_type'] === 'AUTH_FAILED') {
                $status = [
                    'key' => 'Login',
                    'nature' => 'danger',
                    'text' => __('footprints::activity.auth_failed'),
                ];
            }

            if ($data['log_type'] === 'ROUTE_VISIT') {
                $status = [
                    'key' => 'Visit',
                    'nature' => 'info',
                    'text' => __('footprints::activity.route_visit'),
                ];
            }

            if ($method = Arr::get($data, 'method')) {
                $status = [
                    'key' => $method,
                    'text' => $method,
                ];

                if ($method === 'GET') {
                    $status['nature'] = 'info';
                } elseif ($method === 'POST') {
                    $status['nature'] = 'warning';
                } elseif ($method === 'PUT') {
                    $status['nature'] = 'info';
                } elseif ($method === 'PATCH') {
                    $status['nature'] = 'info';
                } elseif ($method === 'DELETE') {
                    $status['nature'] = 'info';
                }
            }

            $values[$index]['status'] = $status;
        }

        return $values;
    }
}
