<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopTypeOfOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_type_of_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('available')->default(true);
            $table->boolean('principal')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreignUuid('shop_id')->references('id')->on('shops')
                ->onDelete('cascade');
            $table->foreignUuid('type_of_order_id')->references('id')->on('type_of_orders')
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
        Schema::dropIfExists('shop_type_of_orders');
    }
}
