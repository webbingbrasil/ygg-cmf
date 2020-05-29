<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYggTaxonomyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('term_taxonomy', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('term_id');
            $table->string('taxonomy');
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['id', 'taxonomy']);

            $table->foreign('parent_id')
                ->references('id')
                ->on('term_taxonomy')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('term_id')
                ->references('id')
                ->on('terms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('term_taxonomy');
    }
}
