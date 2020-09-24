<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method create(array $all)
 * @method static findOrFail(int $id)
 * @method static find(string $string, string $string1)
 * @method static where(string $string, string $string1)
 */
class Position extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
