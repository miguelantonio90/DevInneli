<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 * @property mixed email
 * @property mixed firstName
 * @property mixed lastName
 * @property mixed pinCode
 * @property mixed phone
 * @property mixed country
 * @property mixed isAdmin
 * @property mixed isManager
 * @property mixed company_id
 * @property mixed|string password
 * @property mixed avatar
 * @property mixed position_id
 */
class Inventory extends Model
{
    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $softCascade = ['articles_shops'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_facture', 'pay', 'company_id', 'payment_id', 'supplier_id'
    ];

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payments(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function articles_shops(): HasMany
    {
        return $this->hasMany(InventoriesArticlesShops::class)
            ->with('articles_shops');
    }

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'inventories_tax');
    }

}
