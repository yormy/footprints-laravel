<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners\Model;

use Yormy\LaravelFootsteps\Enums\LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\BaseListener;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelCreatedListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(ModelCreatedEvent $event)
    {
        if (! config('footsteps.enabled') ||
            ! config('footsteps.log_events.model_created')
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
            'log_type' => LogType::MODEL_CREATED,
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
