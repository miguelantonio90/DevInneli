<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesArticlesShopsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'inventories_articles_shops', bool $company = false): void
    {
        Schema::create('inventories_articles_shops', function (Blueprint $table) {
            $table->integer('cant')->default(1);
            $table->decimal('cost',15,2)->default(1);
            $table->foreignUuid('inventory_id')->references('id')->on('inventories')
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
        Schema::dropIfExists('inventories_articles_shops');
    }
}
