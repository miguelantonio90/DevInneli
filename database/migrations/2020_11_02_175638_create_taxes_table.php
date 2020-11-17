<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'taxes', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('name');
            $table->float('value')->nullable();
            $table->string('type')->default('included');
            $table->boolean('existing')->default(false);
            $table->string('percent')->default(true);
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
        Schema::dropIfExists('taxes');
    }
}
