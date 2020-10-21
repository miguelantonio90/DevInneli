<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('datetimeEntry');
            $table->dateTime('datetimeExit');
            $table->integer('totalHours');
            $table->timestamps();

            $table->foreignUuid('company_id')->references('id')->on('company')
                ->onDelete('cascade');
            $table->foreignUuid('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreignUuid('shop_id')->references('id')->on('shops')
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
        Schema::dropIfExists('assistances');
    }
}
