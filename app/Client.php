<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Client
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @property mixed email
 * @property mixed firstName
 * @property mixed lastName
 * @property mixed address
 * @property mixed city
 * @property mixed province
 * @property mixed postalCode
 * @property mixed country
 * @property mixed barCode
 * @property mixed avatar
 * @property mixed description
 */
class Client extends Authenticatable implements MustVerifyEmail
{
    use Uuid, SoftDeletes, HasApiTokens, Uuid, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $fillable = [
        'firstName', 'lastName', 'email', 'phone', 'address', 'city', 'province', 'postalCode', 'country', 'barCode',
        'avatar', 'description', 'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
