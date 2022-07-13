<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use LogType;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Services\BlacklistFilter;

class ModelCreatedListener extends BaseListener
{
    public function handle(ModelCreatedEvent $event)
    {
        ray('listen');
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_created')
        ) {
            return;
        }

        $model = $event->getModel();
        $tableName = $model->getTable();

        $request = $event->getRequest();
        $data['request_id'] = $request->get('request_id');

        $fields = [
            'table_name' => $tableName,
            'log_type'   => LogType::CREATED,
            'model_old' => BlacklistFilter::filter($model->toArray()),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }

//    private function getOriginalData()
//    {
//        if ($logType == 'create') ;
//        else {
//            if (version_compare(app()->version(), '7.0.0', '>='))
//                $originalData = json_encode($model->getRawOriginal()); // getRawOriginal available from Laravel 7.x
//            else
//                $originalData = json_encode($model->getOriginal());
//        }
//
//
//
//
//    }
}
