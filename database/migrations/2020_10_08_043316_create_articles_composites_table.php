<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesCompositesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'articles_composites', bool $company = false): void
    {
        Schema::create('articles_composites', function (Blueprint $table) {
            $table->integer('cant')->default(1);
            $table->decimal('price',15,2)->default(0);
            $table->foreignUuid('article_id')->references('id')->on('articles')
                ->onDelete('cascade');
            $table->foreignUuid('composite_id')->references('id')->on('articles')
                ->onDelete('cascade');
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
        Schema::dropIfExists('articles_composites');
    }
}
