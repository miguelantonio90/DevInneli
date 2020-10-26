<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShopTypeOfOrder
 * @package App
 * @property mixed available
 * @property mixed principal
 * @property mixed shop_id
 * @property mixed type_of_order_id
 *
 * @method static latest()
 * @method static findOrFail($idShopOrder)
 */
class ShopTypeOfOrder extends Model
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
    protected $fillable = ['available', 'principal', 'shop_id', 'type_of_order_id',];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function typeOfOrder(): BelongsTo
    {
        return $this->belongsTo(TypeOfOrder::class);
    }
}
