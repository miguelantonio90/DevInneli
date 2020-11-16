<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName='positions', $company=true):void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('key');
            $table->string('name');
            $table->boolean('accessPin')->default(0);
            $table->boolean('accessEmail')->default(0);
            $table->string('description')->nullable();
        });
        parent::up($tableName, $company);
        Schema::table('users', function($table) {
        $table->foreignUuid('position_id')->references('id')->on('positions')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('positions');
    }
}
