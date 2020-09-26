<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @method find($id)
 */
class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'country', 'address'
    ];

    //
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
