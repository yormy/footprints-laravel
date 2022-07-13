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

        $fields = [
            'table_name' => $tableName,
            'log_type' => LogType::MODEL_DELETED,
            'model_type' => get_class($model),
            'model_id' => $model->id,
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
