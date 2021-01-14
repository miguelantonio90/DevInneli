<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Scalar\String_;

/**
 * Class Sale
 * @package App
 * @method static latest()
 * @method static findOrFail($id)
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 */
class Sale extends Model
{
    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $softCascade = ['articles_shops'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_facture', 'company_id' , 'payment_id'
    ];

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class);
    }

    public function pay_sales(): HasMany
    {
        return $this->hasMany(PaySale::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function articles_shops(): HasMany
    {
        return $this->hasMany(SalesArticlesShops::class)
            ->with('articles_shops')
            ->with('discount');
    }

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'sales_taxes');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'sales_discounts');
    }

    public function refounds():HasMany
    {
        return $this->hasMany(SalesArticlesShops::class);
    }
}
