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
            $table->increments('id');
            $table->unsignedInteger('articles_id');
            $table->foreign('articles_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->string('variant');
            $table->decimal('cost');
            $table->decimal('price');
            $table->integer('ref')->nullable();
            $table->bigInteger('barCode')->nullable();
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
        Schema::dropIfExists('variants_values');
    }
}
