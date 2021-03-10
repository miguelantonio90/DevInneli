<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'users', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->integer('pinCode')->nullable();
            $table->string('country')->nullable();
            $table->boolean('isAdmin')->default(0);
            $table->boolean('isManager')->default(0);
            $table->boolean('isSupplier')->default(0);
            $table->longText('avatar')->nullable();
            $table->boolean('isLoginPin')->nullable()->default(false);
            $table->rememberToken();
        });
        parent::up($tableName, true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
