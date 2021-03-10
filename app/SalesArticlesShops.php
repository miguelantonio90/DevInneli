<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SalesArticlesShops
 * @package App
 * @method static latest()
 * @method static findOrFail($id)
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 */
class SalesArticlesShops extends Model
{
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_id', 'articles_shops_id', 'cant', 'price'
    ];

    public function articles_shops(): BelongsTo
    {
        return $this->belongsTo(ArticlesShops::class)
            ->with('shops')
            ->with('article');
    }

    public function discount(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'sales_articles_shop_discounts');
    }

    public function modifier(): BelongsToMany
    {
        return $this->belongsToMany(Modifiers::class, 'sales_articles_shop_modifiers', 'sales_articles_shops_id',
            'modifier_id');
    }
}
