<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('variant');
            $table->decimal('cost');
            $table->decimal('price');
            $table->integer('ref')->nullable();
            $table->string('barCode')->nullable();
            $table->timestamps();

            $table->foreignUuid('articles_id')->references('id')->on('articles')
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
        Schema::dropIfExists('variants_values');
    }
}
