<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingCategoriesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'accounting_categories', bool $company = true): void
    {
        Schema::create('accounting_categories', function (Blueprint $table) {
            $table->string('name');
            $table->string('color');
            $table->enum('nature', ['creditor', 'debtor'])->default('creditor');
            $table->boolean('default_category')->default(false);
            $table->string('description')->nullable();

        });
        parent::up($tableName, $company);
        Schema::table('accounting_categories', function($table) {
            $table->foreignUuid('parent_id')->nullable()->references('id')->on('accounting_categories')
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
        Schema::dropIfExists('accounting_categories');
    }
}
