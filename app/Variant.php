<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Variant
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class Variant extends Model
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','value', 'articles_id',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }
    public function variants(): HasMany
    {
        return $this->hasMany(Articles::class)->addSelect([
            'variantsValues' => self::select('*')
                ->whereColumn('articles.variant_id', 'variants.id')
        ]);
    }
}
