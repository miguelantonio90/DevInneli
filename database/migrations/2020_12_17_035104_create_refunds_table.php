<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'refunds', bool $company = true): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->decimal('cant')->default(0);
            $table->decimal('money')->default(0);
            $table->foreignUuid('sale_id')->nullable()->references('id')->on('sales');
            $table->foreignUuid('article_id')->nullable()->references('id')->on('articles');
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
        Schema::dropIfExists('refunds');
    }
}
