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
        Schema::create('footsteps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('user_type')->nullable();

            $table->string('log_type',50);
            $table->string('table_name',50)->nullable();

            $table->string('route')->nullable();
            $table->string('url')->nullable();

            $table->longText('data')->nullable();

            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();

            $table->text('payload_base64')->nullable();
            $table->string('request_id')->index()->nullable();
            $table->double('request_start')->nullable();
            $table->double('request_duration')->nullable();

            $table->json('location')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
