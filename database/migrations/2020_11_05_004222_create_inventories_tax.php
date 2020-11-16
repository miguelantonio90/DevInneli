<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTax extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'inventories_tax', bool $company = false): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->foreignUuid('tax_id')->references('id')->on('taxes')
                ->onDelete('cascade');
            $table->foreignUuid('inventory_id')->references('id')->on('inventories')
                ->onDelete('cascade');
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
        Schema::dropIfExists('inventories_tax');
    }
}
