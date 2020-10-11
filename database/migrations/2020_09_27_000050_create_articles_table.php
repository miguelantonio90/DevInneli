<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->boolean('unit')->default(false);
            $table->decimal('price')->nullable();
            $table->string('cost')->nullable();
            $table->string('ref')->nullable();
            $table->string('barCode')->nullable();
            $table->boolean('composite')->default(false);
            $table->boolean('inventory')->default(false);
            $table->boolean('track_inventory')->default(false);
            $table->boolean('itbis')->default(false);
            $table->boolean('lay')->default(true);
            $table->timestamps();

            $table->foreignUuid('company_id')->references('id')->on('companies')
                ->onDelete('cascade');
            $table->foreignUuid('category_id')->references('id')->on('categories')
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
        Schema::dropIfExists('articles');
    }
}
