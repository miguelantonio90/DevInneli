<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string phone
 * @property int|mixed pinCode
 */
class Employee extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
