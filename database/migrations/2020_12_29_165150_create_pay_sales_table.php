<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaySalesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName='pay_sales', $company=false):void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->foreignUuid('sale_id')->references('id')->on('sales')
                ->onDelete('cascade');
            $table->string('bank_payment_id')->references('id')->on('bank_payments');
            $table->decimal('cant')->default(0.00);
            $table->date('mora')->nullable();
            $table->decimal('cantMora')->nullable();
            $table->string('check_number')->nullable(); ////no de serie del cheque
            $table->string('check_emited')->nullable(); //// quien emitio el cheque
            $table->string('currency_id')->nullable()->references('id')->on('exchange_rates');
            $table->decimal('cant_pay')->default(0.00);
            $table->decimal('cant_back')->default(0.00);

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
        Schema::dropIfExists('pay_sales');
    }
}
