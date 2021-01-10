<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'payments', 'mark', 'suppliers', 'modifiers'
    ];
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'name', 'email', 'phone', 'country', 'currency', 'address', 'slogan', 'footer'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Articles::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function mark(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(Modifiers::class);
    }
}
