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
            $table->string('state')->nullable();
            $table->string('type')->default('sale');
            $table->boolean('supply')->default(false); /// para definir si es un pedido
            $table->boolean('supply_process')->default(false); /// para ver si ya el pedido se convirtio en compra/venta
            $table->date('reclamation')->nullable();
            $table->foreignUuid('client_id')->nullable()->references('id')->on('clients');
            $table->foreignUuid('provider_id')->nullable()->references('id')->on('suppliers');
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
