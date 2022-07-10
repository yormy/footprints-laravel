<?php

namespace Yormy\LaravelFootsteps;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yormy\LaravelFootsteps\Console\Commands\TestCommand;

class FootstepsServiceProvider extends ServiceProvider
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('footsteps.php'),
            ], 'config');

            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

            $this->registerCommands();
        }

//        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xid');
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'footsteps');
    }

    private function registerCommands(): void
    {
        $this->commands([
            TestCommand::class,
        ]);
    }
}
