<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Base\LoginHistoryController;

class FootprintsRoutes
{
    public static function register(): void
    {
        Route::macro('FootprintsApiRoutes', function (string $prefix = ''): void {
            Route::prefix($prefix)
                ->name('footprints.')
                ->group(function (): void {
                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function (): void {
                            Route::get('/', [LoginHistoryController::class, 'index'])->name('index');
                        });
                });
        });
    }
}
