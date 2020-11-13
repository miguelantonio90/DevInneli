<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesArticlesShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_articles_shops',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->integer('cant')->default(1);
                $table->decimal('price', 15, 2)->default(1);
                $table->foreignUuid('sale_id')->references('id')->on('sales')
                    ->onDelete('cascade');
                $table->foreignUuid('articles_shops_id')->references('id')->on('articles_shops')
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
    public function down()
    {
        Schema::dropIfExists('sales_articles_shops');
    }
}
