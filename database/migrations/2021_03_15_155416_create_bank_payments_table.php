<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankPaymentsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'bank_payments', bool $company = false): void
    {
        Schema::create('bank_payments', function (Blueprint $table) {
            $table->string('bank_id')->nullable()->references('id')->on('banks');
            $table->string('payment_id')->nullable()->references('id')->on('payments');
        });
        parent::up($tableName, $company);
        Schema::table('refunds', function($table) {
            $table->foreignUuid('bank_payment_id')->nullable()->references('id')->on('bank_payments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_payments');
    }
}
