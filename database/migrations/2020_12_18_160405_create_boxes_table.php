<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'boxes', bool $company = true): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->string('name');
            $table->string('state')->default('close');
            $table->foreignUuid('shop_id')->nullable()->references('id')->on('shops');


        });
        parent::up($tableName, $company);
        Schema::table('boxes', function($table) {
            $table->foreignUuid('box_id')->nullable()->references('id')->on('boxes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes');
    }
}
