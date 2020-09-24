<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static findOrFail(int $id)
 * @method static find(string $string, string $string1)
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 */
class Position extends Model
{
    protected $fillable = [
        'key', 'name', 'accessEmail', 'accessPin',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function createFirst($company): Position
    {
        $position = new Position();
        $position->key = 'manager';
        $position->name = 'Manager';
        $position->accessEmail = 1;
        $position->accessPin = 1;
        $position->company_id = $company->id;
        $position->save();
        return $position;
    }
}
