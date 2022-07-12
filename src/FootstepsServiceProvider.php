<?php

namespace Yormy\LaravelFootsteps;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yormy\LaravelFootsteps\ServiceProviders\EventServiceProvider;

class FootstepsServiceProvider extends ServiceProvider
{
    const CONFIG_FILE = __DIR__ . '/../config/footsteps.php';
    const MIGRATION_PATH = __DIR__ . '/Database/Migrations';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot()
    {
        $this->publish();

        $this->loadMigrationsFrom(static::MIGRATION_PATH);

        $this->registerCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'footsteps');

        $this->app->register(EventServiceProvider::class);
    }

    private function publish()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_FILE => config_path('footsteps.php')
            ], 'config');

            $this->publishes([
                self::MIGRATION_PATH => database_path('migrations')
            ], 'migrations');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    private function loadMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(static::MIGRATION_PATH);
        }
    }
}
