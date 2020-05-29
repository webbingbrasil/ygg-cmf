<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYggResourcesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('type');
            $table->string('status')->nullable();
            $table->jsonb('content')->nullable();
            $table->jsonb('options')->nullable();
            $table->string('slug')->unique();
            $table->timestamp('publish_at')->nullable()->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'type']);

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('resources');
    }
}
