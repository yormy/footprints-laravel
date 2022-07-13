<?php

namespace Yormy\LaravelFootsteps\Traits;

use Illuminate\Support\Facades\Auth;
use Yormy\LaravelFootsteps\Observers\Events\ModelCreatedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelDeletedEvent;
use Yormy\LaravelFootsteps\Observers\Events\ModelUpdatedEvent;
use Yormy\LaravelFootsteps\Observers\Listeners\Traits\LoggingTrait;


trait Footsteps
{
    use LoggingTrait;

    static protected $logTable = 'logs';

//    static function logToDb($model, $logType)
//    {
//        if ($model->excludeLogging || !config('footsteps.activated', true)) {
//            return;
//        }
//
//        if ($logType == 'create') $originalData = json_encode($model);
//        else {
//            if (version_compare(app()->version(), '7.0.0', '>='))
//                $originalData = json_encode($model->getRawOriginal()); // getRawOriginal available from Laravel 7.x
//            else
//                $originalData = json_encode($model->getOriginal());
//        }
//
//        $tableName = $model->getTable();
//
//        $originalData = json_decode($originalData, true);
//        $originalData['request_id'] = request()->get('request_id');
//        $originalData = json_encode($originalData);
//
//        static::createLogEntry(
//            auth()->user(),
//            request(),
//            [
//                'table_name' => $tableName,
//                'log_type'   => $logType,
//                'data'       => $originalData
//            ]);
//    }

    public static function bootFootsteps(): void
    {
        ray('boot');

        self::created(function ($model) {
            ray('created');
            event(new ModelCreatedEvent($model, Auth::user(), request()));
        });

        static::updated(function ($model) {
            ray('updated');
            event(new ModelUpdatedEvent($model, Auth::user(), request()));
        });

        static::deleted(function ($model) {
            ray('deleted');
            event(new ModelDeletedEvent($model, Auth::user(), request()));
        });
    }
}
