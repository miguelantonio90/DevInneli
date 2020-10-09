<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesCompositesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_composites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cant');
            $table->decimal('price');
            $table->unsignedInteger('articles_id');
            $table->foreign('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->unsignedInteger('composite_id');
            $table->foreign('composite_id')->references('id')->on('articles')
                ->onDelete('cascade');
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
        Schema::dropIfExists('articles_composites');
    }
}
