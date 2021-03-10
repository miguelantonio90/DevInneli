<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineConfigsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'online_configs', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('template')->default('shipit');
            $table->foreignUuid('shop_id')->nullable()->references('id')->on('shops');
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
        Schema::dropIfExists('online_configs');
    }
}
