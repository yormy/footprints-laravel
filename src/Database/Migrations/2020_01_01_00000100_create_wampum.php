<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootsteps extends Migration
{
    /**
     * @psalm-suppress MissingReturnType
     */
    public function up()
    {
        Schema::create('footsteps', function (Blueprint $table) {
            $table->id('id');

            $table->string('title');

            $table->string('description')->nullable();

            $table->boolean('needs_translation')->default(1);

            $table->nullableMorphs('mainable');

            $table->nullableMorphs('relatable');

            $table->json('values_old')->nullable();

            $table->json('values_new')->nullable();

            $table->json('values_diff')->nullable();

            $table->string('type')->nullable()->comment('CREATED, UPDATED, DELETED');

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * @psalm-suppress MissingReturnType
     */
    public function down()
    {
        Schema::dropIfExists('footsteps');
    }
}
