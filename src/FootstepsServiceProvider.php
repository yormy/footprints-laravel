<?php

namespace Yormy\LaravelFootsteps;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Yormy\LaravelFootsteps\Console\InstallCommand;
use Yormy\LaravelFootsteps\Models\Log;
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

        $this->schedule();

        $this->loadMigrations();

        $this->registerCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'footsteps');

        $this->app->register(EventServiceProvider::class);
    }

    private function schedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('model:prune', [
                '--model' => Log::class
            ])->daily();

            if (config('footsteps.log_geoip')) {
                $schedule->command('geoip:update')->monthly();
            }
        });
    }

    private function publish(): void
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
                InstallCommand::class,
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
