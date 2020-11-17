<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function  up(string $tableName = 'categories', bool $company = true): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->string('name');
            $table->string('color')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
