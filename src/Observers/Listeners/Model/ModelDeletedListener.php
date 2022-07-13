<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelDeletedListener extends BaseListener
{
    public function handle(ModelDeletedEvent $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_deleted')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data['request_id'] = $request->get('request_id');

        $fields = [
            'table_name' => $tableName,
            'log_type'   => LogType::MODEL_DELETED,
            'model_old' => BlacklistFilter::filter($model->toArray()),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
