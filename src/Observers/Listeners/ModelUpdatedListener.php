<?php

namespace Yormy\LaravelFootsteps\Observers\Listeners;

use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;

class ModelUpdatedListener extends BaseListener
{
    public function handle(ModelUpdatedEvent $event)
    {
        ray('updated');
        if (!config('footsteps.enabled') ||
            !config('footsteps.log_events.on_update')
        ) {
            return;
        }
        ray('updated-go');
        $model = $event->getModel();
        $originalData = json_encode($model);
        $tableName = $model->getTable();

        $request = $event->getRequest();

        $originalData = json_decode($originalData, true);
        $originalData['request_id'] = $request->get('request_id');
        $originalData = json_encode($originalData);

        $fields = [
            'table_name' => $tableName,
            'log_type' => 'updatoie ?',
            'data' => $originalData
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
