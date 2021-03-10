<?php


namespace App;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class BaseMigration extends Migration
{
    /**
     * @param  string  $tableName
     * @param  false|bool  $company
     */
    public function up(string $tableName, bool $company = false): void
    {
        Schema::table($tableName, function ($table) use ($company, $tableName) {
            if ($tableName !== 'users') {
                $table->uuid('id')->primary();
            }
            if ($company) {
                $table->foreignUuid('company_id')->nullable()->references('id')->on('companies')
                    ->onDelete('cascade');
            }
            $table->foreignUuid('created_by')->nullable()->references('id')->on('users');
            $table->foreignUuid('updated_by')->nullable()->references('id')->on('users');
            $table->foreignUuid('deleted_by')->nullable()->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
