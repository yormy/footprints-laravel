<?php

namespace Yormy\LaravelFootsteps\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\LaravelFootsteps\Http\Controllers\Api\V1\LoginHistoryController;

class FootstepsRoutes
{
    public static function register(): void
    {
        Route::macro('FootstepsApiRoutes', function (string $prefix = '') {
            Route::prefix($prefix)
                ->name('footsteps.')
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
