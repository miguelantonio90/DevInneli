<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Articles
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class Articles extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id', 'category_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function variants_values(): HasMany
    {
        return $this->hasMany(VariantsValues::class);
    }

    public function variants_shops(): HasMany
    {
        return $this->hasMany(VariantsShops::class)->addSelect([
            'name' => Shop::select('name')
                ->whereColumn('shops.id', 'variants_shops.shop_id')
        ])->addSelect([
            'variant' => VariantsValues::select('variant')
                ->whereColumn('variants_values.id', 'variants_shops.vv_id')
        ]);
    }

    public function composites(): HasMany
    {
        return $this->hasMany(ArticlesComposite::class)->addSelect([
            'articles_name' => self::select('name')
                ->whereColumn('articles_composites.articles_id', 'articles.id')
        ])->addSelect([
            'composite_name' => self::select('name')
                ->whereColumn('articles_composites.composite_id', 'articles.id')
        ]);
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }
}
