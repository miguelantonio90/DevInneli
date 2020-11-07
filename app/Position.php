<?php

namespace App;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static latest()
 * @method static findOrFail(int $id)
 * @method static find(string $string, string $string1)
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 */
class Position extends Model
{

    use Uuid, SoftDeletes, SoftCascadeTrait;

    protected $dates = ['deleted_at'];

    protected $softCascade = [ 'users'];

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'key', 'name', 'accessEmail', 'accessPin', 'description', 'company_id',
    ];

    public static function createFirst($company): Position
    {
        $position = new Position();
        $position->key = 'super_manager';
        $position->name = 'CEO Manager';
        $position->accessEmail = 1;
        $position->accessPin = 1;
        $position->company_id = $company->id;
        $position->save();
        return $position;
    }

    public static function makePosition($company_id, $properties)
    {
        $position = new Position();
        $position->key = $properties['name'];
        $position->name = $properties['country'];
        $position->accessEmail = $properties['address'];
        $position->accessPin = $properties['phone'];
        $position->description = $properties['description'];
        $position->company_id = $company_id;
        return $position;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
