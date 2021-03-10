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
 * @property mixed name
 * @property mixed country
 * @method static create(array $array)
 * @method static latest()
 * @method static findOrFail($id)
 * @method static find(array $idShops)
 * @method static where(string $string, string $string1, mixed $id)
 */
class Shop extends Model
{
    use Uuid;
    use SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $softCascade = ['shopTypeOfOrders', 'assistances', 'articlesShops', 'shopTypeOfOrders', 'modifiers'];
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'country', 'company_id', 'address', 'description', 'phone'];

    //

    /**
     * @param $data
     * @param $company
     * @return Shop
     */
    public static function createFirst($data, $company): Shop
    {
        $shop = new self();
        $shop->name = $data['shopName'];
        $shop->country = $data['country']['id'];
        $shop->phone = $data['phone'];
        $shop->company_id = $company->id;
        $shop->save();
        Box::createFirst($shop, $company);
        return $shop;

    }

    /**
     * @param $properties
     * @return Shop
     */
    public static function makeShop($properties): Shop
    {
        $shop = new self();
        $shop->name = $properties['name'];
        $shop->country = $properties['country'];
        $shop->address = $properties['address'];
        $shop->phone = $properties['phone'];
        $shop->description = $properties['description'];
        $shop->company_id = $properties['company_id'];
        return $shop;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function typeOfOrders(): BelongsToMany
    {
        return $this->belongsToMany(TypeOfOrder::class);
    }

    public function shopTypeOfOrders(): HasMany
    {
        return $this->hasMany(ShopTypeOfOrder::class);
    }

    public function assistances(): HasMany
    {
        return $this->hasMany(Assistance::class);
    }

    public function articlesShops(): HasMany
    {
        return $this->hasMany(ArticlesShops::class);
    }

    public function boxes(): HasMany
    {
        return $this->hasMany(Box::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function modifiers(): BelongsToMany
    {
        return $this->belongsToMany(Modifiers::class);
    }
}
