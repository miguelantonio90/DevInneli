<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingCategoriesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'accounting_categories', bool $company = true): void
    {
        Schema::create('accounting_categories', function (Blueprint $table) {
            $table->string('name');
            $table->string('color');
            $table->string('description');

        });
        parent::up($tableName, $company);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_categories');
    }
}
