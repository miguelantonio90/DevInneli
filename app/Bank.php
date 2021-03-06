<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use PayPal\Api\Currency;

/**
 * Class Bank
 * @package App
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class Bank extends Model
{
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = ['company_id', 'count_type', 'name', 'init_balance', 'date', 'count_number'];


    public function currency(): HasOne
    {
        return $this->hasOne(ExchangeRate::class,'id','currency_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
