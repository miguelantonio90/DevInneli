<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Notification
 * @package App
 * @method static latest()
 * @method static findOrFail($id)
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @method static where(string $string, string $string1, string $string2)
 */
class Notification extends Model
{
    use Uuid, SoftDeletes, SoftCascadeTrait;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];
    protected $fillable = [
        'company_id', 'params', 'msg', 'type', 'read'
    ];////the params will be divided by ','

}
