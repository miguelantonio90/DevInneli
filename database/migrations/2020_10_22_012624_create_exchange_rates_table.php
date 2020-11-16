<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'exchange_rates', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('currency')->nullable();
            $table->float('change')->default(1);
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
        Schema::dropIfExists('exchange_rates');
    }
}
