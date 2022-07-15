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

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_UPDATED,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'model_changes' => BlacklistFilter::filter($changes),
            'model_old' => BlacklistFilter::filter($original),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
