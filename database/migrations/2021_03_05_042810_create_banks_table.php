<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'banks', bool $company = true): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->string('name');
            $table->string('count_number');
            $table->boolean('default_bank')->default(false);
            $table->string('currency_id')->nullable()->references('id')->on('exchange_rates');
            $table->decimal('init_balance')->default(0.00);
            $table->date('date')->nullable(now());
            $table->string('description')->nullable();
            $table->string('color')->default('#27AF60');
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
        Schema::dropIfExists('banks');
    }
}
