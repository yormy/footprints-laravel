<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Model;

use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelDeletedListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ModelDeletedEvent $event)
    {
        if (! config('footsteps.enabled') ||
            ! config('footsteps.log_events.model_deleted')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data = [];
        $data['request_id'] = (string)$request->get('request_id');

        $valuesOld = json_encode([]);
        if (config('footsteps.model.content.values_old')) {
            $loggableFields = $model->getFootstepsFields();
            $valuesOld = BlacklistFilter::filter($model->toArray(), $loggableFields);
            $valuesOld = json_encode($valuesOld);
        }

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_DELETED,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_old' => $valuesOld,
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
