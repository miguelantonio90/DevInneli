<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sales_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignUuid('discount_id')->references('id')->on('discounts')
                ->onDelete('cascade');
            $table->foreignUuid('sale_id')->references('id')->on('sales')
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
        Schema::dropIfExists('sales_discounts');
    }
}
