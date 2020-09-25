<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email')->unique();
            $table->string('employeeEmail')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->integer('pinCode')->nullable();
            $table->boolean('isAdmin')->default(0);
            $table->boolean('isManager')->default(0);
            $table->longText('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('cascade');
            $table->unsignedInteger('position_id');
            $table->foreign('position_id')->references('id')->on('positions')
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
        Schema::dropIfExists('users');
    }
}
