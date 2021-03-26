<?php

namespace App\Models\Accounting;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class AccountingCategory
 * @package App\Models\Accounting
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @property mixed color
 */
class AccountingCategory extends Model
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
        'name', 'color', 'company_id'
    ];

    /**
     * @param $companyId
     */
    public static function createFirsts($companyId):void
    {
        $actives = self::create(['name'=>'actives', 'company_id'=>$companyId, 'description'=>'', 'color'=>'#0d47a1']);
        $actives->nature = 'debtor';
        $actives->default_category = true;
        $actives->save();
        AccountingAccount::createFirst($actives->id, '100', 'A000');
        $passives = self::create(['name'=>'passives', 'company_id'=>$companyId, 'description'=>'', 'color'=>'#00c853']);
        $passives->nature = 'creditor';
        $passives->default_category = true;
        $passives->save();
        AccountingAccount::createFirst($passives->id, '200', 'P000');
        $incomes = self::create(['name'=>'incomes', 'company_id'=>$companyId, 'description'=>'', 'color'=>'#0000ff']);
        $incomes->nature = 'creditor';
        $incomes->default_category = true;
        $incomes->save();
        AccountingAccount::createFirst($incomes->id, '300', 'I000');
        $expenses =  self::create(['name'=>'expenses', 'company_id'=>$companyId, 'description'=>'', 'color'=>'#ff0000']);
        $expenses->nature = 'debtor';
        $expenses->default_category = true;
        $expenses->save();
        AccountingAccount::createFirst($expenses->id, '400', 'E000');
    }


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(AccountingAccount::class, 'category_id', 'id');
    }

    public function sub_categories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }


}
