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
            $table->string('payment_id')->references('id')->on('payments');
            $table->decimal('cant')->default(0.00);
            $table->date('mora')->nullable();
            $table->decimal('cantMora')->nullable();

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
