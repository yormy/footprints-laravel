<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Yormy\LaravelFootsteps\Observers\Events\CustomFootstepEvent;

class CustomListener extends BaseListener
{
    public function handle(CustomFootstepEvent $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_custom')
        ) {
            return;
        }

        $data = $event->getData();
        $request = $event->getRequest();
        $data['request_id'] = $request->get('request_id');


        $fields = [
            'table_name' => $event->getTableName(),
            'log_type' => $event->getLogType(),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
