<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistancesTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function  up(string $tableName = 'assistances', bool $company = true): void
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->dateTime('datetimeEntry');
            $table->dateTime('datetimeExit');
            $table->integer('totalHours');
            $table->foreignUuid('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('assistances');
    }
}
