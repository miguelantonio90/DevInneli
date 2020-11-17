<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'suppliers', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('name');
            $table->string('identity')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('contract')->nullable();
            $table->string('note')->nullable();
            $table->foreignUuid('expense_id')->references('id')->on('expense_categories')
                ->onDelete('cascade');
        });
        parent::up($tableName, $company);
        Schema::table('inventories', function($table) {
            $table->foreignUuid('supplier_id')->nullable()->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
}
