<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoriesArticlesShops
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class InventoriesArticlesShops extends Model
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
        'inventory_id', 'articles_shops_id', 'cant', 'cost'
    ];



}
