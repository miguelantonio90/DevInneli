<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants_shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('price');
            $table->integer('stock');
            $table->integer('under_inventory');
            $table->timestamps();

            $table->foreignUuid('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('shop_id')->references('id')->on('shops')
                ->onDelete('cascade');
            $table->foreignUuid('vv_id')->references('id')->on('variants_values')
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
        Schema::dropIfExists('variants_shops');
    }
}
