<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('article_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->longText('path')->nullable();
            $table->boolean('default')->nullable();
            $table->string('position')->nullable();
            $table->foreignUuid('article_id')->nullable()->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('shop_id')->nullable()->references('id')->on('shops')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('article_images');
    }
}
