<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\LaravelFootsteps\Repositories\LogItemRepository;
use  Yormy\LaravelFootsteps\Http\Resources\LogItemCollection;
use Yormy\LaravelFootsteps\Services\Resolvers\UserResolver;

class ActivityController extends BaseController
{
    public function indexForUser(Request $request, $member_xid)
    {
        $user = UserResolver::getMemberOnXId($member_xid);

        return $this->returnForUser($request, $user);
    }


    private function returnForUser($request, $user)
    {
        $logItemRepository = new LogItemRepository();
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
                    'text' => __('footsteps::activity.auth_login'),
                ];
            }
            if ($data['log_type'] === 'AUTH_FAILED') {
                $status = [
                    'key' => 'Login',
                    'nature' => 'danger',
                    'text' => __('footsteps::activity.auth_failed'),
                ];
            }

            if ($data['log_type'] === 'ROUTE_VISIT') {
                $status = [
                    'key' => 'Visit',
                    'nature' => 'info',
                    'text' => __('footsteps::activity.route_visit'),
                ];
            }

            if ($method = Arr::get($data, 'method')) {
                $status = [
                    'key' => $method,
                    'text' => $method
                ];

                if ($method === 'GET') {
                    $status['nature'] = 'info';
                }elseif ($method === 'POST') {
                    $status['nature'] = 'warning';
                } elseif ($method === 'PUT') {
                    $status['nature'] = 'info';
                } elseif ($method === 'PATCH') {
                    $status['nature'] = 'info';
                } elseif ($method === 'DELETE') {
                    $status['nature'] = 'info';                }
            }

            $values[$index]['status'] = $status;
        }

        return $values;
    }
}

