<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @method find($id)
 * @method static where(string $string, string $string1, string $email)
 */
class Company extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'name', 'email', 'phone', 'country', 'currency', 'address',
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
        return $this->hasMany(Shops::class);
    }
}
