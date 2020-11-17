<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'marks', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('name');
            $table->string('description');
            $table->string('color')->nullable();
            $table->longText('avatar')->nullable();
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
        Schema::dropIfExists('marks');
    }
}
