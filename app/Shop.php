<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
