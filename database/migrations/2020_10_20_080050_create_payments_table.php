<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'payments', bool $company = false): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('name');
            $table->string('method')->default('cash');
        });
        parent::up($tableName, $company);
        Schema::table('inventories', function($table) {
            $table->foreignUuid('payment_id')->nullable()->references('id')->on('payments')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
