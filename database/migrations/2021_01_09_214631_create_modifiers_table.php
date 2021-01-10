<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModifiersTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @param  string  $tableName
     * @param  bool  $company
     */
    public function up(string $tableName = 'modifiers', bool $company = true): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->string('name');
            $table->float('value')->nullable();
            $table->boolean('percent')->default(true);
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
        Schema::dropIfExists('modifiers');
    }
}
