<?php

namespace Yormy\FootprintsLaravel\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Yormy\FootprintsLaravel\Models\Footprint;

trait PackageFactoryTrait
{
    use HasFactory;

    protected static function newFactory(): Footprint
    {
        $package = Str::before(get_called_class(), 'Models\\');
        $modelName = Str::after(get_called_class(), 'Models\\');
        $path = $package.'Database\\Factories\\'.$modelName.'Factory';

        return $path::new();
    }
}
