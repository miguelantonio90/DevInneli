<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SystemConfig
 * @package App
 * @method static latest()
 * @method static findOrFail(int $id)
 * @method static find(string $string, string $string1)
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 */
class SystemConfig extends Model
{
    use Uuid, SoftDeletes;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = ['system_configs'];
}
