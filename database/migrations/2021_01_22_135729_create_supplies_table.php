<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'supplies', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->foreignUuid('state_id')->references('id')->on('supply_states');
            $table->foreignUuid('sale_id')->references('id')->on('sales');
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
        Schema::dropIfExists('supplies');
    }
}
