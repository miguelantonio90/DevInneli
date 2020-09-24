<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string phone
 * @property int|mixed pinCode
 * @method static find()
 * @method static latest()
 */
class Employee extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }
}
