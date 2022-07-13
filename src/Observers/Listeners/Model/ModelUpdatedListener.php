<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelUpdatedListener extends BaseListener
{
    public function handle(ModelUpdatedEvent $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.model_update')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data['request_id'] = $request->get('request_id');


        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_UPDATED,
            'model_changes' => BlacklistFilter::filter($model->getChanges()),
            'model_old' => BlacklistFilter::filter($model->getRawOriginal()),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
