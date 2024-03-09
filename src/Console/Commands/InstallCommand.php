<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    protected $signature = 'footprints:install';

    protected $description = 'Publish the configuration and run the migrations';

    protected string $migrationFile = '2020_07_12_100001_create_footprints_table.php';

    public function handle(): void
    {
        $this->checkAndPublishConfig();
        $this->checkAndPublishMigrations();

        $this->runMigrations();
    }

    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress InvalidStringClass
     */
    private function runMigrations(): void
    {
        $logModelClass = (string) config('footprints.log_model');

        $table = (new $logModelClass())->getTable();

        $this->line('-----------------------------');
        if (! Schema::hasTable($table)) {
            $this->call('migrate');
        } else {
            $this->error('logs table already exist in your database. migration not run successfully');
        }
    }

    private function checkAndPublishConfig(): void
    {
        if (File::exists(config_path('footprints.php'))) {
            $confirm = $this->confirm('footprints.php config file already exist. Do you want to overwrite?');
            if ($confirm) {
                $this->publishConfig();
                $this->info('config overwrite finished');
            } else {
                $this->info('skipped config publish');
            }
        } else {
            $this->publishConfig();
            $this->info('config published');
        }
    }

    private function checkAndPublishMigrations(): void
    {
        if (File::exists(database_path("migrations/{$this->migrationFile}"))) {
            $confirm = $this->confirm('migration file already exist. Do you want to overwrite?');
            if ($confirm) {
                $this->publishMigration();
                $this->info('migration overwrite finished');
            } else {
                $this->info('skipped migration publish');
            }
        } else {
            $this->publishMigration();
            $this->info('migration published');
        }
    }

    private function publishConfig(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Yormy\FootprintsLaravel\ServiceProvider",
            '--tag' => 'config',
            '--force' => true,
        ]);
    }

    private function publishMigration(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Yormy\FootprintsLaravel\ServiceProvider",
            '--tag' => 'migrations',
            '--force' => true,
        ]);
    }
}
