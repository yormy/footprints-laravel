<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Model;

use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Events\ModelDeletedEvent;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;

class ModelDeletedListener extends BaseListener
{
    public function handle(ModelDeletedEvent $event): void
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.model_deleted')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data = [];
        $data['request_id'] = (string) $request->get('request_id');

        $valuesOld = json_encode([]);
        if (config('footprints.model.content.values_old')) {
            /** @var array $loggableFields */
            $loggableFields = $model->getFootprintsFields();
            $valuesOld = BlacklistFilter::filter($model->toArray(), $loggableFields);
            $valuesOld = json_encode($valuesOld);
        }

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_DELETED,
            'model_type' => $model::class,
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
