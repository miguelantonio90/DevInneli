<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Refund
 * @package App
 * @method static latest()
 * @method static findOrFail($id)
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 */
class Refund extends Model
{
    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'cant', 'money' , 'sale_id', 'article_id'
    ];



    public function article(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class);
    }
}
