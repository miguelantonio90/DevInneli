<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static create(array $array)
 * @method static findOrFail($id)
 * @method static where(string $string, string $string1, $get)
 */
class ExchangeRate extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'country', 'currency', 'change', 'company_id',
    ];
}
