<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesArticlesShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories_articles_shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('cant',15,2)->default(1);
            $table->decimal('cost',15,2)->default(1);
            $table->timestamps();
            $table->foreignUuid('inventory_id')->references('id')->on('inventories')
                ->onDelete('cascade');
            $table->foreignUuid('articles_shops_id')->references('id')->on('articles_shops')
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
        Schema::dropIfExists('inventories_articles_shops');
    }
}
