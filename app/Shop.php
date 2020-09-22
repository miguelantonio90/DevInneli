<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name
 * @property mixed email
 * @property mixed country
 * @method static latest()
 */
class Shop extends Model
{
    //
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
