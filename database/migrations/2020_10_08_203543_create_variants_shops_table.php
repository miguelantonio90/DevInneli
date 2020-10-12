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
            $table->id();
            $table->unsignedInteger('articles_id');
            $table->foreign('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->unsignedInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')
                ->onDelete('cascade');
            $table->unsignedInteger('vv_id');
            $table->foreign('vv_id')->references('id')->on('variants_values')
                ->onDelete('cascade');
            $table->decimal('price');
            $table->integer('stock')->nullable();
            $table->integer('under_inventory')->nullable();

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
        Schema::dropIfExists('variants_shops');
    }
}
