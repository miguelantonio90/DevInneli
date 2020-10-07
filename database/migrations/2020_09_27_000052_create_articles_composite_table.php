<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesCompositeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_composite', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cant');
            $table->json('price');
            $table->unsignedInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->unsignedInteger('article_composite_id');
            $table->foreign('article_composite_id')->references('id')->on('articles')
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
        Schema::dropIfExists('articles_composite');
    }
}
