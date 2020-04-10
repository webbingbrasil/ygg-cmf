<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYggCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('content');
            $table->boolean('approved')->nullable();
            $table->timestamps();

            $table->index(['approved', 'resource_id']);
            $table->index('resource_id');
            $table->index('parent_id');
            $table->foreign('resource_id')
                ->references('id')
                ->on('resources')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::drop('comments');
    }
}
