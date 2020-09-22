<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name
 * @property mixed email
 * @property mixed pais
 */
class Shop extends Model
{
    //
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
