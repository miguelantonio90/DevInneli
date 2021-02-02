<?php

use App\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends BaseMigration
{
    /**
     * @param string $tableName
     * @param bool $company
     */
    public function up(string $tableName = 'notifications', bool $company = false): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->foreignUuid('company_id')->nullable()->references('id')->on('companies');
            $table->foreignUuid('to')->nullable()->references('id')->on('users');
            $table->string('action')->default('created');
            $table->string('element')->nullable();
            $table->string('type')->default('info');
            $table->boolean('read')->default(false);
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
        Schema::dropIfExists('notifications');
    }
}
