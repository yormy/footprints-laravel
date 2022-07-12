<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Traits;

use Illuminate\Database\Eloquent\Model;

trait LoggingTrait
{
    private static function cleanPayload(string $payload)
    {
        if (!config('footsteps.log_response.enabled')) {
            return 'nope';
        }

        $truncated = substr($payload, 0,config('footsteps.log_response.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
    }

    private static function createLogEntry(?Model $user, $request, array $props)
    {
        $logModelClass = config('footsteps.log_model');
        $logModel = new $logModelClass;

        $data = $props;
        $userFields = [];
        if ($user) {
            $userFields = [
                'user_id' => $user->id,
                'user_type' => get_class($user),
            ];

            $data =  array_merge($userFields, $data);
        }

        $data['ip'] = $request->ip();
        $data['user_agent'] = $request->userAgent();


        ray($data)->color('blue');
        $logModel->create($data);
    }
}
