<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable  extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'variants', bool $company = false): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->string('name');
            $table->json('value');
            $table->foreignUuid('article_id')->references('id')->on('articles')
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
        Schema::dropIfExists('variants');
    }
}
