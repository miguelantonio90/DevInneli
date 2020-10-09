<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;

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

    protected $fillable = ['variant', 'articles_id','price', 'cost', 'ref', 'barCode'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }
}
