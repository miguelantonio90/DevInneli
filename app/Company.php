<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Company extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'country'
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
