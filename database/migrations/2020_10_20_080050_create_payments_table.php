<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('method')->default('cash');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignUuid('company_id')->references('id')->on('companies')
                ->onDelete('cascade');
        });
        Schema::table('inventories', function($table) {
            $table->foreignUuid('payment_id')->nullable()->references('id')->on('payments')
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
        Schema::dropIfExists('payments');
    }
}
