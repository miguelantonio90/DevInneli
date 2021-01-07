<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     *
     */
    public function up():void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('sector')->default('others');
            $table->string('currency')->nullable();
            $table->string('address')->nullable();
            $table->string('slogan')->nullable();
            $table->longText('logo')->nullable();
            $table->string('faker')->default(0);
            $table->uuid('id')->primary();
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
        Schema::dropIfExists('companies');
    }
}
