<?php

namespace App\Models\Accounting;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AccountingAccount
 * @package App\Models\Accounting
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @property mixed color
 */
class AccountingAccount extends Model
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
        'name', 'code', 'category_id'
    ];

    /**
     * @param $categoryId
     * @param $name
     * @param $code
     */
    public static function createFirst($categoryId, $name, $code):void{
       $account = self::create(['name'=>$name, 'category_id'=>$categoryId, 'code'=>$code]);
       $account->init_balance = 0.00;
       $account->current_balance = 0.00;
       $account->save();
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(AccountingCategory::class, 'category_id', 'id');
    }
}
