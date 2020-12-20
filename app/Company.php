<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @method static latest()
 * @method find($id)
 * @method static where(string $string, string $string1, string $email)
 */
class Company extends Model
{
    use Uuid;
    use SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $softCascade = [
        'users', 'positions', 'shops',
        'categories', 'articles', 'clients', 'discounts', 'inventories',
        'payments', 'mark', 'suppliers'
    ];
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'name', 'email', 'phone', 'country', 'currency', 'address',
    ];

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

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function articles()
    {
        return $this->hasMany(Articles::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function mark()
    {
        return $this->hasMany(Mark::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
