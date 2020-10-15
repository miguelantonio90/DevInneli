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
            $table->uuid('id')->primary();
            $table->string('cant');
            $table->decimal('price');
            $table->foreignUuid('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('composite_id')->references('id')->on('articles')
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
