<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingAccountsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'accounting_accounts', bool $company = false): void
    {
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->string('name');
            $table->string('code');
            $table->string('description')->nullable();
            $table->double('init_balance')->default(0.00);
            $table->double('current_balance')->nullable()->default(0.00);
            $table->string('category_id')->references('id')->on('accounting_categories');
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
        Schema::dropIfExists('accounting_accounts');
    }
}
