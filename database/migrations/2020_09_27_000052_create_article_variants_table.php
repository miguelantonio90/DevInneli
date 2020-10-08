<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->json('value');
            $table->json('price');
            $table->json('cost');
            $table->json('ref');
            $table->json('barCode');
            $table->unsignedInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')
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
        Schema::dropIfExists('article_variants');
    }
}
