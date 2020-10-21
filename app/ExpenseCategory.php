<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static create(array $array)
 * @method static latest()
 */
class ExpenseCategory extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'name', 'company_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
