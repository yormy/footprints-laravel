<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Model;

use Yormy\FootprintsLaravel\Enums\LogType;
use Yormy\FootprintsLaravel\Observers\Events\ModelUpdatedEvent;
use Yormy\FootprintsLaravel\Observers\Listeners\BaseListener;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;

class ModelUpdatedListener extends BaseListener
{
    public function handle(ModelUpdatedEvent $event): void
    {
        ray('updated');
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.model_updated')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();

        $data = [];
        $data['request_id'] = (string) $request->get('request_id');

        /** @var array $loggableFields */
        $loggableFields = $model->getFootprintsFields();

        $valuesOld = json_encode([]);
        if (config('footprints.content.model.values_old')) {
            /** @var array<array-key, mixed> $valuesOld */
            $valuesOld = $model->getRawOriginal();
            $valuesOld = BlacklistFilter::filter($valuesOld, $loggableFields);
            $valuesOld = json_encode($valuesOld);
        }

        $valuesChanged = json_encode([]);
        if (config('footprints.content.model.values_changed')) {
            $valuesChanged = $model->getChanges();
            $valuesChanged = BlacklistFilter::filter($valuesChanged, $loggableFields);
            $valuesChanged = json_encode($valuesChanged);
        }

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_UPDATED,
            'model_type' => $model::class,
            'model_id' => $model->id,
            'model_changes' => $valuesChanged,
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
