<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopTypeOfOrderTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'shop_type_of_order', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->foreignUuid('shop_id')->references('id')->on('shops')
                ->onDelete('cascade');
            $table->foreignUuid('type_of_order_id')->references('id')->on('type_of_orders')
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
        Schema::dropIfExists('shop_type_of_order');
    }
}
