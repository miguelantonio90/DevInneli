<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories_tax', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->foreignUuid('tax_id')->references('id')->on('taxes')
                ->onDelete('cascade');
            $table->foreignUuid('inventory_id')->references('id')->on('inventories')
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
        Schema::dropIfExists('inventories_tax');
    }
}
