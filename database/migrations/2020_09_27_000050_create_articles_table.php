<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function  up(string $tableName = 'articles', bool $company = true): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->string('name');
            $table->json('um')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('onlinePrice', 15, 2)->default(0);
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('color')->nullable();
            $table->string('ref')->nullable();
            $table->string('barCode')->nullable();
            $table->boolean('composite')->default(false);
            $table->string('description')->nullable();
            $table->boolean('track_inventory')->default(false);
            $table->foreignUuid('category_id')->nullable()->references('id')->on('categories');

        });
        parent::up($tableName, $company);
        Schema::table('articles', function($table) {
            $table->foreignUuid('parent_id')->nullable()->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
