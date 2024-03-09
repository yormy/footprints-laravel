<?php

namespace Yormy\FootprintsLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;

class FootprintsRoutes
{
    public static function register(): void
    {
        Route::macro('FootprintsApiRoutes', function (string $prefix = '') {
            Route::prefix($prefix)
                ->name('footprints.')
                ->group(function () {

                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function () {
                            Route::get('/', [LoginHistoryController::class, 'index'])->name('index');
                        });
                });
        });
    }
}
