<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Model;

use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelUpdatedListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ModelUpdatedEvent $event)
    {
        ray('updated');
        if (! config('footsteps.enabled') ||
            ! config('footsteps.log_events.model_updated')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();

        $data = [];
        $data['request_id'] = (string)$request->get('request_id');

        $changes = $model->getChanges();

        /**
         * @var array<array-key, mixed> $original
         */
        $original = $model->getRawOriginal();

        $valuesOld = json_encode([]);
        if (config('footsteps.model.values_old')) {
            $valuesOld = BlacklistFilter::filter($model->toArray());
        }

        $valuesChanged = json_encode([]);
        if (config('footsteps.model.values_changed')) {
            $valuesChanged = BlacklistFilter::filter($changes);
        }

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_UPDATED,
            'model_type' => get_class($model),
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
