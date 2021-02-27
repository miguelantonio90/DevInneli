<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'clients', bool $company = true): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('country')->nullable();
            $table->string('barCode')->nullable();
            $table->longText('avatar')->nullable();
            $table->string('description')->nullable();
            $table->rememberToken();
        });
        parent::up($tableName, $company);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
}
