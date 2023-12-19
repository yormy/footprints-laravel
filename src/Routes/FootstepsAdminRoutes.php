<?php

namespace Yormy\LaravelFootsteps\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\LaravelFootsteps\Http\Controllers\Api\V1\ActivityController;
use Yormy\LaravelFootsteps\Http\Controllers\Api\V1\Admins\AdminLoginHistoryController;
use Yormy\LaravelFootsteps\Http\Controllers\Api\V1\Base\LoginHistoryController;
use Yormy\LaravelFootsteps\Http\Controllers\Api\V1\Members\MemberLoginHistoryController;

class FootstepsAdminRoutes
{
    public static function register(): void
    {
        Route::macro('FootstepsAdminApiRoutes', function (string $prefix = '') {
            Route::prefix($prefix)
                ->name('footsteps.')
                ->group(function () {

                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function () {
                            Route::get('/{member_xid}', [MemberLoginHistoryController::class, 'indexForUser'])->name('index');
                        });

                    Route::prefix('activity')
                        ->name('activity.')
                        ->group(function () {
                            Route::get('/{member_xid}', [MemberLoginHistoryController::class, 'indexForUser'])->name('index');
                        });
                });
        });

        Route::macro('FootstepsAdminAdminApiRoutes', function (string $prefix = '') {
            Route::prefix($prefix)
                ->name('footsteps.')
                ->group(function () {

                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function () {
                            Route::get('/{admin_xid}', [AdminLoginHistoryController::class, 'indexForUser'])->name('index');
                        });

                    Route::prefix('activity')
                        ->name('activity.')
                        ->group(function () {
                            Route::get('/{admin_xid}', [AdminLoginHistoryController::class, 'indexForUser'])->name('index');
                        });
                });
        });
    }
}
