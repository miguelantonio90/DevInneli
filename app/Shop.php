<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed name
 * @property mixed country
 * @method static create(array $array)
 * @method static latest()
 * @method static findOrFail($id)
 * @method static find(array $idShops)
 */
class Shop extends Model
{
    use Uuid;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'country', 'company_id', 'address', 'description', 'phone'];

    //

    public static function createFirst($data, $company)
    {
        $shop = new Shop();
        $shop->name = $data['shopName'];
        $shop->country = $data['country']['id'];
        $shop->company_id = $company->id;
        $shop->save();

        return $shop;

    }

    public static function makeShop($properties)
    {
        $shop = new Shop();
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

    public function shopTypeOfOrders()
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

}
