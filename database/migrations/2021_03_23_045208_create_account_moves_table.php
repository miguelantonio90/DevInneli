<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountMovesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'account_moves', bool $company = false): void
    {
        Schema::create('account_moves', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->string('ref')->nullable();
            $table->string('detail')->default('');
            $table->double('debit')->default(0.00);
            $table->double('credit')->default(0.00);
            $table->string('account_id')->references('id')->on('accounting_accounts');
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
        Schema::dropIfExists('account_moves');
    }
}
