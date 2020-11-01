<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class ArticlesShop
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class ArticlesShop extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    protected $fillable = ['price', 'stock', 'under_inventory', 'shops_id', 'articles_id'];
    public function articles(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }

    public function shops(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
