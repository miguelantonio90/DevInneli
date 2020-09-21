<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
