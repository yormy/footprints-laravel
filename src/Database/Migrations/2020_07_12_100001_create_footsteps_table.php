<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((string)config('footsteps.table_name'), function (Blueprint $table) {
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


            $table->string('route')->nullable();
            $table->string('url')->nullable();

            $table->json('data')->nullable();
            $table->json('model_old')->nullable();
            $table->json('model_changes')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser_fingerprint', 50)->nullable();
            $table->json('location')->nullable();

            $table->text('payload_base64')->nullable();
            $table->text('response_base64')->nullable();

            $table->string('request_id')->index()->nullable();
            $table->double('request_duration_sec')->nullable();

            $table->timestamps();
        });
    }
};
