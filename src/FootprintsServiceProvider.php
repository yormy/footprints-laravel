<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Yormy\FootprintsLaravel\Console\Commands\InstallCommand;
use Yormy\FootprintsLaravel\Models\Footprint;
use Yormy\FootprintsLaravel\ServiceProviders\EventServiceProvider;
use Yormy\FootprintsLaravel\ServiceProviders\RouteServiceProvider;

class FootprintsServiceProvider extends ServiceProvider
{
    public const CONFIG_FILE = __DIR__.'/../config/footprints.php';

    public const MIGRATION_PATH = __DIR__.'/Database/Migrations';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot(): void
    {
        $this->publish();

        $this->schedule();

        $this->loadMigrations();

        $this->registerCommands();

        $this->registerTranslations();
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function register(): void
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'footprints');
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'footprints');
    }

    private function schedule(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command('model:prune', [
                '--model' => Footprint::class,
            ])->daily();

            // @phpstan-ignore-next-line
            if (config('footprints.log_geoip')) {
                $schedule->command('geoip:update')->monthly();
            }
        });
    }

    private function publish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_FILE => config_path('footprints.php'),
            ], 'config');

            $this->publishes([
                self::MIGRATION_PATH => database_path('migrations'),
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
            $this->loadMigrationsFrom((string) static::MIGRATION_PATH);
        }
    }
}
