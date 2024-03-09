<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners\Model;

use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Events\ModelCreatedEvent;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;

class ModelCreatedListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ModelCreatedEvent $event)
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.model_created')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data = [];
        $data['request_id'] = (string)$request->get('request_id');

        $valuesOld = json_encode([]);
        if (config('footprints.model.content.values_old')) {
            /** @var array $loggableFields */
            $loggableFields = $model->getFootprintsFields();
            $valuesOld = BlacklistFilter::filter($model->toArray(), $loggableFields);
            $valuesOld = json_encode($valuesOld);
        }

        $fields = [
            'table_name' => $tableName,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'log_type' => LogType::MODEL_CREATED,
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
