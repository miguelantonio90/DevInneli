<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesShopsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'articles_shops', bool $company = false): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->decimal('price',15,2)->default(0);
            $table->decimal('online_price', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->integer('start')->default(0);
            $table->integer('stock')->nullable();
            $table->integer('under_inventory')->nullable();
            $table->foreignUuid('article_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('shop_id')->references('id')->on('shops')
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
        Schema::dropIfExists('articles_shops');
    }
}
