<?php

use App\BaseMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyPositionsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName='key_positions', $company=true):void
    {
        Schema::create('key_positions', function (Blueprint $table) {
            $table->string('key');
            $table->json('access_permit')->nullable();
        });
        parent::up($tableName, $company);
        Schema::table('positions', function($table) {
            $table->foreignUuid('key_position_id')->references('id')->on('key_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_positions');
    }
}
