<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VariantsShops
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class VariantsShops extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = ['vv_id', 'price', 'stock', 'under_inventory', 'shop_id', 'articles_id'];

    public function variants(): BelongsTo
    {
        return $this->belongsTo(VariantsValues::class);
    }

    public function shops(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }
}
