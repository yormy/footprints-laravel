<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Yormy\FootprintsLaravel\Routes\FootprintsAdminRoutes;
use Yormy\FootprintsLaravel\Routes\FootprintsRoutes;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->map();
    }

    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
    }

    protected function mapApiRoutes(): void
    {
        FootprintsRoutes::register();
        FootprintsAdminRoutes::register();
    }
}
