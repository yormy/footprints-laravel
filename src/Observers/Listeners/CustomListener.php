<?php

namespace Yormy\FootprintsLaravel\Observers\Listeners;

use Yormy\FootprintsLaravel\Observers\Events\CustomFootprintEvent;

class CustomListener extends BaseListener
{
    /**
     * @return void
     */
    public function handle(CustomFootprintEvent $event)
    {
        if (! config('footprints.enabled') ||
            ! config('footprints.log_events.on_custom')
        ) {
            return;
        }

        $data = $event->getData();
        $request = $event->getRequest();
        $data['request_id'] = (string) $request->get('request_id');

        $fields = [
            'table_name' => $event->getTableName(),
            'log_type' => $event->getLogType(),
            'data' => json_encode($data),
        ];

        $this->logItemRepository->createLogEntry(
            $event->getUser(),
            $event->getRequest(),
            $fields
        );
    }
}
