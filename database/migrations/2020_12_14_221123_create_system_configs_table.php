<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemConfigsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName='system_configs', $company=true):void
    {
        Schema::create('system_configs', function (Blueprint $table) {
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->json('system_configs')->nullable();
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
        Schema::dropIfExists('sistem_configs');
    }
}
