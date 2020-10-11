<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VariantsValues
 * @package App
 * @method static create(array $array)
 * @method static latest()
 * @method static findOrFail($id)
 * @method static find(array $idShops)
 */
class VariantsValues extends Model
{
    use Uuid;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $fillable = ['variant', 'articles_id', 'price', 'cost', 'ref', 'barCode'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }
}
