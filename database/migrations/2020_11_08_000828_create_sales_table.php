<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'sales', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('no_facture')->nullable();
            $table->string('pay')->nullable();
            $table->string('state')->nullable();
            $table->foreignUuid('client_id')->references('id')->on('clients');
            $table->foreignUuid('payment_id')->nullable()->references('id')->on('payments');
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
        Schema::dropIfExists('sales');
    }
}
