<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use PayPal\Api\Currency;

/**
 * Class Bank
 * @package App
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 */
class Bank extends Model
{
    use Uuid;
    use SoftDeletes;

    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = ['company_id', 'name', 'init_balance', 'date', 'count_number'];

    /**
     * @param Company $company
     * @param User $user
     */
    public static function createFirst(Company $company, User $user): void
    {
        $bank = self::create([
            'company_id' => $company->id,
            'name' => 'Bank',
            'count_number' => '',
            'init_balance' => 0.00
        ]);
        $bank->description = 'Default';
        $bank->default_bank = true;
        $payments = Payment::latest()->get();
        foreach ($payments as $key => $payment) {
            $bPayment = BankPayment::create([
                'bank_id' => $bank->id,
                'payment_id' => $payment->id
            ]);
            $bPayment->created_by = $user->id;
            $bPayment->save();
        }
        $bank->created_by = $user->id;
        $bank->save();
    }

    public function currency(): HasOne
    {
        return $this->hasOne(ExchangeRate::class, 'id', 'currency_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payments_banks(): hasMany
    {
        return $this->hasMany(BankPayment::class, 'bank_id', 'id')->with('payment');
    }
}
