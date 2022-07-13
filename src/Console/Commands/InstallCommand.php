<?php

namespace Yormy\LaravelFootsteps\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    protected $signature = 'footsteps:install';

    protected $description = 'Publish the configuration and run the migrations';

    protected string $migrationFile = "2020_07_12_100001_create_footsteps_table.php";

    public function handle()
    {
        $this->checkAndPublishConfig();
        $this->checkAndPublishMigrations();

        $this->runMigrations();
    }

    private function runMigrations()
    {
        $logModelClass = config('footsteps.log_model');
        $table = (new $logModelClass)->getTable();

        $this->line('-----------------------------');
        if (!Schema::hasTable($table)) {
            $this->call('migrate');
        } else {
            $this->error('logs table already exist in your database. migration not run successfully');
        }
    }

    private function checkAndPublishConfig()
    {
        if (File::exists(config_path('footsteps.php'))) {
            $confirm = $this->confirm("footsteps.php config file already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishConfig();
                $this->info("config overwrite finished");
            } else {
                $this->info("skipped config publish");
            }
        } else {
            $this->publishConfig();
            $this->info("config published");
        }
    }

    private function checkAndPublishMigrations()
    {

        if (File::exists(database_path("migrations/$this->migrationFile"))) {
            $confirm = $this->confirm("migration file already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishMigration();
                $this->info("migration overwrite finished");
            } else {
                $this->info("skipped migration publish");
            }
        } else {
            $this->publishMigration();
            $this->info("migration published");
        }
    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "Yormy\LaravelFootsteps\ServiceProvider",
            '--tag'      => 'config',
            '--force'    => true
        ]);
    }

    private function publishMigration()
    {
        $this->call('vendor:publish', [
            '--provider' => "Yormy\LaravelFootsteps\ServiceProvider",
            '--tag'      => 'migrations',
            '--force'    => true
        ]);
    }

}
