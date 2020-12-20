<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenCloseBoxesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'open_close_boxes', bool $company = true): void
    {
        Schema::create('open_close_boxes', function (Blueprint $table) {
            $table->decimal('open_money')->default(0.00);
            $table->decimal('close_money')->default(0.00);
            $table->foreignUuid('box_id')->nullable()->references('id')->on('boxes');
            $table->foreignUuid('open_to')->nullable()->nullable()->references('id')->on('users');
            $table->foreignUuid('close_by')->nullable()->references('id')->on('users');
        });
        parent::up($tableName, $company);
        Schema::table('boxes', function($table) {
            $table->foreignUuid('open_id')->nullable()->references('id')->on('open_close_boxes')
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
        Schema::dropIfExists('open_close_boxes');
    }
}
