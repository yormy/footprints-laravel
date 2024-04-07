<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Admins\AdminLoginHistoryController;
use Yormy\FootprintsLaravel\Http\Controllers\Api\V1\Members\MemberLoginHistoryController;

class FootprintsAdminRoutes
{
    public static function register(): void
    {
        Route::macro('FootprintsAdminApiRoutes', function (string $prefix = ''): void {
            Route::prefix($prefix)
                ->name('footprints.')
                ->group(function (): void {
                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function (): void {
                            Route::get('/{member_xid}', [MemberLoginHistoryController::class, 'indexForUser'])->name('index');
                        });

                    Route::prefix('activity')
                        ->name('activity.')
                        ->group(function (): void {
                            Route::get('/{member_xid}', [MemberLoginHistoryController::class, 'indexForUser'])->name('index');
                        });
                });
        });

        Route::macro('FootprintsAdminAdminApiRoutes', function (string $prefix = ''): void {
            Route::prefix($prefix)
                ->name('footprints.')
                ->group(function (): void {
                    Route::prefix('loginhistory')
                        ->name('loginhistory.')
                        ->group(function (): void {
                            Route::get('/{admin_xid}', [AdminLoginHistoryController::class, 'indexForUser'])->name('index');
                        });

                    Route::prefix('activity')
                        ->name('activity.')
                        ->group(function (): void {
                            Route::get('/{admin_xid}', [AdminLoginHistoryController::class, 'indexForUser'])->name('index');
                        });
                });
        });
    }
}
