<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tax
 * @package App
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method static find(array $idShops)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class Tax extends Model
{
    use Uuid;
    use SoftDeletes;

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
        'name', 'value', 'percent', 'company_id', 'type', 'existing'
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function article(): BelongsToMany
    {
        return $this->belongsToMany(Articles::class, 'article_tax', 'tax_id', 'article_id');
    }
}
