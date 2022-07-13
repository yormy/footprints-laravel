<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;

class ModelUpdatedListener extends BaseListener
{
    public function handle(ModelUpdatedEvent $event)
    {
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_update')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data['request_id'] = $request->get('request_id');

        $fields = [
            'table_name' => $tableName,
            'log_type' => 'updatoie ?',
            'model_changes' => json_encode($model->getChanges()),
            'model_old' => json_encode($model->getRawOriginal()),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
