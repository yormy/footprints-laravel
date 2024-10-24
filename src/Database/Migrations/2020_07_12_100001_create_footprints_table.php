<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((string) config('footprints.table_name'), function (Blueprint $table): void {
            $table->id();

            $table->string('xid')->unique();
            $table->unsignedBigInteger('impersonator_id')->unsigned()->nullable();
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->string('user_type')->nullable();

            $table->string('log_type', 50);
            $table->string('method', 10)->nullable();
            $table->string('table_name', 50)->nullable();

            $table->bigInteger('model_id')->unsigned()->nullable();
            $table->string('model_type', 50)->nullable();

            $table->string('route', 200)->nullable();
            $table->string('url', 200)->nullable();

            $table->text('data')->nullable();
            $table->text('model_old')->nullable();
            $table->text('model_changes')->nullable();

            $table->text('ip_address')->nullable();
            $table->string('user_agent', 200)->nullable();
            $table->string('browser_fingerprint', 50)->nullable();
            $table->json('location')->nullable();

            $table->text('payload')->nullable();
            $table->text('response')->nullable();
            $table->text('custom_cookies')->nullable();
            $table->text('custom_data')->nullable();

            $table->string('session_id')->index()->nullable();
            $table->string('request_id')->index()->nullable();
            $table->double('request_duration_sec')->nullable();
            $table->double('page_visit_sec')->nullable();

            $table->timestamps();
        });
    }
};
