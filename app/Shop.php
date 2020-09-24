<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name
 * @property mixed email
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
    protected $fillable = ['name', 'email', 'country', 'user_id', 'address', 'description', 'phone'];
    //
    public function users()
    {
        return $this->belongsToMany(Employee::class);
    }
}
