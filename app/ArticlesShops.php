<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ArticlesShops
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class ArticlesShops extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $fillable = ['price', 'stock', 'under_inventory', 'shop_id', 'article_id'];
    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }

    public function shops(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
