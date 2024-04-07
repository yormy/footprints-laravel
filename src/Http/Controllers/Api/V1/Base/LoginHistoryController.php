<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base;

use Illuminate\Http\Request;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\BaseController;
use Yormy\FootprintsLaravel\Http\Resources\LogItemCollection;
use Yormy\FootprintsLaravel\Repositories\LogItemRepository;

abstract class LoginHistoryController extends BaseController
{
    public function index(Request $request)
    {
        return $this->returnForUser($request, $this->user);
    }

    public function indexForUser(Request $request, $member_xid)
    {
        $user = $this->getUser($member_xid);

        return $this->returnForUser($request, $user);
    }

    private function returnForUser($request, $user)
    {
        $logItemRepository = new LogItemRepository();
        $logins = $logItemRepository->getAllLoginForUser($user);

        $logins = (new LogItemCollection($logins))->toArray($request);
        $logins = $this->decorateWithStatus($logins);

        return ApiResponse::withData($logins)
            ->successResponse();
    }

    private function decorateWithStatus($values): array
    {
        foreach ($values as $index => $data) {
            if ($data['log_type'] === 'AUTH_LOGIN') {
                $status = [
                    'key' => 'success',
                    'nature' => 'success',
                    'text' => __('bedrock-usersv2::status.success'),
                ];
            }
            if ($data['log_type'] === 'AUTH_FAILED') {
                $status = [
                    'key' => 'failed',
                    'nature' => 'danger',
                    'text' => __('bedrock-usersv2::status.failed'),
                ];
            }

            $values[$index]['status'] = $status;
        }

        return $values;
    }
}
