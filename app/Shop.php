<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        $shop->country = $data['country'];
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

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
