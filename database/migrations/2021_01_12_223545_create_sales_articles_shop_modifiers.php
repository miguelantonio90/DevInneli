<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesArticlesShopModifiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sales_articles_shop_modifiers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignUuid('modifier_id')->references('id')->on('modifiers')
                ->onDelete('cascade');
            $table->foreignUuid('sales_articles_shops_id')->references('id')->on('sales_articles_shops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_articles_shop_modifiers');
    }
}
