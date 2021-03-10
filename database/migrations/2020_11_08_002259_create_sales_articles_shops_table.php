<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesArticlesShopsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'sales_articles_shops', bool $company = false): void
    {
        Schema::create($tableName,
            function (Blueprint $table) {
                $table->integer('cant')->default(1);
                $table->decimal('price', 15, 2)->default(1);
                $table->foreignUuid('sale_id')->references('id')->on('sales')
                    ->onDelete('cascade');
                $table->foreignUuid('articles_shops_id')->references('id')->on('articles_shops')
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
        Schema::dropIfExists('sales_articles_shops');
    }
}
