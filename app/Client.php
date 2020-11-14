<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class Client extends Model
{
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *TODO: Poner mascara en codigo de Barras en la vista
     * @var string[]
     */
    protected $fillable = [
        'firstName', 'lastName', 'email', 'phone', 'address', 'city', 'province', 'postalCode', 'country', 'barCode',
        'avatar', 'description', 'company_id'
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
