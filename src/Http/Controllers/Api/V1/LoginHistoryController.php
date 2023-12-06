<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Yormy\Apiresponse\Facades\ApiResponse;
use Yormy\LaravelFootsteps\Repositories\LogItemRepository;
use  Yormy\LaravelFootsteps\Http\Resources\LogItemCollection;

class LoginHistoryController extends BaseController
{
    public function index(Request $request)
    {
        $logItemRepository = new LogItemRepository();
        $logins = $logItemRepository->getAllLoginForUser($this->user);

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

