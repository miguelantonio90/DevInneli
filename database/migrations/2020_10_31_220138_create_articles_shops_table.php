<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('price',15,2);
            $table->integer('stock')->nullable();
            $table->integer('under_inventory')->nullable();
            $table->timestamps();
            $table->foreignUuid('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('shops_id')->references('id')->on('shops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_shops');
    }
}
