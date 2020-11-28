<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $softCascade = [
        'articlesShops',
        'variants', 'images', 'variantValues'
    ];
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id', 'category_id','created_by'
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
        return $this->hasMany(Variant::class, 'article_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ArticleImage::class, 'article_id');
    }

    public function variantValues(): HasMany
    {
        return $this->hasMany(Articles::class, 'parent_id')
            ->with('articlesShops')
            ->with('tax');
    }

    public function articlesShops(): HasMany
    {
        return $this->hasMany(ArticlesShops::class, 'article_id')->with('shops');
    }

    public function composites(): HasMany
    {
        return $this->hasMany(ArticlesComposite::class, 'article_id')->addSelect([
            'articles_name' => self::select('name')
                ->whereColumn('articles_composites.article_id', 'articles.id')
        ])->addSelect([
            'composite_name' => self::select('name')
                ->whereColumn('articles_composites.composite_id', 'articles.id')
        ]);
    }

    public function tax(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'article_tax', 'article_id', 'tax_id');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'articles_discounts', 'article_id', 'discount_id');
    }

}
