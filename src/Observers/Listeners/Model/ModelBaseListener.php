<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Observers\Listeners\Model;

use Illuminate\Database\Eloquent\Model;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;

class ModelBaseListener extends model
{
    public function getData($event): array // @phpstan-ignore-line
    {
        $model = $event->getModel();
        $tableName = $model->getTable();

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

        return [
            'table_name' => $tableName,
            'model_type' => $model::class,
            'model_id' => $model->id,
            'model_changes' => $valuesChanged,
            'model_old' => $valuesOld,
        ];
    }
}
