<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Supply
 * @package App
 * @method static latest()
 * @method static findOrFail($id)
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 */
class Supply extends Model
{
    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id' , 'sale_id', 'state_id'
    ];
    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class, 'id','sale_id');
    }

    public function state(): HasOne
    {
        return $this->hasOne(SupplyState::class, 'id','state_id');
    }

    public function from(): HasOne
    {
        return $this->hasOne(User::class, 'id','created_by');
    }

}
